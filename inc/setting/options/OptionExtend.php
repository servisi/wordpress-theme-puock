<?php

namespace Publicus\Theme\setting\options;

class OptionExtend extends BaseOptionItem
{

    function get_fields(): array
    {
        return [
            'key' => 'extend',
            'label' => __('扩展及兼容', PUBLICUS),
            'icon' => 'dashicons-admin-plugins',
            'fields' => [
                [
                    'id' => 'office_mp_support',
                    'label' => __('Puock官方小程序支持', PUBLICUS),
                    'type' => 'switch',
                    'value' => defined('PUBLICUS_MP_VERSION'),
                    'tips' => __('Puock官方小程序支持，此选项安装小程序插件后会自动开启，如需关闭请在小程序插件中关闭', PUBLICUS) . " （<a target='_blank' href='https://licoy.cn/puock-mp.html'>" . __('了解小程序?', PUBLICUS) . "</a>）",
                    'disabled' => true,
                    'badge' => ['value' => '🔥 ' . __('热门 & 推荐', PUBLICUS)]
                ],
                [
                    'id' => 'user_center',
                    'label' => __('用户中心', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => false,
                    'badge' => ['value' => 'New'],
                    'tips' => __('使用前请先配置wordpress伪静态规则：<code>try_files $uri $uri/ /index.php?$args</code>', PUBLICUS)
                ],
                [
                    'id' => 'strawberry_icon',
                    'label' => __('Strawberry图标库', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => false,
                    'tips' => __('开启之后会在前台加载Strawberry图标库支持', PUBLICUS)
                ],
                [
                    'id' => 'dplayer',
                    'label' => 'DPlayer' . ' ' . __('支持', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => false,
                    'tips' => __('开启之后会在前台加载DPlayer支持', PUBLICUS)
                ],
            ],
        ];
    }
}
