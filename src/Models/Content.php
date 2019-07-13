<?php

namespace GeekCms\Content\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ponich\Eloquent\Traits\HasAttachment;
use Ponich\Eloquent\Traits\VirtualAttribute;
use function is_array;

class Content extends Model
{
    use SoftDeletes;
    use VirtualAttribute;
    use HasAttachment;

    public static $content;
    /**
     * @var string User model namespace
     */
    public static $userModel = User::class;
    public $table = 'content';
    public $guarded = [
        'id',
    ];
    public $with = [
        'parent',
    ];
    public $virtalAttributes = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (self::$content) {
            $this->setContentType(self::$content);
        }
    }

    public function setContentType($content)
    {
        // attributes
        if (isset($content->attributes) && is_array($content->attributes)) {
            foreach ($content->attributes as $key => $type) {
                $this->virtalAttributes[] = (is_numeric($key)) ? $type : $key;
            }
        }
    }

    /**
     * Model boot.
     */
    protected static function boot()
    {
        parent::boot();

        // creating
        self::creating(function ($model) {
            $model->created_by = auth()->id();

            return $model;
        });

        // updating
        self::updating(function ($model) {
            $model->updated_by = auth()->id();

            return $model;
        });

        // deleting
        self::deleting(function ($model) {
            $model->deleted_by = auth()->id();

            return $model;
        });
    }

    /**
     * Route key.
     *
     * @return string
     */
    public function getRouteKey()
    {
        return 'slug';
    }

    /**
     * Get item url.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        $path = $this->getSlugTree($this);

        return route('page.open', [
            'slug' => implode(DIRECTORY_SEPARATOR, $path),
        ]);
    }

    /**
     * Parse recursive parent slug.
     *
     * @param $model
     * @param int $level
     *
     * @return array
     */
    protected function getSlugTree($model, $level = 0)
    {
        $path = [];
        ++$level;

        $path[] = array_get($model, 'slug');

        if ($parentModel = array_get($model, 'parent')) {
            $path = array_merge($path, $this->getSlugTree($parentModel, $level));
        }

        if (1 === $level) {
            return array_reverse($path);
        }

        return $path;
    }

    public function getUrlEditAttribute()
    {
        return route('content.edit', [
            'type' => $this->type,
            'item' => $this->id,
        ]);
    }

    /**
     * Safe save parent item.
     *
     * @param int $id
     */
    public function setParentIdAttribute($id = 0)
    {
        $parentId = ($id === $this->id) ? 0 : $id;
        $parentId = (!$parentId) ? 0 : $parentId;

        $this->attributes['parent_id'] = $parentId;
    }

    /**
     * Get parent item.
     *
     * @return BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get children items.
     *
     * @return HasMany
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Get created user.
     *
     * @return null|Model|object|static
     */
    public function getCreatedByAttribute()
    {
        $this->created_by_id = $this->attributes['created_by'];

        $user = $this->belongsTo(self::$userModel, 'created_by_id', 'id')->first();

        $this->created_by_id = null;

        return $user;
    }

    /**
     * Get updated user.
     *
     * @return null|Model|object|static
     */
    public function getUpdatedByAttribute()
    {
        $this->updated_by_id = $this->attributes['updated_by'];

        $user = $this->belongsTo(self::$userModel, 'updated_by_id', 'id')->first();

        $this->updated_by_id = null;

        return $user;
    }

    /**
     * Get deleted user.
     *
     * @return null|Model|object|static
     */
    public function getDeletedByAttribute()
    {
        $this->deleted_by_id = $this->attributes['deleted_by'];

        $user = $this->belongsTo(self::$userModel, 'deleted_by_id', 'id')->first();

        $this->deleted_by_id = null;

        return $user;
    }
}
