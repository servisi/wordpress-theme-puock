<?php

namespace Publicus\Theme\setting\options;

class OptionCache extends BaseOptionItem{

    function get_fields(): array
    {
        return [
            'key' => 'cache',
            'label' => __('缓存与性能', PUBLICUS),
            'icon'=>'dashicons-superhero',
            'fields' => [
                [
                    'id' => 'cache_expire_second',
                    'label' => __('缓存过期秒数', PUBLICUS),
                    'type' => 'number',
                    'sdt' => 0,
                    'tips'=>__('0为不过期', PUBLICUS),
                ],
            ],
        ];
    }
}
