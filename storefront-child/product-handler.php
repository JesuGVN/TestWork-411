<?php
/* Define these, So that WP functions work inside this file */
define('WP_USE_THEMES', false);
require( $_SERVER['DOCUMENT_ROOT'] .'/abelohost/wp-blog-header.php');
?>

<?php
if(isset($_POST['send']) == '1') {
 $product_title = $_POST['title'];
 
 $new_post = array(
 'ID' => '',
 'post_author' => $user->ID,
 'post_content' => '',
 'post_type'  => 'product',
 'post_title' => $product_title,
 'post_name' => $product_title,
 'post_status' => 'publish'
 );
 
 $post_id = wp_insert_post($new_post);

 wp_set_object_terms( $post_id, 'Colors', 'product_cat' );
 wp_set_object_terms( $post_id, 'simple', 'product_type');

 update_post_meta( $post_id, '_visibility', 'visible' );
 update_post_meta( $post_id, '_stock_status', 'instock');
 update_post_meta( $post_id, 'total_sales', '0');
 update_post_meta( $post_id, '_downloadable', 'yes');
 update_post_meta( $post_id, '_virtual', 'yes');
 update_post_meta( $post_id, '_purchase_note', "" );
 update_post_meta( $post_id, '_featured', "no" );
 update_post_meta( $post_id, '_weight', "" );
 update_post_meta( $post_id, '_length', "" );
 update_post_meta( $post_id, '_width', "" );
 update_post_meta( $post_id, '_height', "" );
 update_post_meta( $post_id, '_sku', "");
 update_post_meta( $post_id, '_product_attributes', array());
 update_post_meta( $post_id, '_sale_price_dates_from', "" );
 update_post_meta( $post_id, '_sale_price_dates_to', "" );
 
 update_post_meta( $post_id, '_sold_individually', "" );
 update_post_meta( $post_id, '_manage_stock', "no" );
 update_post_meta( $post_id, '_backorders', "no" );
 update_post_meta( $post_id, '_stock', "" );
 
 // This will redirect you to the newly created post
 $post = get_post($post_id);



  require_once(ABSPATH . "wp-admin" . '/includes/image.php');
  require_once(ABSPATH . "wp-admin" . '/includes/file.php');
  require_once(ABSPATH . "wp-admin" . '/includes/media.php');

  if ($_FILES) {

    foreach ($_FILES as $file => $array) {
        if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
            return "upload error : " . $_FILES[$file]['error'];
        }
        $attach_id = media_handle_upload( $file, $post_id );
        
    }   
  }


  //echo $attach_id;
if ($attach_id > 0){
    //and if you want to set that image as Post  then use:
    update_post_meta($post_id,'_thumbnail_id',$attach_id);
}

foreach( $_POST['abel'] as $key => $value ){
    if( empty($value) ){
        delete_post_meta( $post_id, $key ); // удаляем поле если значение пустое
        continue;
    }

    update_post_meta( $post_id, $key, $value ); // add_post_meta() работает автоматически

    if($key == 'price') { 
        update_post_meta( $post_id, '_regular_price', $value );
        update_post_meta( $post_id, '_price',$value );
    }

    if($key == 'published_date') {
        $newDate = $value;

        remove_action('save_post', 'woocommerce_custom_fields_save');

        wp_update_post(
            array (
                'ID' => $post_id,
                'post_date' => $newDate,
                'post_date_gmt' => get_gmt_from_date( $newDate ),
                'edit_date'     => true
            )
        );

        add_action('save_post', 'woocommerce_custom_fields_save');
    }

}



 wp_redirect($post->guid);
}
?>