<?php

namespace Publicus\Theme\setting\options;

class OptionValidate extends BaseOptionItem
{

    function get_fields(): array
    {
        return [
            'key' => 'validate',
            'icon' => 'dashicons-shield',
            'fields' => [
                [
                    'id' => 'vd_type',
                    'type' => 'radio',
                    'sdt' => 'img',
                    'radioType' => 'button',
                    'options' => [
                        [
                            'value' => 'img',
                        ],
                        [
                            'value' => 'gt',
                        ],
                    ],
                ],
                [
                    'id' => 'vd_comment',
                    'type' => 'switch',
                    'sdt' => false,
                ],
                [
                    'id' => '-',
                    'type' => 'panel',
                    'open' => true,
                    'children' => [
                        [
                            'id' => 'vd_gt_id',
                            'sdt' => ''
                        ],
                        [
                            'id' => 'vd_gt_key',
                            'sdt' => ''
                        ]
                    ]
                ],
                [
                    'id' => 'vd_comment_need_chinese',
                    'label' => __('评论内容中必须含有中文字符', PUBLICUS),
                    'type' => 'switch',
                    'tips' => __('开启后，评论中必须含有至少1个中文字符，否则将会被拦截', PUBLICUS),
                    'sdt' => false,
                ],
                [
                    'id' => 'vd_kwd_access_reject',
                    'label' => __('恶意统计关键字访问屏蔽', PUBLICUS),
                    'type' => 'switch',
                    'tips' => __('开启后，将会使含有指定关键字的query参数请求得到403拒绝访问，防止站点统计的恶意刷量', PUBLICUS),
                    'sdt' => false,
                ],
                [
                    'id' => 'vd_kwd_access_reject_list',
                    'label' => __('恶意统计关键字访问屏蔽参数', PUBLICUS),
                    'tips' => __('多个之间使用半角<code>,</code>进行分隔', PUBLICUS),
                    'sdt' => 'wd,str',
                ],
            ],
        ];
    }
}
