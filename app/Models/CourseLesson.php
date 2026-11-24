<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CourseLesson extends Model
{
    protected $fillable = [
        'course_id',
        'course_part_id',
        'title',
        'description',
        'sort_order',
        'duration_minutes',
        'is_preview',
        'video_path',
        'video_url',
        'video_filename',
        'video_mime',
        'video_size',
    ];

    protected $casts = [
        'is_preview' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(CoursePart::class, 'course_part_id');
    }

    public function videoPublicUrl(): ?string
    {
        if (! empty($this->video_url)) {
            return $this->video_url;
        }
        if (! empty($this->video_path)) {
            return asset('storage/'.ltrim($this->video_path, '/'));
        }

        return null;
    }

    public function deleteVideoFile(): void
    {
        if (! empty($this->video_path)) {
            Storage::disk('public')->delete($this->video_path);
        }
    }
}