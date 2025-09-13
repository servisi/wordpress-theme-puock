<?php

namespace Publicus\Theme\setting\options;

class OptionGlobal extends BaseOptionItem
{

    function get_fields(): array
    {
        return [
            'key' => 'global',
            'label' => __('全局设置', PUBLICUS),
            'icon' => 'dashicons-admin-site',
            'fields' => [
                [
                    'id' => 'index_mode',
                    'label' => __('Ana Sayfa布局', PUBLICUS),
                    'type' => 'radio',
                    'sdt' => 'blog',
                    'radioType' => 'button',
                    'options' => [
                        [
                            'value' => 'blog',
                            'label' => __('博客风格', PUBLICUS),
                        ],
                        [
                            'value' => 'cms',
                            'label' => __('CMS风格', PUBLICUS),
                        ],
                        [
                            'value' => 'company',
                            'label' => __('企业风格', PUBLICUS),
                        ],
                    ],
                ],
                [
                    'id' => 'post_style',
                    'label' => __('文章风格', PUBLICUS),
                    'type' => 'radio',
                    'sdt' => 'list',
                    'radioType' => 'button',
                    'options' => [
                        [
                            'value' => 'list',
                            'label' => __('列表风格', PUBLICUS),
                        ],
                        [
                            'value' => 'card',
                            'label' => __('卡片风格', PUBLICUS),
                        ],
                    ],
                ],
                [
                    'id' => 'theme_mode',
                    'label' => __('主题模式', PUBLICUS),
                    'type' => 'radio',
                    'sdt' => 'light',
                    'radioType' => 'button',
                    'options' => [
                        [
                            'value' => 'light',
                            'label' => __('日光模式', PUBLICUS),
                        ],
                        [
                            'value' => 'dark',
                            'label' => __('暗黑模式', PUBLICUS),
                        ],
                    ],
                ],
                [
                    'id' => 'theme_mode_s',
                    'label' => __('允许切换主题模式', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => true,
                ],
                [
                    'id' => 'nav_blur',
                    'label' => __('导航栏毛玻璃效果', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => 'false',
                ],
                [
                    'id' => 'html_page_permalink',
                    'label' => __('页面使用.html后缀', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => 'false',
                    'tips' => __('更改后需要重新保存<strong>固定链接</strong>', PUBLICUS),
                ],
                [
                    'id' => 'chinese_format',
                    'label' => __('开启中文格式化（文案排版）', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => 'false',
                    'tips' => __('参考', PUBLICUS) . "：<a href='https://github.com/sparanoid/chinese-copywriting-guidelines' target='_blank'>https://github.com/sparanoid/chinese-copywriting-guidelines</a>"
                ],
                [
                    'id' => 'on_txt_logo',
                    'label' => __('使用文字LOGO', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => 'false',
                ],
                [
                    'id' => 'light_logo',
                    'label' => __('日光模式LOGO', PUBLICUS),
                    'type' => 'img',
                    'sdt' => '',
                    'tips' => __('若不上传则显示文字LOGO，比例：500*125，请尽量选择png无底色图片', PUBLICUS),
                ],
                [
                    'id' => 'dark_logo',
                    'label' => __('暗黑模式LOGO', PUBLICUS),
                    'type' => 'img',
                    'sdt' => '',
                    'tips' => __('若不上传则显示文字LOGO，比例：500*125，请尽量选择png无底色图片', PUBLICUS),
                ],
                [
                    'id' => 'logo_loop_light',
                    'label' => __('LOGO扫光动画', PUBLICUS),
                    'type' => 'switch',
                    'badge' => ['value' => 'New'],
                    'sdt' => false,
                ],
                [
                    'id' => 'favicon',
                    'label' => __('网站图标', PUBLICUS),
                    'type' => 'img',
                    'sdt' => '',
                    'tips' => __('比例：32*32，请尽量选择png无底色图片', PUBLICUS),
                ],
                [
                    'id' => 'stop5x_editor',
                    'label' => __('禁用Gutenberg编辑器', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => 'false',
                ],
                [
                    'id' => 'use_widgets_block',
                    'label' => __('使用区块小工具', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => 'false',
                ],
                [
                    'id' => 'upload_webp',
                    'label' => __('允许上传webp', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => 'false',
                ],
                [
                    'id' => 'hide_post_views',
                    'label' => __('隐藏文章浏览量', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => 'false',
                ],
                [
                    'id' => 'close_post_comment',
                    'label' => __('关闭全站评论功能', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => 'false',
                ],
                [
                    'id' => 'hide_footer_wp_t',
                    'label' => __('隐藏底部<code>感谢使用WordPress进行创作</code>和左上角标识', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => 'false',
                ],
                [
                    'id' => 'grey',
                    'label' => __('全站变灰', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => 'false',
                ],
                [
                    'id' => 'disable_not_admin_user_profile',
                    'type' => 'switch',
                    'badge' => ['value' => 'New'],
                    'sdt' => false,
                ],
                [
                    'id' => 'compress_html',
                    'label' => __('将HTML压缩成一行', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => 'false',
                ],
                [
                    'id' => 'hide_global_sidebar',
                    'label' => __('关闭全局Kenar Çubuğu显示', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => 'false',
                ],
                [
                    'id' => 'close_rest_api',
                    'label' => __('禁止使用', PUBLICUS) . ' REST API',
                    'tips' => __('开启后将不能使用相关功能，如果使用了<b>小程序</b>等功能此选项应不要开启，
                                另外开启后可能导致古腾堡编辑器出现通信异常问题，建议非必要不开启此选项', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => 'false',
                ],
                [
                    'id' => 'close_xmlrpc',
                    'label' => __('禁止使用', PUBLICUS) . ' XML-RPC',
                    'type' => 'switch',
                    'sdt' => 'false',
                ],
                [
                    'id' => 'thumbnail_rewrite_open',
                    'label' => __('缩略图伪静态', PUBLICUS),
                    'type' => 'switch',
                    'badge' => ['value' => 'New'],
                    'sdt' => false,
                    'tips' => "⚠️".__('若开启此选项，请自行手动在Nginx配置中添加伪静态规则', PUBLICUS)."：<code>rewrite ^/timthumb/w_([0-9]+)/h_([0-9]+)/q_([0-9]+)/zc_([0-9])/a_([a-z]+)/([0-9A-Za-z_\-]+)\.([0-9a-z]+)$ /wp-content/themes/" . get_template() . "/timthumb.php?w=$1&h=$2&q=$3&zc=$4&a=$5&src=$6;</code>"
                ],
                [
                    'id' => 'thumbnail_allows',
                    'label' => __('缩略图白名单', PUBLICUS),
                    'type' => 'textarea',
                    'sdt' => '',
                    'tips' => __("<strong>若使用了其他外链图片须在此处添加外链域名以允许</strong>：一行一个，不要带 <code>http://</code> 或 <code>https://</code> 协议头，例如：<code>blog.example.com</code>", PUBLICUS)
                ],
            ],
        ];
    }
}
