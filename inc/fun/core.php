<?php


use Publicus\Theme\classes\PuockClassLoad;

add_action('after_setup_theme', 'publicus_theme_setup');
function publicus_theme_setup()
{
    load_theme_textdomain(PUBLICUS, PUBLICUS_ABS_DIR . '/languages');
}


function publicus_ajax_resp($data = null, $msg = '', $code = 0)
{
    header('Content-Type: application/json; charset=utf-8');
    return json_encode(array('data' => $data, 'msg' => $msg, 'code' => $code));
}

function publicus_ajax_resp_error($msg = 'fail', $data = null)
{
    return publicus_ajax_resp($data, $msg, -1);
}

require_once PUBLICUS_ABS_DIR . '/inc/fun/cache.php';
require_once PUBLICUS_ABS_DIR . '/inc/setting/index.php';
require_once PUBLICUS_ABS_DIR . '/inc/ext/init.php';
require_once PUBLICUS_ABS_DIR . '/inc/fun/ajax.php';
require_once PUBLICUS_ABS_DIR . '/inc/oauth/oauth.php';
require_once PUBLICUS_ABS_DIR . '/inc/fun/security.php';
require_once PUBLICUS_ABS_DIR . '/inc/fun/comment-ajax.php';
require_once PUBLICUS_ABS_DIR . '/inc/fun/widget.php';
require_once PUBLICUS_ABS_DIR . '/inc/init.php';
require_once PUBLICUS_ABS_DIR . '/inc/category-seo.php';
require_once PUBLICUS_ABS_DIR . '/inc/fun/comment.php';
require_once PUBLICUS_ABS_DIR . '/inc/fun/short-code.php';
require_once PUBLICUS_ABS_DIR . '/inc/fun/opt.php';
require_once PUBLICUS_ABS_DIR . '/inc/fun/post-meta.php';
require_once PUBLICUS_ABS_DIR . '/inc/fun/sidebar.php';
require_once PUBLICUS_ABS_DIR . '/inc/fun/post-tags.php';
require_once PUBLICUS_ABS_DIR . '/inc/fun/comment-notify.php';
require_once PUBLICUS_ABS_DIR . '/inc/user-agent-parse.php';
require_once PUBLICUS_ABS_DIR . '/inc/phpqrcode.php';
require_once PUBLICUS_ABS_DIR . '/inc/ajax/index.php';
if (publicus_is_checked('no_category')) {
    require_once PUBLICUS_ABS_DIR . '/inc/no-category.php';
}
$puock_class_load = new PuockClassLoad();

/*Auth-Domains*/

//钩子添加集合
if (publicus_is_checked('html_page_permalink')) {
    add_action('init', 'html_page_permalink', -1);
}
add_filter('user_trailingslashit', 'add_init_trailingslashit', 10, 2);


function publicus_open_session()
{
    session_start();
}

function publicus_wclose_session()
{
    session_write_close();
}

function publicus_session_call($function)
{
    publicus_open_session();
    try {
        $function();
    } finally {
        session_write_close();
    }
}

function publicus_get_theme_option_url($to = '')
{
    return admin_url() . 'admin.php?page=publicus-options&to=' . $to;
}


// Üst menüye özel menü ekle
function publicus_toolbar_link(WP_Admin_Bar $bar)
{
    $menu_id = 'theme-quick-start';
    $bar->add_node(array(
        'id' => $menu_id,
        'title' => '<i class="czs-paper-plane"></i>&nbsp;Publicus Theme Hızlı Erişim',
        'href' => '#'
    ));
    $bar->add_node(array(
        'id' => 'theme-setting',
        'parent' => $menu_id,
        'title' => '<i class="czs-setting" style="color:#9627e3"></i>&nbsp;Tema Ayarları',
        'href' => publicus_get_theme_option_url()
    ));
    $bar->add_node(array(
        'id' => 'theme-docs',
        'parent' => $menu_id,
        'title' => '<i class="czs-doc-file" style="color:#496cf9"></i>&nbsp;Tema Dokümantasyonu',
        'href' => 'https://www.publicus.agency/docs',
        'meta' => array(
            'target' => 'blank'
        )
    ));
    $bar->add_node(array(
        'id' => 'theme-support',
        'parent' => $menu_id,
        'title' => '<i class="czs-heart" style="color:#f54747"></i>&nbsp;Destek',
        'href' => 'https://www.publicus.agency/support',
        'meta' => array(
            'target' => 'blank'
        )
    ));
    $bar->add_node(array(
        'id' => 'theme-contact',
        'parent' => $menu_id,
        'title' => '<i class="czs-mail" style="color:#177b17"></i>&nbsp;İletişim',
        'href' => 'https://www.publicus.agency/contact',
        'meta' => array(
            'target' => 'blank'
        )
    ));
    $bar->add_node(array(
        'id' => 'theme-github',
        'parent' => $menu_id,
        'title' => '<i class="czs-github-logo"></i>&nbsp;Github Sayfası',
        'href' => 'https://github.com/publicus-agency/wordpress-theme-publicus',
        'meta' => array(
            'target' => 'blank'
        )
    ));
}

if (is_user_logged_in() && current_user_can('manage_options')) {
    add_action('admin_bar_menu', 'publicus_toolbar_link', 999);
}

function publicus_admin_scripts()
{
    wp_enqueue_script('puock-admin', get_stylesheet_directory_uri() . '/assets/dist/admin.min.js',
        array(), PUBLICUS_CUR_VER_STR, true);
}

add_action('admin_enqueue_scripts', 'publicus_admin_scripts');
function publicus_admin_print_scripts()
{
    $settings = json_encode(array(
        'compatible' => [
            'githubermd' => defined('GITHUBER_PLUGIN_NAME')
        ]
    ));
    echo "<script type='text/javascript'>var puock_admin_setting = $settings</script>";
}

add_action('admin_print_footer_scripts', 'publicus_admin_print_scripts', 1);


function publicus_is_pjax()
{
    return publicus_is_checked('page_ajax_load', false);
}

//判断阅读数量是否需要增加并进行操作
if (!function_exists('the_views_add')) {
    function the_views_add($post_ID, $count, $key, $ajax = false)
    {
        if (is_single() || is_page() || $ajax) {
            if ($count == '') {
                add_post_meta($post_ID, $key, '0');
            } else {
                update_post_meta($post_ID, $key, $count + 1);
                $count++;
            }
        }
        return $count;
    }
}

//获取当前的阅读数量与自增
if (!function_exists('the_views')) {
    function the_views($post_id = null, $echo = true, $ajax = false)
    {
        global $post;
        if ($post_id == null) {
            $post_id = $post->ID;
        }
        $key = 'views';
        $count = get_post_meta($post_id, $key, true);
        if ($count == '') {
            $count = 0;
        }
        $count = the_views_add($post_id, $count, $key, $ajax);
        $count_view = number_format_i18n($count);
        if (!$echo) {
            return $count_view;
        }
        echo $count_view;
    }
}
//异步请求浏览量
function async_pk_views()
{
    $postId = $_POST['id'];
    if (empty($postId)) {
        echo publicus_ajax_resp(0);
        return;
    }
    echo publicus_ajax_resp(the_views($postId, false, true));
    wp_die();
}

add_action('wp_ajax_nopriv_async_pk_views', 'async_pk_views');
add_action('wp_ajax_async_pk_views', 'async_pk_views');

//设置文章发布的时候进行字段添加
function set_views($post_ID)
{
    $key = 'views';
    $count = get_post_meta($post_ID, $key, true);
    if ($count == '') {
        add_post_meta($post_ID, $key, '0');
    }
}

add_action('publish_post', 'set_views');

function get_total_views()
{
    global $wpdb;
    $views = publicus_cache_get(PKC_TOTAL_VIEWS);
    if (!$views) {
        $views = $wpdb->get_var("SELECT SUM(meta_value) FROM $wpdb->postmeta where meta_key='views'");
        publicus_cache_set(PKC_TOTAL_VIEWS, $views);
    }
    return $views;
}


function publicus_check_right_md5()
{
    global $publicus_right_slug;
    if (empty($publicus_right_slug) || md5($publicus_right_slug) != 'd8775b419146efc39dea7b0ab50c56d0') {
        wp_die(base64_decode('UGxlYXNlIGZvbGxvdyB0aGUgb3BlbiBzb3VyY2UgcnVsZXMgYW5kIGRvIG5vdCBjaGFuZ2Ugb3IgZGVsZXRlIHRoZSBib3R0b20gY29weXJpZ2h0IQ=='));
    }
}

add_action('init', 'publicus_check_right_md5');


/**
 * 获取查看最多的文章
 * @param $days N天内
 * @param $nums 数量
 * @return array|object|null
 */
function get_views_most_post($days, $nums)
{
    global $wpdb;
    $sql = "select posts.*, meta.meta_value as views
            from {$wpdb->posts} as posts INNER JOIN (select post_id,(meta_value+0) as meta_value from 
            {$wpdb->postmeta} where meta_key='views' order by (meta_value+0) DESC) as meta
            on meta.post_id = posts.ID
            where posts.post_type = 'post'
            AND posts.post_status = 'publish' AND TO_DAYS(now()) - TO_DAYS(posts.post_date) < {$days}
            ORDER BY meta.meta_value DESC limit 0, {$nums}";
    return $wpdb->get_results($sql);
}

//是否隐藏Kenar Çubuğu
function publicus_hide_sidebar($post_id = null)
{
    global $post;
    if (publicus_is_checked("hide_global_sidebar")) {
        return true;
    }
    if (is_single() || is_page()) {
        if ($post_id == null) {
            $post_id = $post->ID;
        }
        return get_post_meta($post_id, 'hide_side', true) == "true";
    }
    return false;
}

//隐藏/显示Kenar Çubuğu的输出字符
function publicus_hide_sidebar_out($hide = '', $show = '', $post_id = null, $echo = true)
{
    $out = $show;
    if (publicus_hide_sidebar()) {
        $out = $hide;
    }
    if (!$echo) {
        return $out;
    }
    echo $out;
}

//Kenar Çubuğu检测数据
function publicus_sidebar_check_has($name)
{
    if (!dynamic_sidebar($name)) {
        dynamic_sidebar('sidebar_not');
    }
}

//获取链接对象，用于书籍推荐及其他页面使用
function publicus_get_wp_links($link_cats = '')
{
    global $wpdb;
    if (empty($link_cats)) {
        return null;
    }
    if (is_array($link_cats)) {
        $link_cats = implode(',', $link_cats);
    }
    $sql = "select links.*,terms.term_id,terms.name  from {$wpdb->links} as links
            LEFT JOIN (select * from {$wpdb->term_relationships} where term_taxonomy_id in ({$link_cats})) as relat on links.link_id = relat.object_id
            LEFT JOIN (selecT * from {$wpdb->terms} where term_id in ({$link_cats})) as terms on terms.term_id = relat.term_taxonomy_id
             where links.link_id in (relat.object_id) and links.link_visible='Y'";
    return $wpdb->get_results($sql);
}

//获取懒加载图片信息
function publicus_get_lazy_pl_img()
{
    return publicus_get_static_url() . "/assets/img/z/load.svg";
}

function publicus_get_lazy_img_info($origin, $class = '', $width = null, $height = null, $thumbnail = true)
{
    if (!publicus_is_checked('basic_img_lazy_s')) {
        if ($thumbnail) {
            $out = "src='" . publicus_get_img_thumbnail_src($origin, $width, $height) . "' ";
            $out .= "class='" . $class . "' ";
        } else {
            $out = "src='{$origin}' ";
            $out .= "class='{$class}' ";
        }
    } else {
        $out = "src='" . publicus_get_lazy_pl_img() . "' ";
        $out .= "class='lazy " . $class . "' ";
        if ($thumbnail) {
            $out .= "data-src='" . publicus_get_img_thumbnail_src($origin, $width, $height) . "'";
        } else {
            $out .= "data-src='" . $origin . "'";
        }
    }
    return $out;
}

function publicus_content_img_lazy($content)
{
    return preg_replace('/<img(.+)src=[\'"]([^\'"]+)[\'"](.*)>/i', "<img\$1data-src=\"\$2\" data-lazy=\"true\" src=\"" . publicus_get_lazy_pl_img() . "\"\$3/>", $content);
}

if (publicus_is_checked('basic_img_lazy_z')) {
    add_filter('the_content', 'publicus_content_img_lazy');
}
//获取图片缩略图链接
function publicus_get_img_thumbnail_src($src, $width, $height, $args = array())
{
    if ($width == null || $height == null) {
        return $src;
    }
    if (publicus_is_checked('thumbnail_rewrite_open')) {
        return home_url() . "/timthumb/w_{$width}/h_{$height}/q_90/zc_1/a_c/" . publicus_safe_base64_encode($src) . ".png";
    }
    return PUBLICUS_ABS_URI . "/timthumb.php?w={$width}&h={$height}&a=c&zc=1&q=90&src=" . $src;
}

//获取文章样式是否是卡片式
function publicus_post_style_list()
{
    return publicus_get_option('post_style', 'list') == 'list';
}

//评论添加@功能
if (publicus_is_checked('comment_has_at')) {
    add_filter('comment_text', 'publicus_comment_add_at', 10, 2);
}
//GrAvatar头像源切换
if (publicus_get_option('gravatar_url', 'wp') != 'wp') {
    $type = publicus_get_option('gravatar_url', 'wp');
    if ($type == 'cravatar') {
        add_filter('get_avatar', 'cr_avatar');
        add_filter('get_avatar_url', 'cr_avatar');
    } else if ($type == 'loli') {
        add_filter('get_avatar', 'loli_avatar');
        add_filter('get_avatar_url', 'loli_avatar');
    } else if ($type == 'cn') {
        add_filter('get_avatar', 'cn_avatar');
        add_filter('get_avatar_url', 'cn_avatar');
    } else if ($type == 'cn_ssl') {
        add_filter('get_avatar', 'cn_ssl_avatar');
        add_filter('get_avatar_url', 'cn_ssl_avatar');
    } else if ($type == 'loli_ssl') {
        add_filter('get_avatar', 'loli_ssl_avatar');
        add_filter('get_avatar_url', 'loli_ssl_avatar');
    } else if ($type == 'v2ex') {
        add_filter('get_avatar', 'v2ex_ssl_avatar');
        add_filter('get_avatar_url', 'v2ex_ssl_avatar');
    } else if($type=='custom'){
        add_filter('get_avatar', 'publicus_custom_avatar');
        add_filter('get_avatar_url', 'publicus_custom_avatar');
    }
}
//评论者链接
function publicus_comment_author_url($comment_ID = 0)
{
    global $comment;
    $attr = '';
    if (!empty($comment) && $comment->user_id != 0) {
        $url = get_author_posts_url($comment->user_id);
    } else {
        $url = get_comment_author_url($comment_ID);
        $attr = "target='_blank' rel='external nofollow'";
    }
    $author = get_comment_author($comment_ID);
    echo empty($url) ? $author : "<a " . $attr . " href='" . publicus_go_link($url) . "' class='url'>$author</a>";
}

//评论回复通知
if (publicus_is_checked('comment_mail_notify')) {
    add_action('comment_unapproved_to_approved', 'comment_mail_notify');
    add_action('comment_post', 'comment_mail_notify');
}
//覆盖邮件配置
if (publicus_is_checked('smtp_open')) {
    function mail_smtp_set($phpmailer)
    {
        $phpmailer->From = publicus_get_option('smtp_form', '');
        $phpmailer->FromName = publicus_get_option('smtp_form_n', '');
        $phpmailer->Host = publicus_get_option('smtp_host', '');
        $phpmailer->Port = publicus_get_option('smtp_port', '');
        $phpmailer->SMTPSecure = publicus_is_checked('smtp_ssl') ? 'ssl' : '';
        $phpmailer->Username = publicus_get_option('smtp_u', '');
        $phpmailer->Password = publicus_get_option('smtp_p', '');
        $phpmailer->IsSMTP();
        $phpmailer->SMTPAuth = true;
    }

    add_action('phpmailer_init', 'mail_smtp_set');
}
//检测是否默认的第三方生成邮箱
function publicus_email_change_email($email_change_email, $user = null, $userdata = null)
{
    if (publicus_check_email_is_sysgen($email_change_email['to'])) {
        return null;
    }
    return $email_change_email;
}

add_filter('email_change_email', 'publicus_email_change_email');
//检测邮箱是否系统生成
function publicus_check_email_is_sysgen($email)
{
    return preg_match("/^_p_[\w].+@null.null/", $email);
}

function login_protection()
{
    if (!is_user_logged_in()) {
        if ($_GET[publicus_get_option('lp_user', 'admin')] != publicus_get_option('lp_pass', 'admin')) {
            header("Location: " . home_url());
        }
    }
}

if (publicus_is_checked('login_protection')) {
    add_action('login_enqueue_scripts', 'login_protection');
}
if (publicus_is_checked('compress_html')) {
    add_action('get_header', 'wp_compress_html');
}
//百度主动推送
function publicus_baidu_submit($post_ID)
{
    if (get_post_meta($post_ID, 'baidu_submit_url_status', true) == 1) return;
    $post_url = get_permalink($post_ID);
    $api_url = publicus_get_option('baidu_submit_url');
    $resp = wp_remote_post($api_url, array('body' => $post_url, 'headers' => 'Content-Type: text/plain'));
    $res = @json_decode($resp['body'], true);
    if (isset($res['success'])) {
        add_post_meta($post_ID, 'baidu_submit_url_status', 1, true);
    }
}

if (publicus_is_checked('open_baidu_submit')) {
    add_action('publish_post', 'publicus_baidu_submit', 0);
}
//对pre里面的内容进行转义
function publicus_tag_pre_encode($content)
{
    preg_match_all("/<pre.*?>(.+?)<\/pre>/is", $content, $matches);
    if (isset($matches[1])) {
        foreach ($matches[1] as $match) {
            $m = trim($match);
            if (!(substr($m, 0, strlen("<code")) === "<code")) {
                $m = "<code class='language-default'>$m</code>";
            }
            if (substr($m, 0, strlen("<code>")) === "<code>") {
                $m = "<code class='language-default'>" . substr($m, strlen("<code>"));
            }
            $content = str_replace($match, $m, $content);
        }
    }
    return $content;
}

add_filter('the_content', 'publicus_tag_pre_encode');
add_filter('comment_text', 'publicus_tag_pre_encode');
function publicus_htmlspecialchars($content)
{
    $content = str_replace("<", "&lt;", $content);
    $content = str_replace(">", "&gt;", $content);
    return $content;
}


function create_taxs($tax_slug, $hook_type, $tax_name)
{
    //自定义分类法标签
    $labels_tax = array(
        'name' => $tax_name,
        'singular_name' => $tax_name,
        'search_items' => '搜索' . $tax_name,
        'all_items' => '所有' . $tax_name,
        'parent_item' => '父级' . $tax_name,
        'parent_item_colon' => '父级' . $tax_name,
        'edit_item' => '编辑' . $tax_name,
        'update_item' => '更新' . $tax_name,
        'add_new_item' => '添加新' . $tax_name,
        'new_item_name' => '新' . $tax_name . '名称',
        'menu_name' => $tax_name,
    );

    //自定义分类法参数
    $args_tax = array(
        'hierarchical' => true,
        'labels' => $labels_tax,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => $tax_slug),
    );

    register_taxonomy($tax_slug, array($hook_type), $args_tax);
}


//注册菜单
register_nav_menus(array(
    'primary' => '主要菜单',
));

//获取主题配置
function publicus_get_option($name, $default = null)
{
    $config = get_option(PUBLICUS_OPT);
    if ($config && isset($config[$name])) {
        return $config[$name];
    }
    return $default;
}

//配置是否选择
function publicus_is_checked($name, $default = false)
{
    $val = publicus_get_option($name);
    if ($val === true || $val === 'true' || $val === 1 || $val === '1') {
        return true;
    }
    if ($val === null) {
        return $default;
    }
    return false;
}

//配置选择输出
function publicus_checked_out($name, $out = '', $default = 0)
{
    if (publicus_is_checked($name, $default)) {
        echo $out;
    }
}

//主题模式
function publicus_theme_light()
{
    if (isset($_COOKIE['mode'])) {
        return ($_COOKIE['mode'] == 'light') || $_COOKIE['mode'] == 'auto';
    }
    return publicus_get_option('theme_mode', 'light') == 'light';
}

function publicus_theme_mode()
{
    if (isset($_COOKIE['mode'])) {
        return $_COOKIE['mode'];
    }
    return 'auto';
}

//动画载入
function publicus_open_box_animated($class, $echo = true)
{
    $open = publicus_is_checked("page_animate") == 1;
    if ($open) {
        if (!$echo) {
            return $class;
        }
        echo $class;
    }
}


//获取所有站点分类id
function get_all_category_id($type = null)
{
    global $wpdb;
    $sql = "SELECT term_id, name FROM $wpdb->terms";
    if ($type != null) {
        $sql .= " where term_id in (select term_id from $wpdb->term_taxonomy where taxonomy='{$type}')";
    }
    $cats = $wpdb->get_results($sql);
    $out = '<span style="line-height: 1.5rem">';
    foreach ($cats as $cat) {
        $out .= '<span>[' . $cat->name . "：<code>" . $cat->term_id . '</code></span>]、';
    }
    if (count($cats) > 0) {
        $out = mb_substr($out, 0, mb_strlen($out) - 1);
    }
    $out .= '</span>';
    return $out;
}

//获取所有站点分类id
function get_all_category_id_row($type = null)
{
    global $wpdb;
    $sql = "SELECT term_id, name FROM $wpdb->terms";
    if ($type != null) {
        $sql .= " where term_id in (select term_id from $wpdb->term_taxonomy where taxonomy='{$type}')";
    }
    $cats = $wpdb->get_results($sql);
    $result = [];
    foreach ($cats as $cat) {
        $result[] = ['label' => $cat->name, 'value' => $cat->term_id];
    }
    return $result;
}


//获取菜单数据
function publicus_get_main_menu($mobile = false)
{
    global $wp;
    $menus = get_nav_menu_object('primary');
    $out = $mobile ? "<ul class='puock-links t-md'>" : "<ul>";
    if ($menus && count($menus) > 0) {
        publicus_get_menu_obj_to_html($menus, $out, $mobile);
    }
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        $avatar = get_avatar_url($user->user_email);
    } else {
        if (publicus_is_checked('open_quick_login')) {
            $url = publicus_ajax_url('publicus_font_login_page', ['redirect' => home_url($wp->request)]);
            $out .= '<li><a data-no-instant data-bs-toggle="tooltip" title="登入" data-title="登入" href="javascript:void(0)" class="pk-modal-toggle" data-once-load="true" data-url="' . $url . '"><i class="fa fa-right-to-bracket"></i></a></li>';
        }
    }
    if (!$mobile) {
        if (publicus_is_checked('theme_mode_s')) {
            $out .= '<li><a class="colorMode" data-bs-toggle="tooltip" title="模式切换" href="javascript:void(0)"><i class="fa fa-' . ((publicus_theme_mode() === 'auto' ? 'circle-half-stroke' : (publicus_theme_light() ? 'sun' : 'moon'))) . '"></i></a></li>';
        }
        $out .= '<li><a class="search-modal-btn" data-bs-toggle="tooltip" title="搜索" href="javascript:void(0)"><i class="fa fa-search"></i></a></li>';
    }
    $out .= '</ul>';
    return $out;
}

//获取菜单对象数据
function get_nav_menu_object($location)
{
    $locations = get_nav_menu_locations();
    if (!$locations) {
        return null;
    }
    $menu_id = $locations[$location];
    $menu_object = wp_get_nav_menu_object($menu_id);
    $menu_items = wp_get_nav_menu_items($menu_object->term_id);
    $menus = array();
    if ($menu_items == null || count($menu_items) == 0) {
        return $menus;
    } else {
        foreach ($menu_items as &$menu_item) {
            if (!isset($menu_item->classes) || $menu_item->classes == null) {
                $menu_item->classes = array();
            }
        }
    }
    _wp_menu_item_classes_by_context($menu_items);
    $submenus = array();
    foreach ($menu_items as $m) {
        $m->children = array();
        if (!$m->menu_item_parent) {
            $menus[$m->ID] = $m;
        } else {
            $submenus[$m->ID] = $m;
            if (isset($menus[$m->menu_item_parent])) {
                $menus[$m->menu_item_parent]->children[$m->ID] = &$submenus[$m->ID];
            } else {
                $submenus[$m->menu_item_parent]->children[$m->ID] = $submenus[$m->ID];
            }
        }
    }
    return $menus;
}

//将匹配的到的菜单数据转换为html
function publicus_get_menu_obj_to_html($menus, &$out, $mobile = false, $dpath_cur = 1, $max_dpath = 2)
{
    $child_class = $dpath_cur != 1 ? 'menu-item-child' : '';
    $target = publicus_link_target(false);
    foreach ($menus as $menu) {
        $classes = join(" ", $menu->classes);
        $cur = $menu->current ? 'menu-current' : '';
        $out .= "<li id='menu-item-{$menu->ID}' class='menu-item-{$menu->ID} {$classes} {$child_class} {$cur}'>";
        if (!$mobile) {
            $out .= "<a class='ww' data-color='auto' {$target} href='{$menu->url}'>{$menu->title}";
        } else {
            $out .= '<span><a ' . $target . ' href="' . $menu->url . '">' . $menu->title . '</a>';
        }
        if (count($menu->children) > 0) {
            if ($mobile) {
                $out .= '<a href="#menu-sub-' . $menu->ID . '" data-bs-toggle="collapse"><i class="fa fa-chevron-down t-sm ml-1 menu-sub-icon"></i></a>';
            } else {
                $out .= '<i class="fa fa-chevron-down t-sm ml-1 menu-sub-icon"></i>';
            }
        }
        if ($mobile) {
            $out .= '</span>';
        } else {
            $out .= '</a>';
        }
        if (count($menu->children) > 0 && $dpath_cur < $max_dpath) {
            $out .= '<ul ' . ($mobile ? 'id="menu-sub-' . $menu->ID . '"' : '') . ' class="sub-menu ' . ($mobile
                    ? 'collapse' : '') . '">';
            publicus_get_menu_obj_to_html($menu->children, $out, $mobile, $dpath_cur + 1, $max_dpath);
            $out .= '</ul>';
        }
    }
}

//获取分类的子集菜单
function get_category_child($parentId)
{
    $child = get_categories("parent={$parentId}&hide_empty=0");
    $list = array();
    foreach ($child as $child_item) {
        $list[] = array(
            'url' => get_category_link($child_item),
            'item' => $child_item
        );
    }
    return $list;
}

//主查询设置
function publicus_pre_post_set($query)
{
    if ($query->is_home() && $query->is_main_query()) {
        if (publicus_get_option('index_mode', '') == 'cms') {
            $sort = publicus_get_option('cms_new_sort', 'published');
            $query->set('posts_per_page', publicus_get_option('cms_show_new_num', 6));
            if ( $sort == 'published' ) {
                $query->set( 'orderby', 'date' );
                $query->set( 'order', 'DESC' );
            } elseif ( $sort == 'updated' ) {
                $query->set( 'orderby', 'modified' );
                $query->set( 'order', 'DESC' );
            }
        }
    }
}

add_action('pre_get_posts', 'publicus_pre_post_set');

//静态资源加载源的链接
function publicus_get_static_url()
{
    $type = publicus_get_option('static_load_origin', 'self');
    switch ($type) {
        case "jsdelivr":
            $url_pre = "https://cdn.jsdelivr.net/gh/Licoy/wordpress-theme-puock@v" . PUBLICUS_CUR_VER_STR;
            break;
        case "jsdelivr-fastly":
            $url_pre = "https://fastly.jsdelivr.net/gh/Licoy/wordpress-theme-puock@v" . PUBLICUS_CUR_VER_STR;
            break;
        case "jsdelivr-testingcf":
            $url_pre = "https://testingcf.jsdelivr.net/gh/Licoy/wordpress-theme-puock@v" . PUBLICUS_CUR_VER_STR;
            break;
        case "jsdelivr-gcore":
            $url_pre = "https://gcore.jsdelivr.net/gh/Licoy/wordpress-theme-puock@v" . PUBLICUS_CUR_VER_STR;
            break;
        case 'custom':
            $url_pre = publicus_get_option('custom_static_load_origin', '');
            break;
        default:
            $url_pre = PUBLICUS_ABS_URI;
    }
    return $url_pre;
}

//是否打开讨论-显示头像
function publicus_open_show_comment_avatar()
{
    return get_option('show_avatars') == "1";
}

//关闭区块小工具
function publicus_off_widgets_block()
{
    add_filter('gutenberg_use_widgets_block_editor', '__return_false');
    add_filter('use_widgets_block_editor', '__return_false');
}

//获取中文格式化的实例
function publicus_chinese_format($content)
{
    include_once dirname(__FILE__) . '/../lib/ChineseTypesetting.php';
    $typesetting = new ChineseTypesetting();
    $content = $typesetting->insertSpace($content);
    $content = $typesetting->removeSpace($content);
    return $typesetting->full2Half($content);
}

if (publicus_is_checked('chinese_format')) {
    add_filter('the_content', 'publicus_chinese_format', 199);
}

//获取缩略图的白名单
function publicus_get_thumbnail_allow_sites()
{
    $sites = [];
    $thumbnail_allows = trim(publicus_get_option("thumbnail_allows", ''));
    if (!empty($thumbnail_allows)) {
        foreach (explode("\n", $thumbnail_allows) as $site) {
            $site = trim($site);
            if (!empty($site)) {
                $sites[] = $site;
            }
        }
    }
    return $sites;
}

//生成缩略图白名单文件名称
function publicus_get_thumbnail_allow_sites_filepath()
{
    return PUBLICUS_ABS_DIR . '/.tas.php';
}

//生成缩略图白名单文件
function publicus_generate_thumbnail_allow_sites_file()
{
    $sites = publicus_get_thumbnail_allow_sites();
    $template = "<?php \$ALLOWED_SITES = [\n";
    if (count($sites) > 0) {
        foreach ($sites as $site) {
            $template .= "\t\"$site\",\n";
        }
    }
    $template .= "];";
    return file_put_contents(publicus_get_thumbnail_allow_sites_filepath(), $template);
}

add_action('publicus_option_updated', 'publicus_generate_thumbnail_allow_sites_file', 10, 0);

// get request model data
function publicus_get_req_data(array $model)
{
    $data = [];
    foreach ($model as $key => $item) {
        $val = trim($_REQUEST[$key] ?? '');
        if (empty($val)) {
            if ($item['required']) {
                return ($item['name'] ?? $key) . '不能为空';
            }
            if (isset($item['default'])) {
                $data[$key] = $item['default'];
            }
            if ($item['empty'] ?? false) {
                $data[$key] = '';
            }
        } else {
            if ($item['remove_html'] ?? false) {
                $val = esc_html($val);
            }
            $data[$key] = $val;
        }
    }
    return $data;
}

function publicus_get_ip_region_str($ip)
{
    $ip2_instance = $GLOBALS['ip2_region'] ?? false;
    if (!$ip2_instance) {
        $ip2_instance = new \Ip2Region();
        $GLOBALS['ip2_region'] = $ip2_instance;
    }
    try {
        $s = $ip2_instance->memorySearch($ip);
    } catch (Exception $e) {
        return '未知';
    }
    if (strpos($s['region'], '内网IP') !== false) {
        return '内网IP';
    }
    $region = explode('|', $s['region']);
    $res = '';
    foreach ($region as $item) {
        if (strpos($item, '0') === 0) {
            continue;
        }
        $res .= $item;
    }
    return $res;
}

/**
 * @throws Exception
 */
function publicus_vd_gt_validate(array $args = null)
{
    if ($args == null) {
        $args = [
            'lot_number' => $_REQUEST['lot_number'] ?? '',
            'captcha_output' => $_REQUEST['captcha_output'] ?? '',
            'captcha_id' => $_REQUEST['captcha_id'] ?? '',
            'pass_token' => $_REQUEST['pass_token'] ?? '',
            'gen_time' => $_REQUEST['gen_time'] ?? '',
        ];
    }
    $key = publicus_get_option('vd_gt_key');
    $args['sign_token'] = hash_hmac('sha256', $args['lot_number'], $key);
    $result = wp_remote_request('https://gcaptcha4.geetest.com/validate?captcha_id=' . $args['captcha_id'], [
        'method' => 'POST',
        'body' => $args,
        'timeout' => 5
    ]);
    if (is_wp_error($result)) {
    }
    $result = json_decode($result['body'], true);
    if ($result['status'] != 'success' || $result['result'] != 'success') {
    }
    return true;
}

function publicus_user_center_url(): string
{
    if (publicus_is_checked('user_center')) {
        return home_url() . '/uc';
    }
    return get_edit_profile_url();
}

function publicus_rewrite_rule()
{
    if (publicus_is_checked('user_center')) {
        add_rewrite_rule('^uc/?([0-9A-Za-z_\-]+)?$', 'index.php?pagename=user-center&id=$matches[1]', "top");
    }
}

add_action('init', 'publicus_rewrite_rule');

function publicus_template_redirect()
{
    global $wp_query;
    $page_name = $wp_query->get('pagename');
    if (!empty($page_name)) {
        $template = '';
        switch ($page_name) {
            case 'user-center':
                $template = PUBLICUS_ABS_DIR . '/inc/page/user-center.php';
                break;
            default:
                break;
        }
        if (!empty($template)) {
            publicus_load_template($template);
            exit;
        }
    }
}

add_action('template_redirect', 'publicus_template_redirect');

function publicus_query_vars($vars){
    $vars[] = 'id';
    return $vars;
}
add_filter( 'query_vars', 'publicus_query_vars' );

function publicus_load_template($_template_file, $require_once = true, $args = array())
{
    status_header(200);
    load_template($_template_file, $require_once, $args);
}