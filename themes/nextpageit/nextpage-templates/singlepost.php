<?php 
/**
 * Tenplate to Display SIngle Post 
 * 
 */
global $post;
$objTerms = get_the_terms($post->ID,'category');
$arrTerms = array();
foreach ($objTerms as $objTerm){
    $arrTerms[] = $objTerm->name;
}
?>
<div class="single-post-container">
    <div class="post-title-container">
        <h1><?php the_title(); ?></h1>
    </div>
    <div class="post-meta-container">
        <div class="author-container">
            By <?php the_author_meta('user_nicename',$post->post_author); ?>        
        </div>
        <div class="divider">|</div>
        <div class="date-container">
            <?php if(class_exists('Theme_Controller')) {echo Theme_Controller::getPostDate($post->post_date);}?>
        </div>
        <div class="divider">|</div>
        <div class="category-container">
            <?php echo implode(', ',$arrTerms);?>       
        </div>
    </div>
    <div class="image-container">
        <img class="img-responsive" src="<?php if(class_exists('Theme_Controller')){echo Theme_Controller::getPostImage($post->ID,'full');}?>">
    </div>
    <div class="content-container">
        <?php if(class_exists('Theme_Controller')){
            echo Theme_Controller::contentFilter($post->post_content,false);
        }?>
    </div>
</div>