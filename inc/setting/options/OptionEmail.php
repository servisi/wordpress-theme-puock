<?php

namespace Publicus\Theme\setting\options;

class OptionEmail extends BaseOptionItem{

    function get_fields(): array
    {
        return [
            'key' => 'email',
            'label' => __('SMTP邮件', PUBLICUS),
            'icon'=>'dashicons-email-alt',
            'fields' => [
                [
                    'id' => 'smtp_open',
                    'label' => __('开启SMTP', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => 'false',
                    'tips'=>__('开启会覆盖WordPress默认配置', PUBLICUS),
                ],
                [
                    'id' => 'smtp_ssl',
                    'label' => __('SMTP加密', PUBLICUS),
                    'type' => 'switch',
                    'sdt' => 'false',
                    'showRefId' => 'smtp_open',
                ],
                [
                    'id' => 'smtp_form',
                    'label' => __('发件人邮箱', PUBLICUS),
                    'sdt' => '',
                    'showRefId' => 'smtp_open',
                ],
                [
                    'id' => 'smtp_host',
                    'label' => __('SMTP服务器', PUBLICUS),
                    'sdt' => '',
                    'showRefId' => 'smtp_open',
                    'tips'=>'如163邮箱的为：smtp.163.com'
                ],
                [
                    'id' => 'smtp_port',
                    'label' => __('SMTP端口', PUBLICUS),
                    'sdt' => '',
                    'showRefId' => 'smtp_open',
                ],
                [
                    'id' => 'smtp_u',
                    'label' => __('SMTP账户', PUBLICUS),
                    'sdt' => '',
                    'showRefId' => 'smtp_open',
                ],
                [
                    'id' => 'smtp_p',
                    'label' => __('SMTP密码', PUBLICUS),
                    'sdt' => '',
                    'showRefId' => 'smtp_open',
                    'tips'=>__('一般非邮箱账号直接密码，而是对应的平台的POP3/SMTP授权码', PUBLICUS),
                ],
            ],
        ];
    }
}
