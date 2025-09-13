<?php if (!publicus_hide_sidebar()): ?>
    <div id="sidebar" class="<?php publicus_open_box_animated('animated fadeInRight') ?> col-lg-4 d-none d-lg-block">
        <div class="sidebar-main">
            <?php
            if (is_home()):
                publicus_sidebar_check_has('sidebar_home');
            elseif (is_single()):
                publicus_sidebar_check_has('sidebar_single');
            elseif (is_search()):
                publicus_sidebar_check_has('sidebar_search');
            elseif (is_category() || is_tag()):
                publicus_sidebar_check_has('sidebar_cat');
            elseif (is_page()):
                publicus_sidebar_check_has('sidebar_page');
            else:
                publicus_sidebar_check_has('sidebar_other');
            endif;
            ?>
        </div>
    </div>
<?php endif; ?>
