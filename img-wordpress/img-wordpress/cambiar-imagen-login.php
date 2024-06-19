<?php
/*
Plugin Name: Custom img Login 😾
Description: Custom img Login WordPress.
Version: 1.0
Author: CristianLoa 😾
Author URI: https://www.facebook.com/CristianLoaH
*/

function cambiar_imagen_login_estilos() {
    $login_logo_url = esc_url( get_option('login_logo_url') );
    if ($login_logo_url) {
        ?>
        <style type="text/css">
            body.login div#login h1 a {
                background-image: url('<?php echo $login_logo_url; ?>');
                background-size: contain;
                width: 320px;
                height: 80px;
            }
        </style>
        <?php
    }
}
add_action('login_enqueue_scripts', 'cambiar_imagen_login_estilos');

function cambiar_imagen_login_url() {
    return home_url();
}
add_filter('login_headerurl', 'cambiar_imagen_login_url');

function cambiar_imagen_login_title() {
    return get_bloginfo('name');
}
add_filter('login_headertitle', 'cambiar_imagen_login_title');

// Añadir la página de configuración al menú de administración
function cambiar_imagen_login_menu() {
    add_options_page(
        'Custom img Login 😾',
        'Custom img Login 😾',
        'manage_options',
        'cambiar-imagen-login',
        'cambiar_imagen_login_opciones'
    );
}
add_action('admin_menu', 'cambiar_imagen_login_menu');

// Cargar scripts y estilos de la Biblioteca de Medios en la página de configuración
function cambiar_imagen_login_admin_scripts($hook) {
    if ($hook !== 'settings_page_cambiar-imagen-login') {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script('cambiar-imagen-login-script', plugin_dir_url(__FILE__) . 'js/admin-script.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'cambiar_imagen_login_admin_scripts');

// Página de configuración
function cambiar_imagen_login_opciones() {
    ?>
    <div class="wrap">
        <h1>Custom img Login</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('cambiar_imagen_login_opciones_grupo');
            do_settings_sections('cambiar-imagen-login');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Registrar configuraciones
function cambiar_imagen_login_configuraciones() {
    register_setting('cambiar_imagen_login_opciones_grupo', 'login_logo_url');

    add_settings_section(
        'cambiar_imagen_login_seccion',
        'Configuración de la Imagen de Login',
        null,
        'cambiar-imagen-login'
    );

    add_settings_field(
        'login_logo_url',
        'Imagen de Login',
        'cambiar_imagen_login_campo_logo',
        'cambiar-imagen-login',
        'cambiar_imagen_login_seccion'
    );
}
add_action('admin_init', 'cambiar_imagen_login_configuraciones');

// Campo de entrada para la URL de la imagen
function cambiar_imagen_login_campo_logo() {
    $login_logo_url = esc_url( get_option('login_logo_url') );
    ?>
    <input type="hidden" name="login_logo_url" id="login_logo_url" value="<?php echo $login_logo_url; ?>" />
    <div>
        <img id="login_logo_preview" src="<?php echo $login_logo_url; ?>" style="max-width: 320px; height: auto;" />
    </div>
    <input type="button" class="button" value="Seleccionar Imagen" id="upload_image_button" />
    <input type="button" class="button" value="Eliminar Imagen" id="remove_image_button" style="<?php echo ($login_logo_url ? '' : 'display:none;'); ?>" />
    <p class="description">Seleccione una imagen para el logo de inicio de sesión.</p>
    <?php
}
?>
