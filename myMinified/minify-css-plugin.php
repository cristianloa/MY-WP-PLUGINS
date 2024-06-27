<?php
/*
Plugin Name: Minify CSS Plugin 😾
Description: Minify CSS Plugin WordPress: Plugin para minificar y reemplazar CSS en tu tema de WordPress.
Version: 1.0
Author: CristianLoa 😾
Author URI: https://www.facebook.com/CristianLoaH
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Minify_CSS_Plugin {

    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_minified_css'));
    }

    public function enqueue_minified_css() {
        $original_css = get_template_directory() . '/style.css';
        $minified_css = get_template_directory() . '/style.min.css';

        if (file_exists($original_css) && (!file_exists($minified_css) || filemtime($original_css) > filemtime($minified_css))) {
            $css_content = file_get_contents($original_css);
            $first_comment = $this->get_first_comment($css_content);
            $minified_css_content = $this->minify_css(str_replace($first_comment, '', $css_content));
            $final_css_content = $first_comment . "\n" . $minified_css_content;
            file_put_contents($minified_css, $final_css_content);
        }

        wp_enqueue_style('minified-style', get_template_directory_uri() . '/style.min.css', array(), null);
    }

    private function get_first_comment($css) {
        preg_match('/\/\*.*?\*\//s', $css, $matches);
        return isset($matches[0]) ? $matches[0] : '';
    }

    private function minify_css($css) {
        $css = preg_replace('/\/\*.*?\*\//s', '', $css); // Elimina comentarios
        $css = preg_replace('/\s*([{}|:;,])\s+/', '$1', $css); // Elimina espacios alrededor de {}|:;,
        $css = preg_replace('/\s\s+(?![^\{]*\})/', ' ', $css); // Elimina espacios en blanco adicionales
        $css = preg_replace('/\n\s*\n/', "\n", $css); // Elimina líneas en blanco
        $css = preg_replace('/\n\s*/', '', $css); // Elimina saltos de línea
        return $css;
    }
}

new Minify_CSS_Plugin();
