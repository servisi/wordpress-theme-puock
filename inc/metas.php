<?php

function publicus_metas_get_async_view_id()
{
    if (publicus_is_checked('async_view') && (is_page() || is_single())) {
        return get_the_ID();
    }
    return null;
}

$headMetas = [
    'home' => home_url(),
    'vd_comment' => publicus_is_checked('vd_comment'),
    'vd_gt_id' => publicus_get_option('vd_gt_id'),
    'vd_type' => publicus_get_option('vd_type', 'img'),
    'use_post_menu' => publicus_is_checked('use_post_menu'),
    'is_single' => is_single(),
    'is_pjax' => publicus_is_checked('page_ajax_load'),
    'main_lazy_img' => publicus_is_checked('basic_img_lazy_z'),
    'link_blank_open' => publicus_is_checked('link_blank_content'),
    'async_view_id' => publicus_metas_get_async_view_id(),
    'mode_switch' => publicus_is_checked('theme_mode_s'),
    'off_img_viewer'=>publicus_is_checked('off_img_viewer'),
    'off_code_highlighting'=>publicus_is_checked('off_code_highlighting'),
    'mobile_sidebar_enable' => publicus_is_checked('mobile_sidebar_enable'),
];
if($headMetas['async_view_id']){
    $headMetas['async_view_generate_time'] = time();
}
?>
<script data-instant>var puock_metas =<?php echo json_encode($headMetas) ?>;</script>
