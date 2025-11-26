<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttachmentModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'medias';

    protected $fillable = [
        'conversation_id',
        'message_id',
        'uploader_id',
        'filename',
        'original_name',
        'file_path',
        'file_size',
        'mime_type',
        'file_extension',
        'width',
        'height',
        'duration',
        'thumbnail_path',
        'preview_path',
        'platform_file_id',
        'platform_type',
        'upload_status',
        's3_url',
        'upload_started_at',
        'upload_completed_at',
        'upload_error',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
            'duration' => 'integer',
            'upload_started_at' => 'datetime',
            'upload_completed_at' => 'datetime',
        ];
    }

    protected static function newFactory()
    {
        return \Database\Factories\AttachmentFactory::new();
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ConversationModel::class, 'conversation_id');
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(MessageModel::class, 'message_id');
    }

    public function isImage(): bool
    {
        return $this->mime_type && str_starts_with($this->mime_type, 'image/');
    }

    public function isVideo(): bool
    {
        return $this->mime_type && str_starts_with($this->mime_type, 'video/');
    }

    public function isAudio(): bool
    {
        return $this->mime_type && str_starts_with($this->mime_type, 'audio/');
    }

    public function isDocument(): bool
    {
        return $this->mime_type && (
            str_starts_with($this->mime_type, 'application/') ||
            str_starts_with($this->mime_type, 'text/')
        );
    }

    public function hasThumbnail(): bool
    {
        return $this->thumbnail_path !== null;
    }

    public function hasPreview(): bool
    {
        return $this->preview_path !== null;
    }

    public function getFormattedFileSize(): string
    {
        if ($this->file_size === null) {
            return 'Unknown';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = $this->file_size;
        $unitIndex = 0;

        while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
            $bytes /= 1024;
            $unitIndex++;
        }

        return round($bytes, 2).' '.$units[$unitIndex];
    }

    // Upload status helper methods
    public function isLocal(): bool
    {
        return $this->upload_status === 'local';
    }

    public function isUploading(): bool
    {
        return $this->upload_status === 'uploading';
    }

    public function isUploaded(): bool
    {
        return $this->upload_status === 'uploaded';
    }

    public function isUploadFailed(): bool
    {
        return $this->upload_status === 'failed';
    }

    public function hasS3Url(): bool
    {
        return $this->s3_url !== null;
    }

    public function getFileUrl(): string
    {
        // Return S3 URL if available, otherwise local file path
        return $this->hasS3Url() ? $this->s3_url : $this->file_path;
    }
}
