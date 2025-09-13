<?php
if (publicus_is_checked('async_view')) {
    $async_view_id = get_the_ID();
    if ($async_view_id) {
        echo '<script ' . (publicus_is_pjax() ? 'data-instant' : '') . '>$(function() {window.Publicus.asyncCacheViews(' . $async_view_id . ')})</script>';
    }
}
