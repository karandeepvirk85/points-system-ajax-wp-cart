<?php 
/**
 * Posts Columns
 */
?>
<div class="col-md-6 posts-column" data-link="<?php echo get_permalink($post->ID); ?>" onclick="objTheme.sendToSinglePost()">
    <div class="post-container">
        <div class="post-top-container">
            <div class="post-image" style="background-image:url('<?php if(class_exists('Theme_Controller')){echo Theme_Controller::getPostImage($post->ID,'medium');}?>')"></div>
        </div>
        <div class="post-bottom-container">
            <div class="post-meta-container">
                <div class="post-author">
                    <?php echo ucwords(get_user_meta($post->post_author)['nickname'][0]); ?>
                </div>
                <div class="divider">
                    |
                </div>
                <div class="post-date">
                    <?php if(class_exists('Theme_Controller')){echo Theme_Controller::getPostDate($post->post_date);}?>
                </div>
            </div>
            <div class="post-title-container">
                <a href="<?php echo get_permalink($post->ID); ?>"> <?php the_title(); ?></a>
            </div>
            <div class="post-content-container">
                <?php 
                    if(class_exists('Theme_Controller')){
                        echo Theme_Controller::contentFilter($post->post_content,true,300);
                    }
                ?>
            </div>
            <div class="read-more-container">
                <a class="read-more-link" href="<?php the_permalink()?>">Read More..</a>
            </div>
        </div>
    </div>
</div>