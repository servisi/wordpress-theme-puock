<?php

namespace Publicus\Theme\setting;

use Publicus\Theme\setting\options\OptionAbout;
use Publicus\Theme\setting\options\OptionAd;
use Publicus\Theme\setting\options\OptionAi;
use Publicus\Theme\setting\options\OptionBasic;
use Publicus\Theme\setting\options\OptionCache;
use Publicus\Theme\setting\options\OptionCarousel;
use Publicus\Theme\setting\options\OptionCms;
use Publicus\Theme\setting\options\OptionCompany;
use Publicus\Theme\setting\options\OptionDebug;
use Publicus\Theme\setting\options\OptionEmail;
use Publicus\Theme\setting\options\OptionExtend;
use Publicus\Theme\setting\options\OptionGlobal;
use Publicus\Theme\setting\options\OptionAuth;
use Publicus\Theme\setting\options\OptionResource;
use Publicus\Theme\setting\options\OptionScript;
use Publicus\Theme\setting\options\OptionSeo;
use Publicus\Theme\setting\options\OptionValidate;

class PuockSetting
{
    public function init()
    {
        add_action("admin_menu", array($this, '__wp_reg_menu'));
        add_action('admin_init', array($this, '__wp_admin_init'));
    }

    public function option_menus_register()
    {
        $classes = [];
        $classes[] = ['class' => OptionGlobal::class, 'sort' => 1];
        $classes[] = ['class' => OptionBasic::class, 'sort' => 2];
        $classes[] = ['class' => OptionCarousel::class, 'sort' => 3];
        $classes[] = ['class' => OptionCms::class, 'sort' => 4];
        $classes[] = ['class' => OptionCompany::class, 'sort' => 5];
        $classes[] = ['class' => OptionAuth::class, 'sort' => 6];
        $classes[] = ['class' => OptionAi::class, 'sort' => 7];
        $classes[] = ['class' => OptionValidate::class, 'sort' => 7];
        $classes[] = ['class' => OptionAd::class, 'sort' => 8];
        $classes[] = ['class' => OptionEmail::class, 'sort' => 9];
        $classes[] = ['class' => OptionSeo::class, 'sort' => 10];
        $classes[] = ['class' => OptionExtend::class, 'sort' => 10];
        $classes[] = ['class' => OptionScript::class, 'sort' => 11];
        $classes[] = ['class' => OptionCache::class, 'sort' => 12];
        $classes[] = ['class' => OptionDebug::class, 'sort' => 13];
        $classes[] = ['class' => OptionResource::class, 'sort' => 14];
        $classes[] = ['class' => OptionAbout::class, 'sort' => 99];
        $classes = apply_filters('pk_theme_option_menus_register', $classes, 10, 1);
        array_multisort(array_column($classes, 'sort'), SORT_ASC, $classes);
        return $classes;
    }

    public function __wp_admin_init()
    {
    }

    public function __wp_reg_menu()
    {
        add_menu_page(
            __('Puock主题配置', PUBLICUS),
            __('Puock主题配置', PUBLICUS),
            "manage_options",
            "publicus-options",
            array($this, 'setting_page'),
            PUBLICUS_ABS_URI . '/assets/img/logo/publicus-20.png',
        );
    }

    function setting_page()
    {
        $menus = $this->option_menus_register();
        if (!current_user_can('edit_theme_options')) {
            wp_send_json_error(__('权限不足', PUBLICUS));
        }
        $fields = [];
        foreach ($menus as $menu) {
            $f = (new $menu['class']())->get_fields();
            $fields[] = apply_filters('pk_load_theme_option_fields_'.$f['key'], $f);
        }
        do_action('pk_get_theme_option_fields', $fields);
        require_once dirname(__FILE__) . '/template.php';
    }
}
