<?php

namespace App\Features\Common\Rules;

use App\Models\ConversationModel;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class ConversationBelongsToCustomer implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null) {
            return;
        }

        $customerId = $this->data['customer_id'] ?? null;

        if (! $customerId) {
            $fail('The :attribute validation requires customer_id to be present.');

            return;
        }

        $conversation = ConversationModel::query()
            ->where('id', $value)
            ->where('customer_id', $customerId)
            ->first();

        if (! $conversation) {
            $fail('The selected :attribute does not belong to the specified customer.');
        }
    }

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
