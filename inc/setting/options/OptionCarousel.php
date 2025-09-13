<?php

namespace Publicus\Theme\setting\options;

class OptionCarousel extends BaseOptionItem
{

    public static function getCarouselIndexArgs($encode = true)
    {
        $args = [
            'navigation' => [
                'nextEl' => '.index-banner-swiper .swiper-button-next',
                'prevEl' => '.index-banner-swiper .swiper-button-prev',
            ],
            'pagination' => [
                'el' => '.index-banner-swiper .swiper-pagination',
                'clickable' => true,
                'dynamicBullets' => true,
            ],
        ];
        if (!empty(publicus_get_option('index_carousel_switch_effect'))) {
            $args['effect'] = publicus_get_option('index_carousel_switch_effect');
        }
        if (publicus_is_checked('index_carousel_mousewheel')) {
            $args['mousewheel'] = ['invert' => true];
        }
        $speed = publicus_get_option('index_carousel_autoplay_speed');
        if ($speed && $speed > 0) {
            $args['autoplay'] = ['delay' => $speed, 'disableOnInteraction' => false];
        }
        if (publicus_is_checked('index_carousel_loop')) {
            $args['loop'] = true;
        }
        return $encode ? json_encode($args) : $args;
    }

    function get_fields(): array
    {
        return [
            'key' => 'carousel',
            'label' => __('幻灯与公告', PUBLICUS),
            'icon' => 'dashicons-format-gallery',
            'fields' => [
                [
                    'id' => '-',
                    'type' => 'panel',
                    'label' => __('Ana Sayfa幻灯片', PUBLICUS),
                    'open' => publicus_is_checked('index_carousel'),
                    'children' => [
                        [
                            'id' => '-',
                            'type' => 'info',
                            'tips' => __('说明：幻灯片尺寸建议统一为2:1的比例，例如800*400，另外所有的幻灯片尺寸必须完全一致，否则会出现高度不一的情况！', PUBLICUS),
                        ],
                        [
                            'id' => 'index_carousel',
                            'label' => __('启用', PUBLICUS),
                            'type' => 'switch',
                            'sdt' => true,
                        ],
                        [
                            'id' => 'index_carousel_mousewheel',
                            'label' => __('鼠标滚轮切换', PUBLICUS),
                            'type' => 'switch',
                            'sdt' => true,
                        ],
                        [
                            'id' => 'index_carousel_hide_title',
                            'label' => __('隐藏标题', PUBLICUS),
                            'type' => 'switch',
                            'sdt' => false,
                        ],
                        [
                            'id' => 'index_carousel_loop',
                            'label' => __('循环播放', PUBLICUS),
                            'type' => 'switch',
                            'sdt' => true,
                        ],
                        [
                            'id' => 'index_carousel_autoplay_speed',
                            'label' => __('自动播放速度（毫秒）', PUBLICUS),
                            'tips' => __('0为不自动播放', PUBLICUS),
                            'type' => 'number',
                            'sdt' => 3000,
                        ],
                        [
                            'id' => 'index_carousel_switch_effect',
                            'label' => __('切换效果', PUBLICUS),
                            'type' => 'select',
                            'sdt' => '',
                            'options' => [
                                ['label' => __('默认', PUBLICUS), 'value' => ''],
                                ['label' => __('淡入淡出', PUBLICUS), 'value' => 'fade'],
                                ['label' => __('立方体', PUBLICUS), 'value' => 'cube'],
                                ['label' => __('快速翻转', PUBLICUS), 'value' => 'flip'],
                                ['label' => __('覆盖流', PUBLICUS), 'value' => 'coverflow'],
                                ['label' => __('卡片', PUBLICUS), 'value' => 'cards']
                            ]
                        ],
                        [
                            'id' => 'index_carousel_list',
                            'label' => __('幻灯片列表', PUBLICUS),
                            'type' => 'dynamic-list',
                            'sdt' => [],
                            'draggable' => true,
                            'dynamicModel' => [
                                ['id' => 'title', 'label' => __('幻灯标题', PUBLICUS), 'std' => ''],
                                ['id' => 'img', 'label' => __('幻灯图片', PUBLICUS), 'std' => '', 'type' => 'img', 'tips' => __('建议尺寸2:1，所有图片大小必须一致', PUBLICUS)],
                                ['id' => 'link', 'label' => __('指向链接', PUBLICUS), 'std' => ''],
                                ['id' => 'blank', 'label' => __('新标签打开', PUBLICUS), 'std' => false, 'type' => 'switch'],
                                ['id' => 'hide', 'label' => __('隐藏', PUBLICUS), 'type' => 'switch', 'sdt' => false, 'tips' => __('隐藏后将不会显示', PUBLICUS)],
                            ],
                        ],
                    ]
                ],
                [
                    'id' => '-',
                    'type' => 'panel',
                    'label' => __('全局公告', PUBLICUS),
                    'open' => publicus_is_checked('global_notice'),
                    'children' => [
                        [
                            'id' => 'global_notice',
                            'label' => __('启用', PUBLICUS),
                            'type' => 'switch',
                            'sdt' => false,
                        ],
                        [
                            'id' => 'global_notice_autoplay_speed',
                            'label' => __('自动播放速度（毫秒）', PUBLICUS),
                            'tips' => __('0为不自动播放', PUBLICUS),
                            'type' => 'number',
                            'sdt' => 3000,
                        ],
                        [
                            'id' => 'global_notice_list',
                            'label' => __('公告列表', PUBLICUS),
                            'type' => 'dynamic-list',
                            'sdt' => [],
                            'draggable' => true,
                            'dynamicModel' => [
                                ['id' => 'title', 'label' => __('公告标题(支持HTML)', PUBLICUS), 'type' => 'textarea', 'std' => ''],
                                ['id' => 'link', 'label' => __('指向链接(可空)', PUBLICUS), 'std' => ''],
                                ['id' => 'icon', 'label' => __('图标class(可空)', PUBLICUS), 'std' => ''],
                                ['id' => 'hide', 'label' => __('隐藏', PUBLICUS), 'type' => 'switch', 'sdt' => false, 'tips' => __('隐藏后将不会显示', PUBLICUS)],
                            ],
                        ],
                    ]
                ]
            ],
        ];
    }
}
