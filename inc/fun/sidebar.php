<?php
add_action('widgets_init', 'publicus_widgets_init');
function publicus_widgets_init()
{
    publicus_common_sidebar_register('sidebar_single', '正文内容 - Kenar Çubuğu', '文章正文内容Kenar Çubuğu');
    publicus_common_sidebar_register('sidebar_home', 'Ana Sayfa - Kenar Çubuğu', 'Ana SayfaKenar Çubuğu');
    publicus_common_sidebar_register('sidebar_search', '搜索页 - Kenar Çubuğu', '搜索页Kenar Çubuğu');
    publicus_common_sidebar_register('sidebar_cat', '分类/标签页 - Kenar Çubuğu', '分类/标签页Kenar Çubuğu');
    publicus_common_sidebar_register('sidebar_page', '单页面 - Kenar Çubuğu', '单页面Kenar Çubuğu');
    publicus_common_sidebar_register('sidebar_other', '其他页面 - Kenar Çubuğu', '包括作者/404等其他页面');
    publicus_common_sidebar_register('sidebar_not', '通用 - Kenar Çubuğu', '若指定页面未配置任何栏目，则显示此栏目下的数据');
    publicus_common_sidebar_register('post_content_author_top', '正文 - 作者上方栏目', '显示在正文作者栏上方的栏目');
    publicus_common_sidebar_register('post_content_author_bottom', '正文 - 作者下方栏目', '显示在正文作者栏下方的栏目');
    publicus_common_sidebar_register('index_bottom', 'Ana Sayfa - Alt Bölüm', '显示在Ana Sayfa内容最底部（Arkadaş Bağlantıları上方的通栏项）');
    publicus_common_sidebar_register('index_cms_layout_top', 'CMS布局 - 分类栏上方栏目', 'CMS布局下显示在分类栏之上的栏目');
    publicus_common_sidebar_register('index_cms_layout_bottom', 'CMS布局 - 分类栏下方栏目', 'CMS布局下显示在分类栏之下的栏目');
    publicus_common_sidebar_register('post_content_comment_top', '正文 - Yorumlar Üstü栏目', '显示在正文Yorumlar Üstü的栏目');
    publicus_common_sidebar_register('post_content_comment_bottom', '正文 - 评论下方栏目', '显示在正文评论下方的栏目');
    publicus_common_sidebar_register('page_content_comment_top', '页面 - Yorumlar Üstü栏目', '显示在页面Yorumlar Üstü的栏目');
    publicus_common_sidebar_register('page_content_comment_bottom', '页面 - 评论下方栏目', '显示在页面评论下方的栏目');
}

function publicus_common_sidebar_register($id, $name, $description = '')
{
    register_sidebar(array(
        'name' => __($name, PUBLICUS),
        'id' => $id,
        'description' => __($description, PUBLICUS),
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
}
