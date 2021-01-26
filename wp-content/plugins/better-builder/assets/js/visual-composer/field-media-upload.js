jQuery(document).ready(function($) {

    var frame;

    $('.vc_betterbuilder-media-upload .button').on('click', function(e) {

        var _self = $(this);

        // If the media frame already exists, reopen it.
        if( frame ) {
            frame.open();
            return;
        }

        // Create a new media frame
        frame = wp.media({
            title: 'Upload File',
            button: {
                text: 'Select file'
            },
            library: {
                type: _self.attr('data-file-type')
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected in the media frame...
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            _self.siblings('.better-text-input').val( attachment.url );
        });

        // Finally, open the modal on click
        frame.open();

    });

});