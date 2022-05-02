<meta charset="utf-8" />
 
<form method="post" action="<?php echo get_stylesheet_directory_uri(); ?>/product-handler.php" id="create-product-form" enctype="multipart/form-data">
    <fieldset>
        <legend>Create Product</legend>
     
        <div class="form-group product-title">
            <label class="control-label" for="title">Product Title</label>
            <input id="title" name="title" type="text" placeholder="Product Title" class="form-control" required>
        </div>
        
        <div class="form-group product-price col-2">
            <label class="control-label" for="descripriception">Product Price</label>
            <input type="text" class="form-control" id="_price" name="abel[price]" required>
        </div>


        <div class="form-group col-2">
            <label class="control-label" for="published_date">Product Date</label>
            <input id="published_date" type="date" name="abel[published_date]">
        </div>

        <div class="form-group product-type">
            <label class="control-label" for="published_type">Product Type</label>
            <select name="abel[product_type]" required>
                <option value="Default"></option>
                <option value="rare" >Rare</option>
                <option value="frequent" >Frequent</option>
                <option value="unusual" >Unusual</option>
            </select> 
        </div>

        <div class="form-group product-thumbnail">
            <label class="control-label" for="thumbnail">Product Image</label>
            <label for="icon_upload">
                <div class="img_wrapper"><div id="thumbnail_preview">
                    <span class="clear">X</span>
                </div></div>
            </label>
            <input type="file" name="ct_file" id="thumbnail" multiple="false" required preview-target-id="thumbnail_preview">
            
            <?php wp_nonce_field( 'my_image_upload', 'my_image_upload_nonce' ); ?>
        </div>
        
        <div class="form-group">
            <label class="control-label" for="send"></label>
            <button id="send" name="send" class="btn btn-primary">Create</button>
            <a href="javascript:;" id="submit_cancel">Clear All</a>
        </div>
    
    </fieldset>
</form>


