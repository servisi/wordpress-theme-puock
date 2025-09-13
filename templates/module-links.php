<?php
$link_cid = publicus_get_option('index_link_id', '');
if (!empty($link_cid)):
    ?>
    <div class="p-block index-links">
        <div>
        <span class="t-lg publicus-text pb-2 d-inline-block border-bottom border-primary">
            <i class="fa fa-link"></i>Arkadaş Bağlantıları
        </span>
        </div>
        <div class="mt20 t-md index-links-box">
            <?php
            $links = publicus_cache_get(PKC_FOOTER_LINKS);
            if(!$links){
                $order = publicus_get_option('index_link_order', 'ASC');
                $orderby = publicus_get_option('index_link_order_by', 'link_id');
                $links = get_bookmarks(array(
                    'category' => $link_cid,
                    'orderby' => $orderby,
                    'order' => $order,
                    'category_before' => '',
                    'title_li' => '',
                    'echo' => 0,
                    'class' => ''
                ));
                publicus_cache_set(PKC_FOOTER_LINKS, $links);
            }
            foreach ($links as $link) {
                if ($link->link_visible != 'Y') {
                    continue;
                }
                echo "<a href='$link->link_url' title='$link->link_name'
                    class='badge links-item'
                    rel='$link->link_rel' target='$link->link_target'>$link->link_name</a>";
            }
            $link_page_id = publicus_get_option('link_page', '');
            if (!empty($link_page_id)) {
                echo '<a target="_blank" class="badge links-item" href="' . get_page_link($link_page_id) . '">' . __('更多链接', PUBLICUS) . '</a>';
            }
            ?>
        </div>

    </div>
<?php endif; ?>
