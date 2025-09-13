<?php

namespace Publicus\Theme\setting\options;

class OptionDebug extends BaseOptionItem{

    function get_fields(): array
    {
        return [
            'key' => 'debug',
            'label' =>  __('调试与开发' , PUBLICUS),
            'icon'=>'dashicons-code-standards',
            'fields' => [
                [
                    'id' => 'debug_sql_count',
                    'label' => __('显示SQL查询统计', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => 'false',
                    'tips'=>__('此数据会显示在<code>console</code>，需<code>F12</code>打开控制台查看', PUBLICUS),
                ],
                [
                    'id' => 'debug_sql_detail',
                    'label' => __('显示SQL查询详情', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => 'false',
                    'tips'=>__("此数据会显示在<code>console</code>，需<code>F12</code>打开控制台查看，需要在<code>wp-config.php</code>中加入<code>define('SAVEQUERIES', true);</code>", PUBLICUS)
                ],
            ],
        ];
    }
}
