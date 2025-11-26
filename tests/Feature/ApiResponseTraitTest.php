<?php

namespace Tests\Feature;

use App\Exceptions\CustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class ApiResponseTraitTest extends TestCase
{
    public function test_success_response_wraps_payload(): void
    {
        Route::get('/test-success-response', function () {
            return new class {
                use \App\Http\Controllers\ApiResponseTrait;
            }->responseSuccess(['foo' => 'bar'], 'done');
        });

        $response = $this->getJson('/test-success-response');

        $response->assertOk()
            ->assertJsonStructure(['_status', '_success', '_messages', '_data', '_extra'])
            ->assertJson([
                '_success' => true,
                '_messages' => ['done'],
                '_data' => ['foo' => 'bar'],
            ]);
    }

    public function test_custom_exception_is_formatted(): void
    {
        Route::get('/test-custom-exception', function () {
            throw new CustomException('Oops', ResponseAlias::HTTP_BAD_REQUEST, ['foo' => 'bar']);
        });

        $response = $this->getJson('/test-custom-exception');

        $response->assertStatus(ResponseAlias::HTTP_BAD_REQUEST)
            ->assertJson([
                '_success' => false,
                '_messages' => ['Oops'],
                '_data' => ['foo' => 'bar'],
            ]);
    }

    public function test_validation_exception_is_formatted(): void
    {
        Route::get('/test-validation-exception', function () {
            throw ValidationException::withMessages([
                'email' => ['The email field is required.'],
            ]);
        });

        $response = $this->getJson('/test-validation-exception');

        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                '_success' => false,
                '_messages' => ['Validation failed.'],
            ])
            ->assertJsonPath('_data.errors.email.0', 'The email field is required.');
    }

    public function test_model_not_found_exception_is_formatted(): void
    {
        Route::get('/test-model-not-found', function () {
            throw new ModelNotFoundException();
        });

        $response = $this->getJson('/test-model-not-found');

        $response->assertStatus(ResponseAlias::HTTP_NOT_FOUND)
            ->assertJson([
                '_success' => false,
            ]);
    }
}
