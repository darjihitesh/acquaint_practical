<?php /* Template Name: Products */
get_header();

   $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 10,
    );

    $loop = new WP_Query( $args );
     echo '<div class="container">';
   	echo '<div class="row">';
   	echo '<div id="products">';
 	echo '<img class="custom-loader" src="'.get_stylesheet_directory_uri() . '/assets/loader.gif'.'">';
 		/* AJAX Response will append Here...*/
 
    echo '</div>';	
    wp_reset_query();
    echo '<div class="filter-option">';
    echo '<select name="product_filter" id="products_filter">
    <option value="default" selected>Default</option>
    <option value="featured">Show only featured products</option>
    <option value="popular">Show only Popular products</option>
    </select>';
    echo '</div>';
	echo '</div>';
    echo '</div>';
 ?>

<?php get_footer(); ?>

<script type="text/javascript">
	jQuery(document).on('ready',function (){
	 get_products();
})
</script>