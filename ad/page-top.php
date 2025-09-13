<!--内页上方-->
<?php if(publicus_is_checked('ad_page_t_c')): ?>
    <div class="publicus-text p-block t-md ad-page-top">
        <?php echo publicus_get_option('ad_page_t','') ?>
    </div>
<?php endif; ?>