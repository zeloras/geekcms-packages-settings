<?php

namespace GeekCms\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminController extends Controller
{
    /**
     * Page with main settings.
     *
     * @throws \Exception
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $configs = [];

        if (class_exists('ConfigManager')) {
            $get_configs = \ConfigManager::getSettings();
            foreach ($get_configs as $config) {
                $configs[$config->key] = $config->value;
            }
        } else {
            $configs = \Config::getMany(config('module_setting.main_variables', []));
        }

        $configs['themes.default'] = \Theme::current()->name;

        return view('setting::admin.index', [
            'configs' => $configs,
        ]);
    }

    /**
     * Save settings list.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {
        $configs = $request->post('config', []);

        foreach ($configs as $key => $config) {
            if (!empty($key)) {
                \ConfigManager::set($key, $config);
            }
        }

        return redirect()->back();
    }

    /**
     * Page with variables.
     *
     * @throws \Exception
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function variables()
    {
        // todo: need add new keys from admin
        $configs = \Config::getMany(config('module_setting.main_variables', []));

        foreach (\ConfigManager::getSettings() as $item) {
            if (!\array_key_exists($item->key, $configs)) {
                $configs[$item->key] = $item->value;
            }
        }

        return view('setting::admin.variables', [
            'configs' => $configs,
        ]);
    }

    /**
     * Save variables list.
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save_variables(Request $request)
    {
        $config_manager = \ConfigManager::getInstance();
        $configs_db = [];
        foreach ($config_manager->getSettings() as $item) {
            if (!\array_key_exists($item->key, $configs_db) && !empty($item->key)) {
                $configs_db[$item->key] = $item->value;
            }
        }

        $configs = $this->splitFormArray($request->post('configs', []));

        // $configs save
        foreach ($configs as $config) {
            if ($key = array_get($config, 'key')) {
                unset($configs_db[$key]);
                \ConfigManager::set($key, array_get($config, 'value'));
            }
        }

        if ($configs_db && \count($configs_db)) {
            foreach ($configs_db as $key => $value) {
                \ConfigManager::delete($key);
            }
        }

        return redirect()->back();
    }

    /**
     * Build array from request multidata form.
     *
     * @param $formArray
     *
     * @return array
     */
    protected function splitFormArray($formArray)
    {
        $result = [];

        if (\is_array($formArray)) {
            foreach ($formArray as $key => $data) {
                foreach ($data as $k => $value) {
                    $result[$k][$key] = $value;
                }
            }
        }

        return $result;
    }
}
