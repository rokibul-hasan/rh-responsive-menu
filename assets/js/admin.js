jQuery(document).ready(function($){
    var mediaUploader;
    $('.rh-upload-icon-button').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        var inputField = button.siblings('#rh_mobile_menu_icon');
        var previewDiv = button.siblings('.rh-icon-preview');
        var removeBtn = button.siblings('.rh-remove-icon-button');

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose a Menu Icon',
            button: {
                text: 'Choose Icon'
            },
            multiple: false
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            inputField.val(attachment.url);
            
            if (previewDiv.find('img').length > 0) {
                previewDiv.find('img').attr('src', attachment.url);
            } else {
                previewDiv.html('<img src="' + attachment.url + '" style="max-width: 50px; height: auto;" />');
            }
            removeBtn.show();
        });

        mediaUploader.open();
    });

    $('.rh-remove-icon-button').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        var inputField = button.siblings('#rh_mobile_menu_icon');
        var previewDiv = button.siblings('.rh-icon-preview');

        inputField.val('');
        previewDiv.empty();
        button.hide();
    });
});
