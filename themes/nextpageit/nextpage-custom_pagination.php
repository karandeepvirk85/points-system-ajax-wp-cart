<?php
/**
 * Template to display Pagination
 * 
 */

?>
<div class="pagination">
    <div class="pagination-inner">
        <?php 
        $intBig = 99999999999; 
        echo paginate_links(
            array(
                'base' => str_replace( $intBig, '%#%', esc_url( get_pagenum_link( $intBig ) ) ),
                'format' => '?paged=%#%',
                'current' => max( 1, $args['paged']),
                'total' => $args['max_num_pages']
            ) 
        );
        ?>
    </div>
</div>

