<?php

namespace GeekCms\Setting\Repository;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use GeekCms\Setting\Models\Setting;

class ManagerRepository
{
    /**
     * @var null|self
     */
    private static $_instance;

    /**
     * @var string
     */
    private $cacheSettingKey = 'setting.manager.settings';

    /**
     * @var Collection
     */
    private $storage;

    /**
     * Instance.
     *
     * @return null|self
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new static();
            self::$_instance->init();
        }

        return self::$_instance;
    }

    /**
     * Init.
     */
    public function init()
    {
        $this->storage = $this->getSettings(true);
        $this->marge($this->storage);
    }

    /**
     * Load settings from cache storage|database.
     *
     * @param bool $clear
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function getSettings($clear = false)
    {
        // clear cache
        if ($clear || !config('cache.settings.store')) {
            if (Cache::has($this->cacheSettingKey)) {
                $try_remove = Cache::forget($this->cacheSettingKey);
                if (!$try_remove) {
                    throw new \Exception('Bladskiy cache');
                }
            }
        }

        return Cache::remember($this->cacheSettingKey, \Gcms::MAIN_CACHE_TIMEOUT_KEY, function () {
            try {
                return Setting::all();
            } catch (\Illuminate\Database\QueryException $e) {
                return collect();
            }
        });
    }

    /**
     * Marge database config with local.
     *
     * @return \Illuminate\Config\Repository
     */
    public function marge(Collection $setings)
    {
        $setings->each(function ($setting) {
            Config::set($setting->key, $setting->value);
        });

        return config();
    }

    /**
     * Set config.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function set($key, $value = null)
    {
        $setting = Setting::updateOrCreate(['key' => $key], [
            'key' => $key,
            'value' => $value,
        ]);

        // clear
        $clear = $this->storage->where('key', $key)->keys()->toArray();
        $this->storage->forget($clear);

        // update
        $this->storage->push($setting);
        Config::set($setting->key, $setting->value);
    }

    /**
     * Remove config by key.
     *
     * @param $key
     */
    public function delete($key)
    {
        // clear
        $clear = $this->storage->where('key', $key)->first();
        $clear->delete();
    }

    /**
     * Get config.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $item = $this->storage->where('key', $key)->first();

        return ($item) ? $item->value : $default;
    }
}
