<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Attachment extends Model
{
    use SoftDeletes;

    // Các thuộc tính cho phép được gán
    protected $fillable = [
        'original_name',
        'stored_path',
        'mime_type',
        'size_in_bytes',
        'duration_in_seconds',
        'storage_disk',
        'visibility',
        'additional_metadata',
        'hash_checksum',
        'attachable_id',
        'attachable_type'
    ];

    // Các trường sẽ tự động được cast
    protected $casts = [
        'additional_metadata' => 'array',
    ];

    /**
     * Định nghĩa quan hệ morph đến các model khác
     */
    public function attachable()
    {
        return $this->morphTo();
    }

    /**
     * Kiểm tra nếu file có thể truy cập công khai
     */
    public function isPublic()
    {
        return $this->visibility === 'public';
    }

    /**
     * Lấy URL đầy đủ của file nếu file nằm trên public disk hoặc có thể chia sẻ công khai
     */
    public function getFullUrl()
    {
        if ($this->isPublic() && $this->storage_disk === 'public') {
            $path = Str::startsWith($this->stored_path, '/')
                ? $this->stored_path
                : '/' . $this->stored_path;
            return asset('storage' . $path);
        } elseif ($this->storage_disk === 'Wasabi') {
            return Storage::disk('Wasabi')->url($this->stored_path);
        }
        return null;
    }

    /**
     * Tính kích thước tệp theo định dạng dễ đọc
     */
    public function getReadableSize()
    {
        $size  = $this->size_in_bytes;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $size >= 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, 2) . ' ' . $units[$i];
    }

    public function getAttachmentPageContent()
    {
        $url = $this->getTemporaryUrl();
        if ($url) {
            return file_get_contents($url);
        }
        return '';
    }

    public function getTemporaryUrl(int $expiration = null)
    {
        if ($expiration === null) {
            $expiration = config('filesystems.page_content_temporary_url_expiration_time');
        }
        return Storage::disk($this->storage_disk)->temporaryUrl(
            $this->stored_path, now()->addMinutes($expiration)
        );
    }
}
