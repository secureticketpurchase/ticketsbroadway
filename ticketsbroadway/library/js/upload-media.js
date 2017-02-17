jQuery(document).ready(function($){
    $('.upload_image_button').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            var imgObject = uploaded_image.toJSON();

            // Snag image ID and preview fields
            var inputID = jQuery( '#banner_id' );
            var preview = jQuery( '#img_preview' );

            // Set src attribute of preview image
            preview.attr( 'src', imgObject.url );

            // Set image's attachment ID to ID input field
            inputID.val( imgObject.id );

        });
    });
});