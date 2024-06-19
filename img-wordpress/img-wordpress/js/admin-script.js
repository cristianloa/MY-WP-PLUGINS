jQuery(document).ready(function($){
    var frame;
    $('#upload_image_button').on('click', function(e) {
        e.preventDefault();
        if (frame) {
            frame.open();
            return;
        }
        frame = wp.media({
            title: 'Seleccionar Imagen para el Logo de Login',
            button: {
                text: 'Usar esta imagen'
            },
            multiple: false
        });
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('#login_logo_url').val(attachment.url);
            $('#login_logo_preview').attr('src', attachment.url);
            $('#remove_image_button').show();
        });
        frame.open();
    });
    $('#remove_image_button').on('click', function(e) {
        e.preventDefault();
        $('#login_logo_url').val('');
        $('#login_logo_preview').attr('src', '');
        $(this).hide();
    });
});
