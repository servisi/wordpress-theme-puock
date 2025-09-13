<?php

function publicus_ajax_url($action,$args=[]){
    $url = admin_url('admin-ajax.php?action='.$action);
    if(!empty($args)){
        $url .= '&'.http_build_query($args);
    }
    return $url;
}

/**
 * @param $name
 * @param $callback callable
 * @param $public
 * @return void
 */
function publicus_ajax_register($name, $callback, $public = false)
{
    add_action('wp_ajax_' . $name, $callback);
    if ($public) {
        add_action('wp_ajax_nopriv_' . $name, $callback);
    }
}

function publicus_ajax_get_req_body()
{
    $body = @file_get_contents('php://input');
    return json_decode($body, true);
}

function publicus_ajax_result_page($success = true, $info = '', $from_redirect = '')
{
    if ($success && !empty($from_redirect)) {
        wp_redirect($from_redirect);
    } else {
        publicus_session_call(function () use ($info) {
            $_SESSION['error_info'] = $info;
        });
        wp_redirect(PUBLICUS_ABS_URI . '/error.php');
        wp_die();
    }
}

function publicus_ajax_get_theme_options()
{
    if (current_user_can('edit_theme_options')) {
        wp_send_json_success([
            'settings' => get_option(PUBLICUS_OPT),
        ]);
    } else {
        wp_send_json_error('权限不足');
    }
}

publicus_ajax_register('get_theme_options', 'publicus_ajax_get_theme_options');

function publicus_ajax_update_theme_options()
{
    if (current_user_can('edit_theme_options')) {
        $body = publicus_ajax_get_req_body();
        update_option(PUBLICUS_OPT, $body);
        do_action('publicus_option_updated', $body);
        flush_rewrite_rules();
        wp_send_json_success();
    } else {
        wp_send_json_error('权限不足');
    }
}

publicus_ajax_register('update_theme_options', 'publicus_ajax_update_theme_options');
