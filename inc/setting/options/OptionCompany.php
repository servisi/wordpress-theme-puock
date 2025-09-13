<?php

namespace Publicus\Theme\setting\options;

class OptionCompany extends BaseOptionItem
{

    function get_fields(): array
    {
        return [
            'key' => 'company',
            'label' => __('企业布局', PUBLICUS),
            'icon' => 'dashicons-building',
            'fields' => [
                [
                    'id' => 'company_product_title',
                    'label' => __('产品介绍-大标题', PUBLICUS),
                    'sdt' => __('产品介绍', PUBLICUS),
                ],
                [
                    'id' => 'company_products',
                    'label' => __('产品列表', PUBLICUS),
                    'type' => 'dynamic-list',
                    'sdt' => [],
                    'draggable' => true,
                    'dynamicModel' => [
                        ['id' => 'title', 'label' => __('标题', PUBLICUS), 'std' => ''],
                        ['id' => 'img', 'label' => __('图片', PUBLICUS), 'std' => '', 'type' => 'img'],
                        ['id' => 'desc', 'label' => __('描述', PUBLICUS), 'std' => ''],
                        ['id' => 'link', 'label' => __('链接', PUBLICUS), 'std' => ''],
                    ],
                ],
                [
                    'id' => 'company_do_title',
                    'label' => __('做什么-大标题', PUBLICUS),
                    'sdt' => __('做什么', PUBLICUS),
                ],
                [
                    'id' => 'company_dos',
                    'label' => __('做什么-列表', PUBLICUS),
                    'type' => 'dynamic-list',
                    'sdt' => [],
                    'draggable' => true,
                    'dynamicModel' => [
                        ['id' => 'title', 'label' => __('标题', PUBLICUS), 'std' => ''],
                        ['id' => 'icon', 'label' => __('图标', PUBLICUS), 'std' => ''],
                        ['id' => 'desc', 'label' => __('描述', PUBLICUS), 'std' => ''],
                    ],
                ],
                [
                    'id' => 'company_do_img',
                    'label' => __('做什么-左侧展示图', PUBLICUS),
                    'type' => 'img',
                    'sdt' => '',
                ],
                [
                    'id' => 'company_news_open',
                    'label' => __('显示新闻', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => 'false',
                ],
                [
                    'id' => 'company_news_title',
                    'label' => __('新闻模块标题', PUBLICUS),
                    'sdt' => __('新闻动态', PUBLICUS),
                    'showRefId' => 'company_news_open',
                ],
                [
                    'id' => 'company_news_cid',
                    'label' => __('新闻分类目录', PUBLICUS),
                    'type' => 'select',
                    'sdt' => '',
                    'multiple' => true,
                    'showRefId' => 'company_news_open',
                    'options' => self::get_category(),
                ],
                [
                    'id' => 'company_news_max_num',
                    'label' => __('新闻显示数量', PUBLICUS),
                    'type' => 'number',
                    'sdt' => 4,
                    'showRefId' => 'company_news_open',
                ],
                [
                    'id' => 'company_show_2box',
                    'label' => __('企业两栏CMS分类', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => 'false',
                ],
                [
                    'id' => 'company_show_2box_id',
                    'label' => __('企业两栏CMS分类项', PUBLICUS),
                    'type' => 'select',
                    'sdt' => '',
                    'multiple' => true,
                    'showRefId' => 'company_show_2box',
                    'options' => self::get_category(),
                ],
                [
                    'id' => 'company_show_2box_num',
                    'label' => __('企业两栏CMS分类每栏显示数量', PUBLICUS),
                    'type' => 'number',
                    'sdt' => 6,
                    'showRefId' => 'company_show_2box',
                ],
            ],
        ];
    }
}
