<?php add_filter('safe_svg_optimizer_enabled', '__return_true');
/**
 * Loconuts functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Loconuts
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function loconuts_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Loconuts, use a find and replace
		* to change 'loconuts' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'loconuts', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'loconuts' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'loconuts_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 450,
			'width'       => 450,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'loconuts_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function loconuts_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'loconuts_content_width', 640 );
}
add_action( 'after_setup_theme', 'loconuts_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function loconuts_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'loconuts' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'loconuts' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'loconuts_widgets_init' );




/**
 * Enqueue scripts and styles.
 */
function loconuts_scripts() {
	wp_enqueue_style( 'loconuts-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'loconuts-style', 'rtl', 'replace' );

	wp_enqueue_script( 'loconuts-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'loconuts_scripts' );

/**
 * Enqueue page-main template styles.
 */
function loconuts_enqueue_page_main_styles() {
	if ( is_page_template( 'page-main.php' ) ) {
		wp_enqueue_style( 'page-main-style', get_template_directory_uri() . '/page-main-style.css', array(), _S_VERSION );
		
		// Add inline CSS with custom fonts using PHP
		$font_css = "
@font-face {
  font-family: 'Norwester';
  src: url('" . get_template_directory_uri() . "/fonts/norwester_regular.otf') format('opentype');
  font-weight: normal;
  font-style: normal;
  font-display: swap;
}

@font-face {
  font-family: 'Glacial Indifference';
  src: url('" . get_template_directory_uri() . "/fonts/GlacialIndifference-Regular.otf') format('opentype');
  font-weight: 400;
  font-style: normal;
  font-display: swap;
}

@font-face {
  font-family: 'Glacial Indifference';
  src: url('" . get_template_directory_uri() . "/fonts/GlacialIndifference-Bold.otf') format('opentype');
  font-weight: 700;
  font-style: normal;
  font-display: swap;
}
		";
		wp_add_inline_style( 'page-main-style', $font_css );
	}
}
add_action( 'wp_enqueue_scripts', 'loconuts_enqueue_page_main_styles' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Register "Galerii" custom post type for masonry gallery section.
 */
function loconuts_register_gallery_cpt() {
	$labels = array(
		'name'               => _x( 'Galeriid', 'post type general name', 'loconuts' ),
		'singular_name'      => _x( 'Galerii', 'post type singular name', 'loconuts' ),
		'menu_name'          => _x( 'Galeriid', 'admin menu', 'loconuts' ),
		'name_admin_bar'     => _x( 'Galerii', 'add new on admin bar', 'loconuts' ),
		'add_new'            => _x( 'Lisa uus', 'gallery', 'loconuts' ),
		'add_new_item'       => __( 'Lisa uus galerii', 'loconuts' ),
		'new_item'           => __( 'Uus galerii', 'loconuts' ),
		'edit_item'          => __( 'Muuda galerii', 'loconuts' ),
		'view_item'          => __( 'Vaata galeriid', 'loconuts' ),
		'all_items'          => __( 'Kõik galeriid', 'loconuts' ),
		'search_items'       => __( 'Otsi galeriisid', 'loconuts' ),
		'not_found'          => __( 'Galerii pole leitud.', 'loconuts' ),
		'not_found_in_trash' => __( 'Prügikastis galeriid pole.', 'loconuts' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'has_archive'        => false,
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'thumbnail' ),
		'menu_icon'          => 'dashicons-format-gallery',
		'menu_position'      => 21,
		'rewrite'            => array( 'slug' => 'galerii' ),
	);

	register_post_type( 'gallery', $args );
}
add_action( 'init', 'loconuts_register_gallery_cpt' );


/**
 * Recursively extract attachment IDs from Gutenberg blocks.
 */
if ( ! function_exists( 'loconuts_get_gallery_image_ids' ) ) {
	function loconuts_get_gallery_image_ids( $blocks ) {
		$ids = array();

		foreach ( $blocks as $block ) {
			if ( empty( $block['blockName'] ) ) continue;

			if ( 'core/gallery' === $block['blockName'] && ! empty( $block['attrs']['ids'] ) ) {
				$ids = array_merge( $ids, (array) $block['attrs']['ids'] );
			}

			if ( 'core/image' === $block['blockName'] && ! empty( $block['attrs']['id'] ) ) {
				$ids[] = (int) $block['attrs']['id'];
			}

			if ( ! empty( $block['innerBlocks'] ) ) {
				$ids = array_merge( $ids, loconuts_get_gallery_image_ids( $block['innerBlocks'] ) );
			}
		}

		return $ids;
	}
}


/**
 * Disable Gutenberg if ACF field 'pealkiri' is used.
 */
add_filter('use_block_editor_for_post', function($use_block_editor, $post) {
    $acf_value = get_field('pealkiri', $post->ID);
    if(!empty($acf_value)) return false;
    return $use_block_editor;
}, 10, 2);


/**
 * Redirect "View Gallery" button to /galerii
 */
function loconuts_gallery_redirect_view_link($permalink, $post) {
	if ($post->post_type === 'gallery') {
		return home_url('/galerii');
	}
	return $permalink;
}
add_filter('post_type_link', 'loconuts_gallery_redirect_view_link', 10, 2);

// --- Loo "Tagasiside" custom post type --- //
function create_feedback_cpt() {

    $labels = array(
        'name' => 'Tagasiside',
        'singular_name' => 'Tagasiside',
        'menu_name' => 'Tagasiside',
        'name_admin_bar' => 'Tagasiside',
        'add_new' => 'Lisa uus',
        'add_new_item' => 'Lisa uus tagasiside',
        'edit_item' => 'Muuda tagasisidet',
        'new_item' => 'Uus tagasiside',
        'view_item' => 'Vaata tagasisidet',
        'search_items' => 'Otsi tagasisidet',
        'not_found' => 'Tagasisidet ei leitud',
        'not_found_in_trash' => 'Prügikastis tagasisidet ei leitud',
        'all_items' => 'Kõik tagasiside'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-testimonial',
        'menu_position' => 6,
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'tagasiside'),
    );

    register_post_type('feedback', $args);
}
add_action('init', 'create_feedback_cpt');


// --- Suuna "Vaata tagasisidet" nupul klõpsamisel avalehe ankrule --- //
function feedback_redirect_view_link($permalink, $post) {
    if ($post->post_type === 'feedback') {
        // Muuda linki nii, et viib /#tagasiside
        return home_url('/#tagasiside');
    }
    return $permalink;
}
add_filter('post_type_link', 'feedback_redirect_view_link', 10, 2);

// --- Loo "Videod" custom post type --- //
function create_videos_cpt() {

    $labels = array(
        'name'               => 'Videod',
        'singular_name'      => 'Video',
        'menu_name'          => 'Videod',
        'name_admin_bar'     => 'Video',
        'add_new'            => 'Lisa uus',
        'add_new_item'       => 'Lisa uus video',
        'edit_item'          => 'Muuda videot',
        'new_item'           => 'Uus video',
        'view_item'          => 'Vaata videot',
        'search_items'       => 'Otsi videoid',
        'not_found'          => 'Videoid ei leitud',
        'not_found_in_trash' => 'Prügikastis videoid ei leitud',
        'all_items'          => 'Kõik videod'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => false,
        'menu_icon'          => 'dashicons-video-alt3',
        'menu_position'      => 7,
        'supports'           => array('title', 'editor'),
        'show_in_rest'       => true,
        'rewrite'            => array('slug' => 'videod'),
    );

    register_post_type('videos', $args);
}
add_action('init', 'create_videos_cpt');


// --- Suuna "Vaata videot" nupul klõpsamisel avalehe ankrule --- //
function videos_redirect_view_link($permalink, $post) {
    if ($post->post_type === 'videos') {
        return home_url('/#videod');
    }
    return $permalink;
}
add_filter('post_type_link', 'videos_redirect_view_link', 10, 2);


// --- Loo "Esinemised" custom post type --- //
function create_performances_cpt() {

    $labels = array(
        'name'               => 'Esinemised',
        'singular_name'      => 'Esinemine',
        'menu_name'          => 'Esinemised',
        'name_admin_bar'     => 'Esinemine',
        'add_new'            => 'Lisa uus',
        'add_new_item'       => 'Lisa uus esinemine',
        'edit_item'          => 'Muuda esinemist',
        'new_item'           => 'Uus esinemine',
        'view_item'          => 'Vaata esinemist',
        'search_items'       => 'Otsi esinemisi',
        'not_found'          => 'Esinemisi ei leitud',
        'not_found_in_trash' => 'Prügikastis esinemisi ei leitud',
        'all_items'          => 'Kõik esinemised'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => false,
        'menu_icon'          => 'dashicons-calendar-alt',
        'menu_position'      => 8,
        'supports'           => array('title', 'editor'),
        'show_in_rest'       => true,
        'rewrite'            => array('slug' => 'esinemised'),
    );

    register_post_type('performances', $args);
}
add_action('init', 'create_performances_cpt');


// --- Lisa custom väljad --- //
function add_performances_meta_boxes() {
    add_meta_box(
        'performances_details',
        'Esinemise andmed',
        'render_performances_meta_box',
        'performances',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_performances_meta_boxes');

function render_performances_meta_box($post) {
    wp_nonce_field('save_performance_data', 'performance_nonce');

    $date = get_post_meta($post->ID, '_performance_date', true);
    $place = get_post_meta($post->ID, '_performance_place', true);
    $info = get_post_meta($post->ID, '_performance_info', true);
    ?>
    <p>
        <label for="performance_date"><strong>Kuupäev:</strong></label><br>
        <input type="date" id="performance_date" name="performance_date" value="<?php echo esc_attr($date); ?>" />
    </p>
    <p>
        <label for="performance_place"><strong>Koht:</strong></label><br>
        <input type="text" id="performance_place" name="performance_place" value="<?php echo esc_attr($place); ?>" />
    </p>
    <p>
        <label for="performance_info"><strong>Lisainfo (link):</strong></label><br>
        <input type="text" id="performance_info" name="performance_info" value="<?php echo esc_attr($info); ?>" />
    </p>
    <?php
}


// --- Salvesta custom väljad --- //
function save_performances_meta($post_id) {
    if (!isset($_POST['performance_nonce']) || !wp_verify_nonce($_POST['performance_nonce'], 'save_performance_data')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['performance_date'])) {
        update_post_meta($post_id, '_performance_date', sanitize_text_field($_POST['performance_date']));
    }
    if (isset($_POST['performance_place'])) {
        update_post_meta($post_id, '_performance_place', sanitize_text_field($_POST['performance_place']));
    }
    if (isset($_POST['performance_info'])) {
        update_post_meta($post_id, '_performance_info', esc_url_raw($_POST['performance_info']));
    }
}
add_action('save_post', 'save_performances_meta');


// --- Suuna "Vaata" nupul klõpsamisel avalehe #esinemised sektsiooni --- //
function performances_redirect_view_link($permalink, $post) {
    if ($post->post_type === 'performances') {
        return home_url('/#esinemised');
    }
    return $permalink;
}
add_filter('post_type_link', 'performances_redirect_view_link', 10, 2);




