<?php

namespace Publicus\Theme\setting\options;

class OptionAuth extends BaseOptionItem
{

    function get_fields(): array
    {
        return [
            'key' => 'auth',
            'icon' => 'czs-qq',
            'fields' => [
                [
                    'id' => '-',
                    'type' => 'panel',
                    'open' => true,
                    'children' => [
                        [
                            'id' => 'open_quick_login',
                            'type' => 'switch',
                            'sdt' => false,
                        ],
                        [
                            'id' => 'only_quick_oauth',
                            'type' => 'switch',
                            'sdt' => false,
                        ],
                        [
                            'id' => 'quick_login_try_max_open',
                            'type' => 'switch',
                            'sdt' => false,
                        ],
                        [
                            'id' => 'quick_login_try_max_num',
                            'type' => 'number',
                            'sdt' => 3,
                        ],
                        [
                            'id' => 'quick_login_try_max_ban_time',
                            'type' => 'number',
                            'sdt' => 10,
                        ],
                        [
                            'id' => 'quick_login_forget_password',
                            'type' => 'switch',
                            'sdt' => false,
                        ],
                    ]
                ],
                [
                    'id' => '-',
                    'type' => 'panel',
                    'open' => publicus_is_checked('login_protection'),
                    'children' => [
                        [
                            'id' => 'login_protection',
                            'type' => 'switch',
                            'sdt' => 'false',
                            'tips' => 'func:(function(args){
                            const link = `' . home_url() . '/wp-login.php?${args.data.lp_user}=${args.data.lp_pass}`
                            return `<div>启用后则用 <a href="${link}" target="_blank">${link}</a> 的方式访问后台入口</div>`
                        })(args)'
                        ],
                        [
                            'id' => 'lp_user',
                            'sdt' => 'admin',
                            'showRefId' => 'login_protection',
                        ],
                        [
                            'id' => 'lp_pass',
                            'sdt' => 'admin',
                            'showRefId' => 'login_protection',
                        ],
                    ]
                ],
                [
                    'id' => '-',
                    'type' => 'info',
                    'infoType' => 'info',
                    'tips' => '通用回调地址（callback url）为: <code>' . home_url() . '/wp-admin/admin-ajax.php</code>'
                ],
                [
                    'id' => 'oauth_close_register',
                    'type' => 'switch',
                    'std' => false
                ],
                [
                    'id' => '-',
                    'type' => 'panel',
                    'open' => publicus_is_checked('oauth_qq'),
                    'tips' => '<a target="_blank" href="https://wiki.connect.qq.com/%E7%BD%91%E7%AB%99%E6%8E%A5%E5%85%A5%E6%B5%81%E7%A8%8B">' . __('申请步骤及说明', PUBLICUS) . '</a>',
                    'children' => [
                        [
                            'id' => 'oauth_qq',
                            'type' => 'switch',
                            'sdt' => 'false',
                        ],
                        [
                            'id' => 'oauth_qq_id',
                            'label' => __('QQ互联', PUBLICUS) . ' APP ID',
                            'sdt' => '',
                            'showRefId' => 'oauth_qq',
                        ],
                        [
                            'id' => 'oauth_qq_key',
                            'label' => __('QQ互联', PUBLICUS) . ' APP KEY',
                            'sdt' => '',
                            'showRefId' => 'oauth_qq',
                        ],
                    ]
                ],
                [
                    'id' => '-',
                    'type' => 'panel',
                    'open' => publicus_is_checked('oauth_github'),
                    'tips' => '<a target="_blank" href="https://www.ruanyifeng.com/blog/2019/04/github-oauth.html">' . __('申请步骤及说明', PUBLICUS) . '</a>',
                    'children' => [
                        [
                            'id' => 'oauth_github',
                            'type' => 'switch',
                            'sdt' => 'false',
                        ],
                        [
                            'id' => 'oauth_github_id',
                            'label' => 'Github Client ID',
                            'sdt' => '',
                            'showRefId' => 'oauth_github',
                        ],
                        [
                            'id' => 'oauth_github_secret',
                            'label' => 'Github Client Secret',
                            'sdt' => '',
                            'showRefId' => 'oauth_github',
                        ],
                    ]
                ],
                [
                    'id' => '-',
                    'type' => 'panel',
                    'open' => publicus_is_checked('oauth_weibo'),
                    'tips' => '<a target="_blank" href="https://open.weibo.com/wiki/%E7%BD%91%E7%AB%99%E6%8E%A5%E5%85%A5%E4%BB%8B%E7%BB%8D">' . __('申请步骤及说明', PUBLICUS) . '</a>',
                    'children' => [
                        [
                            'id' => 'oauth_weibo',
                            'type' => 'switch',
                            'sdt' => 'false',
                        ],
                        [
                            'id' => 'oauth_weibo_key',
                            'label' => __('微博', PUBLICUS) . ' App Key',
                            'sdt' => '',
                            'showRefId' => 'oauth_weibo',
                        ],
                        [
                            'id' => 'oauth_weibo_secret',
                            'label' => __('微博', PUBLICUS) . ' App Secret',
                            'sdt' => '',
                            'showRefId' => 'oauth_weibo',
                        ],
                    ]
                ],
                [
                    'id' => '-',
                    'type' => 'panel',
                    'open' => publicus_is_checked('oauth_gitee'),
                    'tips' => '<a target="_blank" href="https://gitee.com/api/v5/oauth_doc#/list-item-3">' . __('申请步骤及说明', PUBLICUS) . '</a>',
                    'children' => [
                        [
                            'id' => 'oauth_gitee',
                            'type' => 'switch',
                            'sdt' => 'false',
                        ],
                        [
                            'id' => 'oauth_gitee_id',
                            'label' => 'Gitee Client ID',
                            'sdt' => '',
                            'showRefId' => 'oauth_gitee',
                        ],
                        [
                            'id' => 'oauth_gitee_secret',
                            'label' => 'Gitee Client Secret',
                            'sdt' => '',
                            'showRefId' => 'oauth_gitee',
                        ],
                    ]
                ],
                [
                    'id' => '-',
                    'type' => 'panel',
                    'open' => publicus_is_checked('oauth_linuxdo'),
                    'tips' => '<a target="_blank" href="https://connect.linux.do">' . __('申请步骤及说明', PUBLICUS) . '</a>',
                    'children' => [
                        [
                            'id' => '-',
                            'type' => 'info',
                            'infoType' => 'info',
                            'tips' => '通用回调地址（callback url）为: <code>' . PUBLICUS_ABS_URI . '/inc/oauth/callback/linuxdo.php</code>'
                        ],
                        [
                            'id' => 'oauth_linuxdo',
                            'type' => 'switch',
                            'sdt' => 'false',
                        ],
                        [
                            'id' => 'oauth_linuxdo_id',
                            'label' => 'LinuxDO Client ID',
                            'sdt' => '',
                            'showRefId' => 'oauth_linuxdo',
                        ],
                        [
                            'id' => 'oauth_linuxdo_secret',
                            'label' => 'LinuxDO Client Secret',
                            'sdt' => '',
                            'showRefId' => 'oauth_linuxdo',
                        ],
                    ]
                ],
            ],
        ];
    }
}
