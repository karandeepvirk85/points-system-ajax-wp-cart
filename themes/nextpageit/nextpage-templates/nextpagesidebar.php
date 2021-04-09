<?php 
/**
 * Blog Side Bar
 * Side bar called on home page and single 
 */

// Get Parent Categories
$arrCategories = get_categories(
    array(
        'orderby' => 'name',
        'parent'  => 0
    )   
);

// Get Recent Posts
$arrRecentPosts = wp_get_recent_posts(
    array(
        'numberposts' => 6,
        'post_status' => 'publish'
    )
);
?>

<?php get_template_part( 'nextpage-templates/searchform' ); ?>

<div class="categories-container">
    <h2>Categories</h2>
    <?php            
        foreach ($arrCategories as $objCategories) {?>
            <p>
                <a href="<?php echo get_category_link( $objCategories->term_id )?>">
                    <?php echo $objCategories->name;?>
                </a>
            </p>
    <?php  } ?>
</div>

<div class="recent-post-container">
    <h2>Recent Posts</h2>
    <ul>
        <?php
            foreach($arrRecentPosts as $arrRecentPost) {?>
            <li>
                <a href="<?php echo get_permalink($arrRecentPost['ID']) ?>">
                    <p><?php echo $arrRecentPost['post_title'] ?></p>
                    <p class="recent-post-date">
                        <?php if(class_exists('Theme_Controller')){ echo Theme_Controller::getPostDate($arrRecentPost ['post_date']);}?>
                    </p>
                </a>
            </li>
        <?php } ?>
    </ul>
</div>