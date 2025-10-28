<?php
/**
 * Loconuts Theme Customizer
 *
 * @package Loconuts
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function loconuts_customize_register( $wp_customize ) {
    // Live preview transport
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector'        => '.site-title a',
                'render_callback' => 'loconuts_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector'        => '.site-description',
                'render_callback' => 'loconuts_customize_partial_blogdescription',
            )
        );
    }
}
add_action( 'customize_register', 'loconuts_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 */
function loconuts_customize_partial_blogname() {
    bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 */
function loconuts_customize_partial_blogdescription() {
    bloginfo( 'description' );
}

/**
 * Customizer preview JS.
 */
function loconuts_customize_preview_js() {
    wp_enqueue_script(
        'loconuts-customizer',
        get_template_directory_uri() . '/js/customizer.js',
        array( 'customize-preview' ),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action( 'customize_preview_init', 'loconuts_customize_preview_js' );


/* ----------------------------------------------------------------
   Kontaktandmete sektsioon Customizeris
---------------------------------------------------------------- */
// functions.php
function loconuts_kontakt_customize_register($wp_customize) {

    // Sektsioon kontaktandmetele
    $wp_customize->add_section('kontakt_info_section', array(
        'title'    => __('Kontaktandmed', 'loconuts'),
        'priority' => 30,
    ));

    // Email
    $wp_customize->add_setting('kontakt_email', array(
        'default'           => 'kontakt@loconuts.ee',
        'sanitize_callback' => 'sanitize_email',
    ));
    $wp_customize->add_control('kontakt_email', array(
        'label'   => __('Email', 'loconuts'),
        'section' => 'kontakt_info_section',
        'type'    => 'email',
    ));

    // Telefon
    $wp_customize->add_setting('kontakt_phone', array(
        'default'           => '+37255512345',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('kontakt_phone', array(
        'label'   => __('Telefon', 'loconuts'),
        'section' => 'kontakt_info_section',
        'type'    => 'text',
    ));
}
add_action('customize_register', 'loconuts_kontakt_customize_register');

function gold_footer_customize_register($wp_customize) {

    // Footer sotsiaalmeedia sektsioon
    $wp_customize->add_section('gold_footer_social', array(
        'title' => __('Footer Social Media', 'loconuts'),
        'priority' => 30,
    ));

    $social_platforms = ['instagram', 'facebook', 'youtube'];

    foreach ($social_platforms as $platform) {
        // Link
        $wp_customize->add_setting("gold_footer_{$platform}_link", array(
            'default' => '#',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control("gold_footer_{$platform}_link", array(
            'label' => ucfirst($platform) . ' link',
            'section' => 'gold_footer_social',
            'type' => 'url',
        ));

        // Ikon (SVG pildi URL)
        $wp_customize->add_setting("gold_footer_{$platform}_icon", array(
            'default' => get_template_directory_uri() . "/assets/icons/{$platform}.svg",
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control(new WP_Customize_Image_Control(
            $wp_customize,
            "gold_footer_{$platform}_icon",
            array(
                'label' => ucfirst($platform) . ' icon',
                'section' => 'gold_footer_social',
                'settings' => "gold_footer_{$platform}_icon",
            )
        ));
    }

}
add_action('customize_register', 'gold_footer_customize_register');

function loconuts_header_socials_customize_register($wp_customize) {
    // Headeri sotsiaalmeedia sektsioon
    $wp_customize->add_section('loconuts_header_socials', array(
        'title' => __('Header Social Media', 'loconuts'),
        'priority' => 25,
    ));

    $social_platforms = ['instagram', 'facebook', 'youtube'];

    foreach ($social_platforms as $platform) {
        // Link
        $wp_customize->add_setting("loconuts_header_{$platform}_link", array(
            'default' => '#',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control("loconuts_header_{$platform}_link", array(
            'label' => ucfirst($platform) . ' link',
            'section' => 'loconuts_header_socials',
            'type' => 'url',
        ));

        // Ikooni pildi fail
        $wp_customize->add_setting("loconuts_header_{$platform}_icon", array(
            'default' => get_template_directory_uri() . "/assets/icons/{$platform}.svg",
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "loconuts_header_{$platform}_icon", array(
            'label' => ucfirst($platform) . ' icon',
            'section' => 'loconuts_header_socials',
            'settings' => "loconuts_header_{$platform}_icon",
        )));
    }
}
add_action('customize_register', 'loconuts_header_socials_customize_register');
