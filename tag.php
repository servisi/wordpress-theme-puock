<?php get_header() ?>

    <div id="content" class="container mt15 ">
        <?php get_template_part('templates/box', 'global-top') ?>
        <?php echo publicus_breadcrumbs() ?>

        <div class="row row-cols-1">
            <div class="col-lg-<?php publicus_hide_sidebar_out('12','8') ?> col-md-12 <?php publicus_open_box_animated('animated fadeInLeft') ?> ">

                <?php get_template_part('templates/module','posts') ?>

            </div>
            <?php get_sidebar() ?>
        </div>

        <?php get_template_part('templates/box', 'global-bottom') ?>
    </div>



<?php get_footer() ?>
