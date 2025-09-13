<?php

include '../../../wp-blog-header.php';

$error_info = "";

publicus_session_call(function () use (&$error_info) {
    $error_info = @$_SESSION['error_info'];
    unset($_SESSION['error_info']);
});

if (empty($error_info)) {
    $error_info = __('Hata bilgisi bulunamadı', PUBLICUS);
}

get_header();
?>

<div id="content" class="mt20 container min-height-container">

    <?php echo publicus_breadcrumbs() ?>

    <div class="text-center p-block  publicus-text">
        <h3 class="mt20"><?php echo $error_info ?></h3>
        <div class="text-center mt20">
            <a href="<?php echo home_url(); ?>" class="btn btn-primary"><?php _e('Ana Sayfaya Dön', PUBLICUS) ?></a>
        </div>
    </div>
</div>


<?php get_footer() ?>
