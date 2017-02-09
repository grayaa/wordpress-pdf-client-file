<?php
/*
 * Template Name: PDF clients Files
*/

get_header(); ?>

    <!-- HEADER -->
    <div class="header-full animated fadeIn">
    
    	<img class="img-responsive" src="<?php header_image(); ?>" />
        <div class="header-full-inner">
        	<h1 class="header-title">
				<?php
                while ( have_posts() ) : the_post();
        
                    // Display Header title
                    the_title();
        
                // End the loop.
                endwhile;
                ?>
		
			</h1></div>
        
    </div>
    <!-- CONTENT -->
    <div class="content container">
        <div class="content-inner">
        
            <?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			// Include the page content template.
			get_template_part( 'content', 'page' );

		// End the loop.
		endwhile;
		?>
               
        
        </div>
    </div><!-- END CONTENT -->


<?php get_footer(); ?>
