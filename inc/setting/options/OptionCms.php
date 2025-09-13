<?php

namespace Publicus\Theme\setting\options;

class OptionCms extends BaseOptionItem{

    function get_fields(): array
    {
        return [
            'key' => 'cms',
            'label' => __('CMS布局', PUBLICUS),
            'icon'=>'czs-layers',
            'fields' => [
                [
                    'id' => 'cms_show_pagination',
                    'label' => __('显示分页', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => false,
                ],
                [
                    'id' => 'cms_show_new',
                    'label' => __('显示最新文章', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => true,
                ],
                [
                    'id' => 'cms_new_sort',
                    'label' => __('最新文章排序规则', PUBLICUS),
                    'type' => 'radio',
                    'options'=>[
                        ['label'=>__('发布时间', PUBLICUS),'value'=>'published'],
                        ['label'=>__('更新时间', PUBLICUS),'value'=>'updated'],
                    ],
                    'sdt' => 'published',
                ],
                [
                    'id' => 'cms_show_new_num',
                    'label' => __('最新文章数量', PUBLICUS),
                    'type' => 'number',
                    'sdt' => 6,
                    'showRefId' => 'cms_show_new',
                ],
                [
                    'id' => 'cms_show_2box',
                    'label' => __('显示CMS两栏布局', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => true,
                ],
                [
                    'id' => 'cms_show_2box_id',
                    'label' => __('CMS两栏布局分类ID', PUBLICUS),
                    'type' => 'select',
                    'sdt' => '',
                    'multiple' => true,
                    'showRefId' => 'cms_show_2box',
                    'options' => self::get_category(),
                ],
                [
                    'id' => 'cms_show_2box_num',
                    'label' => __('CMS两栏布局每栏数量', PUBLICUS),
                    'type' => 'number',
                    'sdt' => 6,
                    'showRefId' => 'cms_show_2box',
                ],
            ],
        ];
    }
}
