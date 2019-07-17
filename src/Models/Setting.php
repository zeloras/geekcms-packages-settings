<?php

namespace GeekCms\Setting\Models;

use App\Models\MainModel;

class Setting extends MainModel
{
    protected $table = 'settings';

    protected $guarded = [];

    /**
     * Set value with serialize data.
     *
     * @param $value
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = serialize($value);
    }

    /**
     * Get value attribute with unserialize data.
     *
     * @return mixed
     */
    public function getValueAttribute()
    {
        return unserialize($this->attributes['value']);
    }
}
