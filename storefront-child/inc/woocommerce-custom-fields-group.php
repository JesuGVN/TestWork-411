<?php


   add_action( 'admin_enqueue_scripts', 'admin_scripts' );
   function admin_scripts(){
      wp_enqueue_script( 'custom-woocommerce-js', get_stylesheet_directory_uri().'/js/custom-woocommerce.js');
   }


   add_action('add_meta_boxes', 'woocommerce_custom_fields', 1);

   function woocommerce_custom_fields($post) {
      add_meta_box('woocommerce_extra_fields', 'Additional Fields', 'woocommerce_custom_fields_func', 'product', 'normal', 'high');
   }

   function woocommerce_custom_fields_func() {
       global $post;
       global $content_width, $_wp_additional_image_sizes;

       $image_id = get_post_meta( $post->ID, '_thumbnail_id', true );

       $old_content_width = $content_width;
	   $content_width = 254;

       ?>

       <div class="product-custom-fields">
           <?php
            if ( $image_id && get_post( $image_id ) ) {

                if ( ! isset( $_wp_additional_image_sizes['post-thumbnail'] ) ) {
                    $thumbnail_html = wp_get_attachment_image( $image_id, array( $content_width, $content_width ) );
                } else {
                    $thumbnail_html = wp_get_attachment_image( $image_id, 'post-thumbnail' );
                }

                if ( ! empty( $thumbnail_html ) ) {
                    $content = $thumbnail_html;
                    $content .= '<p class="hide-if-no-js"><a href="javascript:;" id="remove_listing_image_button" class="submitdelete" >' . esc_html__( 'Remove image', 'text-domain' ) . '</a></p>';
                    $content .= '<input type="hidden" id="upload_listing_image" name="abel[_thumbnail_id]" value="' . esc_attr( $image_id ) . '" />';
                }

                $content_width = $old_content_width;
            } else {

                $content = '<img src="" style="width:' . esc_attr( $content_width ) . 'px;height:auto;border:0;display:none;" />';
                $content .= '<p class="hide-if-no-js"><a title="' . esc_attr__( 'Set listing image', 'text-domain' ) . '" href="javascript:;" id="upload_listing_image_button" id="set-listing-image" data-uploader_title="' . esc_attr__( 'Choose an image', 'text-domain' ) . '" data-uploader_button_text="' . esc_attr__( 'Set listing image', 'text-domain' ) . '">' . esc_html__( 'Set listing image', 'text-domain' ) . '</a></p>';
                $content .= '<input type="hidden" id="upload_listing_image" name="abel[_thumbnail_id]" value="" />';

            }

            ?>

            <div id="product-image-block">
                <strong>Product Image</strong><br>
                <?php
                    echo $content;
                ?>
            </div>

            <div id="product-date-block">
                <label>
                    <label for="published_date">Published Date</label>
                    <input id="published_date"
                        type="date"
                        name="abel[published_date]"
                    value="<?php echo get_the_date('Y-m-d'); ?>">
                </label>
            </div>

            <div id="product-type-block">
                <label>Select Product Type:
                    <select name="abel[product_type]">
                        <?php $sel_v = get_post_meta($post->ID, 'product_type', 1); ?>
                        <option value="Default"></option>
                        <option value="rare" <?php selected( $sel_v, 'rare' )?> >Rare</option>
                        <option value="frequent" <?php selected( $sel_v, 'frequent' )?> >Frequent</option>
                        <option value="unusual" <?php selected( $sel_v, 'unusual' )?> >Unusual</option>
                    </select> 
                </label>
            </div>

            <div id="product-option-block">
                <p><input type="submit" class="button-primary custom-update-product" value="Update All"> or <a href="javascript:;" class="custom-clear-fields">Clear All</a></p>
                <input type="hidden" name="abel_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
            </div>
       </div>


	<?php
   }

   add_action( 'save_post', 'woocommerce_custom_fields_save', 10, 1 );
   function woocommerce_custom_fields_save ( $post_id ) {

    if (array_key_exists('post_type', $_POST) && $_POST['post_type'] === 'product') {

        if (empty( $_POST['abel'] ) || ! wp_verify_nonce( $_POST['abel_fields_nonce'], __FILE__ )|| wp_is_post_autosave( $post_id )|| wp_is_post_revision( $post_id )) return false;
        $_POST['abel'] = array_map( 'sanitize_text_field', $_POST['abel'] );
        foreach( $_POST['abel'] as $key => $value ){
            if( empty($value) ){
                delete_post_meta( $post_id, $key ); // удаляем поле если значение пустое
                continue;
            }
    
            update_post_meta( $post_id, $key, $value ); // add_post_meta() работает автоматически
        
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
        
    }
   }
?>