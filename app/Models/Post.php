<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Post extends Model
{
    use HasFactory , SoftDeletes;

    protected $guarded = [];




    protected static function booted()
    {
        static::addGlobalScope('pinned', function (Builder $builder) {
            $builder->orderBy('is_pinned', 'desc');
        });
    }

    protected static function boot()
    {
        parent::boot();

        // Clear cache on creating, updating or deleting a user
        static::created(function () {
            Cache::forget('stats');
        });

        static::updated(function () {
            Cache::forget('stats');
        });

        static::deleted(function () {
            Cache::forget('stats');
        });
    }


    public function tags() : BelongsToMany
    {

        return $this->belongsToMany(Tag::class,'tags_posts');

    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getCoverImageUrlAttribute()
    {

        if ($this->cover_image) {

            return asset(config('app.url').'/uploads/' . $this->cover_image);
        }

        // Return null or a default image if no image is available
        return asset('uploads/imgs/default_image.png');
    }


}
