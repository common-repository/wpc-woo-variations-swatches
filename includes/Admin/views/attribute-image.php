    <div class="wpc-wc-term-image-thumbnail" style = "float:left;margin-right:10px; " >
        <img src="<?php echo esc_url( $image ); ?>" data-value="<?php echo esc_url( $image ); ?>" class="wpc-image" width="60px" height="60px" />
    </div>
    <div style="line-height:60px;">
        <input type="hidden" class="wpc-wc-term-image" name="image" value="<?php echo esc_attr( $value ); ?>" />
        <button type="button" class="wpc-wc-upload-image-button bg-blue-500 rounded-lg cursor-pointer text-white px-2 py-2 border-0 outline-none focus:outline-none focus:ring">
        <?php esc_html_e( 'Upload/Add image', 'wpcvs' ); ?></button>

        <button type="button" class="wpc-wc-remove-image-button hidden rounded-md cursor-pointer bg-red-500 text-white px-2 py-2 border-0 outline-none focus:outline-none focus:ring">
        <?php esc_html_e( 'Remove', 'wpcvs' ); ?></button>
    </div>
