<?php

use function donatj\UserAgent\parse_user_agent;

function publicus_comment_author_view($text, $comment)
{
    global $authordata;
    $author_view = get_post_meta($comment->comment_post_ID, 'author_cat_comment', true) == 'true';
    if (empty($authordata)) {
        $authordata = get_userdata(get_post_field('post_author', $comment->comment_post_ID));
    }
    $is_author = apply_filters('publicus_comment_author_view_is_author', $authordata->ID, $authordata->ID == get_current_user_id());
    if ($author_view && !$is_author) {
        $text = apply_filters('publicus_comment_author_view', '<div class="fs12 c-sub"><i class="fa fa-lock"></i> 评论仅对作者可见</div>');
    }
    return $text;
}

add_filter('comment_text', 'publicus_comment_author_view', 10, 2);

function publicus_comment_callback($comment, $args, $depth)
{
    global $authordata;
    $GLOBALS['comment'] = $comment;
    $author_cat_comment = get_post_meta($comment->comment_post_ID, 'author_cat_comment', true) == 'true';
    $is_author = $authordata->ID == get_current_user_id();
    if ($comment->comment_parent == 0) {
        $pccci_key = 'publicus_comment_callback_cur_id';
        $pccci = $GLOBALS[$pccci_key] ?? '';
        if (!empty($pccci)) {
            echo '</div>';
        }
        $GLOBALS[$pccci_key] = $comment->comment_ID;
    }
    ?>
<div id="comment-<?php comment_ID() ?>" class="post-comment">
    <div class="info">
        <?php if (publicus_open_show_comment_avatar()): ?>
            <div>
                <?php if (publicus_is_checked('basic_img_lazy_a')): ?>
                    <img src="<?php echo publicus_get_lazy_pl_img() ?>"
                         data-src="<?php echo get_avatar_url($comment->comment_author_email, 64); ?>"
                         class="avatar avatar-64 photo md-avatar lazy" width="60" height="60">
                <?php else: ?>
                    <?php echo get_avatar($comment, 64, '', '', array('class' => 'md-avatar')) ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="<?php if (publicus_open_show_comment_avatar()) {
            echo 'ml-2';
        } ?> two-info">
            <div class="publicus-text ta3b">
                <span class="t-md puock-links"><?php publicus_comment_author_url() ?></span>
                <?php if (publicus_is_checked('comment_level')) {
                    publicus_the_author_class();
                } ?>
            </div>
            <div class="t-sm c-sub">
                <span><?php comment_date('Y-m-d H:i:s') ?></span>
                <?php if ($comment->comment_approved == '1' && (!$author_cat_comment || $is_author)) : ?>
                    <a id="comment-reply-<?php comment_ID() ?>" data-id="<?php comment_ID() ?>"
                       class="hide-info animated bounceIn c-sub-a t-sm ml-1 comment-reply"
                       href="javascript:void(0)" title="回复此评论"><i class="fa fa-share-from-square"></i>
                        <span class="comment-reply-text">回复</span></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="content-text t-md mt10 publicus-text" <?php if (!publicus_open_show_comment_avatar()) {
            echo 'style="margin-left:0"';
        } ?>>
            <?php comment_text() ?>
            <?php if ($comment->comment_approved == '0') : ?>
                <p class="c-sub mt-1"><i class="fa fa-warning mr-1"></i>您的评论正在等待审核！</p>
            <?php endif; ?>

            <div class="comment-os c-sub">
                <?php
                if (publicus_is_checked('comment_show_ua', true)):
                    $commentUserAgent = parse_user_agent($comment->comment_agent);
                    $commentOsIcon = publicus_get_comment_ua_os_icon($commentUserAgent['platform']);
                    $commentBrowserIcon = publicus_get_comment_ua_os_icon($commentUserAgent['browser']);
                    echo "<span class='mt10' title='{$commentUserAgent['platform']}'><i class='$commentOsIcon'></i>&nbsp;<span>{$commentUserAgent['platform']}&nbsp;</span></span>";
                    echo "<span class='mt10' title='{$commentUserAgent['browser']} {$commentUserAgent['version']}'><i class='$commentBrowserIcon'></i>&nbsp;<span>{$commentUserAgent['browser']}</span></span>";
                endif;
                ?>
                <?php
                if (publicus_is_checked('comment_show_ip', true)) {
                    if (!publicus_is_checked('comment_dont_show_owner_ip') || (publicus_is_checked('comment_dont_show_owner_ip') && $comment->user_id != 1)) {
                        $ip = publicus_get_ip_region_str($comment->comment_author_IP);
                        echo "<span class='mt10' title='IP'><i class='fa-solid fa-location-dot'></i>&nbsp;$ip</span>";
                    }
                }
                ?>
            </div>
        </div>
        <div class="comment-box-reply d-none" id="comment-box-<?php comment_ID() ?>"></div>
    </div>
    <?php
}
