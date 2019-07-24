<?php
/*
 Template Name: fullwidth Page
 */

  //Page settings
    $d_breacrumb = get_post_meta(get_the_ID(), 'mom_disbale_breadcrumb', true);
?>

<?php get_header(); ?>
    <div class="inner">
               <?php if ($d_breacrumb != true) { ?>
                <div class="category-title">
                        <?php mom_breadcrumb(); ?>
                </div>
                <?php } ?>    	
        <div class="page-wrap" style="margin-bottom:40px;">
                                <?php while ( have_posts() ) : the_post(); ?>
                        <?php the_content(); ?>
                        <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'theme' ), 'after' => '</div>' ) ); ?>
                    <?php endwhile; // end of the loop. ?>

        </div> <!-- base box -->
</div> <!--main inner-->
            
<?php get_footer(); ?>