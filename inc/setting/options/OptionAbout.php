<?php

namespace Publicus\Theme\setting\options;

class OptionAbout extends BaseOptionItem
{

    function get_fields(): array
    {
        return [
            'key' => 'about',
            'label' => __('关于及指引', PUBLICUS),
            'icon' => 'czs-label-info',
            'fields' => [
                [
                    'id' => 'about_readme',
                    'type' => 'md',
                    'tips' => file_get_contents(PUBLICUS_ABS_DIR.'/README.md'),
                    'md'=>[
                        'baseUrl'=>'https://raw.githubusercontent.com/publicus-agency/wordpress-theme-publicus/main/',
                    ]
                ]
            ]
        ];
    }
}
