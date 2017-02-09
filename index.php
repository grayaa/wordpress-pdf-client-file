<?php
/*
Plugin Name: PDF Client Files
Plugin URI: http://hammed-grayaa.tn
Description: Plugin permettant de générer le (nom - prénom - email) dans chaque entete de page dans les fichier pdf | shortcode to display All Files: [pdf-clients-files]
Version: 0.1
Author: Grayaa Hammed
Author URI: http://hammed-grayaa.tn
License: GPL2
*/


function pdf_clients_files_enqueue_style() {
    wp_enqueue_style( 'core', plugins_url( '/css/pdf_clients_files_style.css', __FILE__ ), false ); 
}

add_action( 'wp_enqueue_scripts', 'pdf_clients_files_enqueue_style' );


//Add posttype Pdf_Clients_fies
add_action( 'init', 'create_posttype_pdf_clients_files' );
function create_posttype_pdf_clients_files() {
  register_post_type( 'pdf-clients-files',
    array(
      'labels' => array(
        'name' => __( 'Pdf Clients Files' ),
        'singular_name' => __( 'Pdf Clients File' )
      ),
      'public' => true,
      'has_archive' => false,
      'rewrite' => array('slug' => 'pdf-clients-files'),
      'supports' => array('title'),
      'menu_icon' => 'dashicons-media-document'
    )
  );
}





//Add Post Type Template to display a Single pdf-client-file
    function load_single_pdf_client_file_template($template) {
        global $post;  

        // Is this a "my-custom-post-type" post?
        if ($post->post_type == "pdf-clients-files"){

            //Your plugin path 
            $plugin_path = plugin_dir_path( __FILE__ );

            // The name of custom post type single template
            $template_name = 'single-pdf-clients-files.php';

            // A specific single template for my custom post type exists in theme folder? Or it also doesn't exist in my plugin?
            if($template === get_stylesheet_directory() . '/' . $template_name
                || !file_exists($plugin_path . $template_name)) {

                //Then return "single.php" or "single-pdf-clients-files.php" from theme directory.
                return $template;
            }

            // If not, return my plugin custom post type template.
            return $plugin_path . $template_name;
        }

        //This is not my custom post type, do nothing with $template
        return $template;
    }
    add_filter('single_template', 'load_single_pdf_client_file_template');


//[pdf-clients-files] shortcode to display all pdf clients files
function pdf_clients_files_func(){
    global $wp_query;
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
            'post_status' => 'published',
            'post_type' => 'pdf-clients-files',
            'post_status' => 'publish',
            'posts_per_page' => 60,
            'paged'=>$paged,
            's' => $_GET['title']
             
    );

    $string = '';
    $wp_query = new WP_Query( $args );
    if( $wp_query->have_posts() ){
        $string .= '<ul class="pdf-files-list">';
        while( $wp_query->have_posts() ){
            $wp_query->the_post();
            if(! get_field('featured')){
                $string .= '<li class="pdf-file"><a target="_blank" href="' . get_the_permalink() . '">' . get_the_title() . '</a></li>';
            }
        }
        $string .= '</ul>';
    }
    wp_reset_postdata();
    return $string.get_the_posts_pagination();
}
add_shortcode( 'pdf-clients-files', 'pdf_clients_files_func' );






//[featured-pdf-clients-files] shortcode to display all pdf clients files
function featured_pdf_clients_files_func(){
    $args = array(
            'post_type' => 'pdf-clients-files',
            'post_status' => 'publish',
            'posts_per_page' => 99999
    );

    $query = new WP_Query( $args );
    if( $query->have_posts() ){
        $string = '<div class="featured-files">';
        while( $query->have_posts() ){
            $query->the_post();
            if(get_field('featured')){
                $string .= '<div class="pdf-file-block"><a target="_blank" href="' . get_the_permalink() . '"><span class="'. get_field('icon').'">' . get_the_title() . '</span></a></div>';
            }
        }
        $string .= '</div>';
    }
    wp_reset_postdata();
    return $string;
}
add_shortcode( 'featured-pdf-clients-files', 'featured_pdf_clients_files_func' );



    /*
//-- Adding the pdf to the post --
function add_pdf_clients_files_meta_boxes() {  
    add_meta_box('wp_custom_attachment', 'PDF File', 'wp_custom_attachment', 'pdf-clients-files', 'normal', 'high');  
}
add_action('add_meta_boxes', 'add_pdf_clients_files_meta_boxes');  

function wp_custom_attachment() {      

    wp_nonce_field(plugin_basename(__FILE__), 'wp_custom_attachment_nonce');

    $html = '<p class="description">';

        //--If there is selected pdf file display it--
        $filearray = get_post_meta( get_the_ID(), 'wp_custom_attachment', true );
        //var_dump($filearray);die();
        $current_pdf_file_url = $filearray['url'];
        $current_pdf_file_name = basename($current_pdf_file_url).PHP_EOL;
        if($current_pdf_file_url != ""){
           $html .= '<label>Current file:</label><a href="' . $current_pdf_file_url . '"> "'.$current_pdf_file_name.'"</a><br>';
        }

    $html .= 'Upload your PDF here.';
    $html .= '</p>';
    $html .= '<input type="file" id="wp_custom_attachment" name="wp_custom_attachment" value="'.$current_pdf_file_url.'" size="25">';
    echo $html;
}

add_action('save_post', 'save_custom_meta_data');
function save_custom_meta_data($id) {
    if(!empty($_FILES['wp_custom_attachment']['name'])) {
        $supported_types = array('application/pdf');
        $arr_file_type = wp_check_filetype(basename($_FILES['wp_custom_attachment']['name']));

        $uploaded_type = $arr_file_type['type'];

        if(in_array($uploaded_type, $supported_types)) {

            $upload = wp_upload_bits($_FILES['wp_custom_attachment']['name'], null, file_get_contents($_FILES['wp_custom_attachment']['tmp_name']));

            if(isset($upload['error']) && $upload['error'] != 0) {
                wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
            } else {
                update_post_meta($id, 'wp_custom_attachment', $upload);
            }
        }
        else {
            wp_die("The file type that you've uploaded is not a PDF.");
        }
    }
}

function update_edit_form() {
    echo ' enctype="multipart/form-data"';
}
add_action('post_edit_form_tag', 'update_edit_form');

*/

