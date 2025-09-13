<!--文章列表-->
<div id="posts">
    <div class="<?php if(!publicus_post_style_list()){echo 'row';} ?> mr-0 ml-0">
        <?php while(have_posts()) : the_post(); ?>
            <?php get_template_part('templates/module','post') ?>
        <?php endwhile; ?>
    </div>
    <?php if(!(publicus_get_option('index_mode','')=='cms' && is_home()) || publicus_get_option('cms_show_pagination',false)): ?>
    <?php publicus_paging(); ?>
    <?php endif; ?>
</div>
