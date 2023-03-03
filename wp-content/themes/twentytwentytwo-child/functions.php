<?php
/**
 * Recommended way to include parent theme styles.
 * (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
 *
 */  

 add_action( 'wp_enqueue_scripts', 'twentytwentytwo_child_style' );
  function twentytwentytwo_child_style() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css',array('parent-style'));
}


/* Redirect to checkout on Add to cart */
add_filter ('add_to_cart_redirect', 'redirect_to_checkout');

function redirect_to_checkout() {
    global $woocommerce;
    $checkout_url = $woocommerce->cart->get_checkout_url();
    return $checkout_url;
}


/* register asssets */

add_action( 'wp_enqueue_scripts', 'menu_scripts' );
function menu_scripts() {
    wp_enqueue_script(
        'wp-script',
        get_stylesheet_directory_uri() . '/assets/wp-practical.js',
        array( 'jquery' )
    );
}

add_action('wp_head', 'set_ajaxurl'); // set as i was facing some issues in js file

function set_ajaxurl() {

   echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

/* Ajax call to get products*/

add_action( 'wp_ajax_nopriv_get_products', 'get_products' );
add_action( 'wp_ajax_get_products', 'get_products' );

function get_products() {
    if($_GET['filter'] == 'featured'){
        $tax_query[] = array(
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => 'featured',
            'operator' => 'IN', // or 'NOT IN' to exclude feature products
        );

       $query = new WP_Query( array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => 10,
            'tax_query'           => $tax_query // <===
        ) );
    }elseif ($_GET['filter'] == 'popular') {
       $query = new WP_Query( array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1,
            'meta_key' => 'total_sales',
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
            'posts_per_page' => 10,
            )
         );
    }else{
        $query = new WP_Query( array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 10,
            )
        );
    }
  
    while ( $query->have_posts() ) : $query->the_post();
        global $product;
        $html .= "<a href='" . $product->add_to_cart_url() ."'><div class='column'>";
        $html .= '<img src="'.wp_get_attachment_url( $product->get_image_id() ).'" height="200" width="200">';
        $html .= '<h5>'.get_the_title().'</h5>';
        $html .= '</div>';
    endwhile;

    wp_reset_query();

    $data = ['response' => $html, 'Status' => 200];
    echo json_encode($data);
    exit;
}