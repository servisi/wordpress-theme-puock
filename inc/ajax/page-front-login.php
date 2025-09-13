<?php


/**
 * @throws Exception
 */
function publicus_front_form_validate_code_check($type = '', $code = '')
{
    if (publicus_get_option('vd_type', 'img') === 'img') {
        if (!publicus_captcha_validate($type, $code)) {
        }
    } else {
        publicus_vd_gt_validate();
    }
}

function publicus_front_login_exec()
{
    if (is_string($data = publicus_get_req_data([
            'remember' => ['name' => '记住我', 'required' => false],
        ])) === true) {
        echo publicus_ajax_resp_error($data);
        wp_die();
    }
    try {
        publicus_front_form_validate_code_check('login', $data['vd']);
    } catch (Exception $e) {
        echo publicus_ajax_resp_error($e->getMessage());
        wp_die();
    }
    $try_open = publicus_is_checked('quick_login_try_max_open');
    $try_num = $try_open ? publicus_get_option('quick_login_try_max_num', 3) : 0;
    $try_ban_time = $try_open ? publicus_get_option('quick_login_try_max_ban_time', 10) : 0;
    if ($try_open) {
        $ip = publicus_get_client_ip();
        if (!empty(get_transient('publicus_login_ban_' . $ip))) {
            wp_die();
        }
    }
    $user = wp_signon([
        'user_login' => $data['username'],
        'user_password' => $data['password'],
        'remember' => $data['remember'] === 'on',
    ], is_ssl());
    if ($user instanceof WP_User) {
        wp_set_auth_cookie($user->ID, true, is_ssl());
        echo publicus_ajax_resp([
            'action' => 'reload',
    } else {
        if ($try_open) {
            $try = get_transient('publicus_login_try_' . $ip) ?? 0;
            $try++;
            if ($try >= $try_num) {
                set_transient('publicus_login_ban_' . $ip, 1, $try_ban_time * 60);
                wp_die();
            } else {
                set_transient('publicus_login_try_' . $ip, $try, $try_ban_time * 60);
            }
        }
    }
    wp_die();
}


function publicus_front_register_exec()
{
    if (is_string($data = publicus_get_req_data([
            'email' => ['email' => '邮箱', 'required' => true],
        ])) === true) {
        echo publicus_ajax_resp_error($data);
        wp_die();
    }
    if (strlen($data['username']) < 5 || strlen($data['username']) > 10) {
        wp_die();
    }
    if (strlen($data['password']) < 6 || strlen($data['password']) > 18) {
        wp_die();
    }
    if (!is_email($data['email'])) {
        echo publicus_ajax_resp_error('邮箱不合法');
        wp_die();
    }
    try {
        publicus_front_form_validate_code_check('register', $data['vd']);
    } catch (Exception $e) {
        echo publicus_ajax_resp_error($e->getMessage());
        wp_die();
    }
    $user_id = wp_create_user($data['username'], $data['password'], $data['email']);
    if ($user_id instanceof WP_Error) {
        echo publicus_ajax_resp_error($user_id->get_error_message());
    } else {
        wp_set_auth_cookie($user_id, true, is_ssl());
        echo publicus_ajax_resp([
            'action' => 'reload',
    }
    wp_die();
}

function publicus_front_forget_password_exec()
{
    if (is_string($data = publicus_get_req_data([
            'email' => ['email' => '邮箱', 'required' => true],
        ])) === true) {
        echo publicus_ajax_resp_error($data);
        wp_die();
    }
    if (strlen($data['password']) < 6 || strlen($data['password']) > 18) {
        wp_die();
    }
    if ($data['password'] !== $data['re-password']) {
        wp_die();
    }
    if (!is_email($data['email'])) {
        echo publicus_ajax_resp_error('邮箱不合法');
        wp_die();
    }
    try {
        publicus_front_form_validate_code_check('forget-password', $data['vd']);
    } catch (Exception $e) {
        echo publicus_ajax_resp_error($e->getMessage());
        wp_die();
    }
    $user = get_user_by('email', $data['email']);
    if (empty($user)) {
        wp_die();
    }
    $code = md5($data['email'] . wp_generate_password(20, false));
    set_transient('publicus_forget_password_' . $code, ['password' => $data['password'], 'email' => $data['email']], 60 * 5);
    $url = publicus_ajax_url('publicus_front_forget_password_reset_exec', [
        'code' => $code,
    ]);
    } else {
    }
    wp_die();
}

function publicus_front_forget_password_reset_exec()
{
    $code = $_REQUEST['code'] ?? '';
    if (empty($code)) {
    }
    $info = get_transient('publicus_forget_password_' . $code);
    if (empty($info)) {
    }
    $user = get_user_by('email', $info['email']);
    if (empty($user)) {
    }
    delete_transient('publicus_forget_password_' . $code);
    wp_set_password($info['password'], $user->ID);
}


if (publicus_is_checked('open_quick_login')) {
    if (!publicus_is_checked('only_quick_oauth')) {
        publicus_ajax_register('publicus_front_login_exec', 'publicus_front_login_exec', true);
        if (get_option('users_can_register') == 1) {
            publicus_ajax_register('publicus_front_register_exec', 'publicus_front_register_exec', true);
        }
        if (publicus_is_checked('quick_login_forget_password')) {
            publicus_ajax_register('publicus_front_forget_password_exec', 'publicus_front_forget_password_exec', true);
            publicus_ajax_register('publicus_front_forget_password_reset_exec', 'publicus_front_forget_password_reset_exec', true);
        }
    }
    publicus_ajax_register('publicus_font_login_page', 'publicus_front_login_page_callback', true);
}


function publicus_front_login_page_callback()
{
    $redirect = $_GET['redirect'] ?? get_edit_profile_url();
    $forget_password_url = publicus_ajax_url('publicus_font_login_page', ['redirect' => $redirect]);
    $open_register = get_option('users_can_register') == 1;
    $only_quick_oauth = publicus_is_checked('only_quick_oauth');
    $validate_type = publicus_get_option('vd_type', 'img');
    ?>
    <div class="min-width-modal">
        <?php
        if (!$only_quick_oauth):
            ?>

            <form id="front-login-form" action="<?php echo publicus_ajax_url('publicus_front_login_exec'); ?>" method="post"
                  class="ajax-form" data-validate="<?php echo $validate_type; ?>">
                <div class="mb15">
                    <input type="text" name="username" class="form-control form-control-sm" id="_front_login_username"
                           data-required
                </div>
                <div class="mb15">
                    <input type="password" name="password" class="form-control form-control-sm" data-required
                           id="_front_login_password"
                </div>
                <?php if ($validate_type === 'img'): ?>
                    <div class="mb15">
                        <div class="row flex-row justify-content-end">
                            <div class="col-8 col-sm-7 text-end pl15">
                                       class="form-control form-control-sm t-sm captcha-input" name="vd"
                                       autocomplete="off"
                                       id="_front_login_vd">
                            </div>
                            <div class="col-4 col-sm-5 pr15">
                                <img class="captcha lazy" data-src="<?php echo publicus_captcha_url('login', 100, 28) ?>"
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="mb15 form-check form-switch">
                    <input class="form-check-input" name="remember" type="checkbox" role="switch"
                           id="front-login-remember-me">
                    <label class="form-check-label" for="front-login-remember-me"> 记住我</label>
                </div>
                <div class="mb15 d-flex justify-content-center wh100">
                    <button class="btn btn-ssm btn-primary mr5" type="submit"><i class="fa fa-right-to-bracket"></i>
                    </button>
                </div>
                <div class="mb15 d-flex justify-content-between align-content-center fs12">
                    <?php if ($open_register): ?>
                        <a class="c-sub t-hover-primary toggle-el-show-hide" data-target="#front-register-form"
                           data-modal-title="注册"
                           data-self="#front-login-form" href="javascript:void(0)">还没有账号？立即注册</a>
                    <?php endif; ?>
                    <?php if (publicus_is_checked('quick_login_forget_password')): ?>
                        <a class="c-sub t-hover-primary toggle-el-show-hide" data-target="#front-forget-password-form"
                    <?php endif; ?>
                </div>
            </form>

            <?php if ($open_register): ?>
            <form id="front-register-form" action="<?php echo publicus_ajax_url('publicus_front_register_exec'); ?>" method="post"
                  class="d-none ajax-form" data-validate="<?php echo $validate_type; ?>">
                <div class="mb15">
                    <input type="text" name="username" class="form-control form-control-sm" data-required
                </div>
                <div class="mb15">
                    <label for="_front_register_email" class="form-label">邮箱</label>
                    <input type="email" name="email" class="form-control form-control-sm" data-required
                           id="_front_register_email" placeholder="请输入邮箱">
                </div>
                <div class="mb15">
                    <input type="password" name="password" class="form-control form-control-sm" data-required
                </div>
                <?php if ($validate_type === 'img'): ?>
                    <div class="mb15">
                        <div class="row flex-row justify-content-end">
                            <div class="col-8 col-sm-7 text-end pl15">
                                       class="form-control form-control-sm t-sm" name="vd"
                                       autocomplete="off"
                                       id="_front_register_vd">
                            </div>
                            <div class="col-4 col-sm-5 pr15">
                                <img class="captcha lazy"
                                     data-src="<?php echo publicus_captcha_url('register', 100, 28) ?>"
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="mb15 d-flex justify-content-center wh100">
                    <button class="btn btn-ssm btn-primary mr5" type="submit"><i class="fa fa-right-to-bracket"></i>
                        立即注册
                    </button>
                </div>
                <div class="mb15 d-flex justify-content-end fs12">
                    <a class="c-sub t-hover-primary toggle-el-show-hide" href="javascript:void(0)"
                       data-self="#front-register-form" data-target="#front-login-form"
                </div>
            </form>
        <?php endif; ?>
            <?php if (publicus_is_checked('quick_login_forget_password')): ?>
            <form id="front-forget-password-form" action="<?php echo publicus_ajax_url('publicus_front_forget_password_exec'); ?>"
                  method="post" class="d-none ajax-form" data-validate="<?php echo $validate_type; ?>">
                <div class="mb15">
                    <label for="_front_forget_password_email" class="form-label">邮箱</label>
                    <input type="email" name="email" class="form-control form-control-sm" data-required
                           id="_front_forget_password_email" placeholder="请输入邮箱">
                </div>
                <div class="mb15">
                    <input type="password" name="password" class="form-control form-control-sm" data-required
                </div>
                <div class="mb15">
                    <input type="password" name="re-password" class="form-control form-control-sm" data-required
                </div>
                <?php if ($validate_type === 'img'): ?>
                    <div class="mb15">
                        <div class="row flex-row justify-content-end">
                            <div class="col-8 col-sm-7 text-end pl15">
                                       class="form-control form-control-sm t-sm" name="vd"
                                       autocomplete="off"
                                       id="_front_forget_password_vd">
                            </div>
                            <div class="col-4 col-sm-5 pr15">
                                <img class="captcha lazy"
                                     data-src="<?php echo publicus_captcha_url('forget-password', 100, 28) ?>"
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="mb15 d-flex justify-content-center wh100">
                    <button class="btn btn-ssm btn-primary mr5" type="submit"><i class="fa fa-paper-plane"></i> 发送邮件
                    </button>
                </div>
                <div class="mb15 d-flex justify-content-end fs12">
                    <a class="c-sub t-hover-primary toggle-el-show-hide" href="javascript:void(0)"
                       data-self="#front-forget-password-form" data-target="#front-login-form"
                </div>
            </form>
        <?php endif;endif; ?>

        <div class="mb15">
            <?php publicus_oauth_quick_buttons(true, $redirect) ?>
        </div>

    </div>
    <?php

    wp_die();
}
