<?php
/**
 * Rocket Homepage functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Rocket_Homepage
 */

if ( ! defined( 'THEME_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'THEME_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function rocket_homepage_setup() {
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
			'menu-1' => esc_html__( 'Primary', 'rocket-homepage' ),
			'menu-2' => esc_html__( 'Footer Menu 1', 'rocket-homepage' ),
			'menu-3' => esc_html__( 'Footer Menu 2', 'rocket-homepage' ),
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

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support( 'custom-logo' );

}
add_action( 'after_setup_theme', 'rocket_homepage_setup' );


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function rocket_homepage_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'rocket-homepage' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'rocket-homepage' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'rocket_homepage_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function rocket_homepage_scripts() {
	wp_enqueue_script( 'jquery' );

	wp_register_script( 'owl-carousel', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', [], THEME_VERSION, true );
	
	wp_register_script('lightgallery', get_template_directory_uri() . '/assets/js/lightgallery-all.min.js', ['jquery'], THEME_VERSION, true);
	
	wp_enqueue_script( 'script', get_template_directory_uri() . '/assets/js/script.js', ['jquery'], THEME_VERSION, true );

	wp_dequeue_style( 'classic-theme-styles' );
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'wc-blocks-style' );
	

	wp_register_style('owl-carousel-css', get_template_directory_uri() . '/assets/js/owl.carousel.min.css');
	wp_register_style('owl-carousel-theme', get_template_directory_uri() . '/assets/js/owl.theme.default.min.css');

	wp_register_style('lightgallery-min', get_template_directory_uri() . '/assets/css/lightgallery.min.css', [], THEME_VERSION);
	
	wp_enqueue_style( 'theme', get_template_directory_uri() . '/assets/css/style.css', [], THEME_VERSION );

	wp_enqueue_style( 'form', get_template_directory_uri() . '/assets/css/form.css', [], THEME_VERSION );
	
	wp_localize_script('script', 'themeObject', array(
      'ajaxUrl' => admin_url('admin-ajax.php'),
      'nonce'   => wp_create_nonce('Rocket_Homepage'),
      'homeUrl' => home_url('/')
  ));

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'rocket_homepage_scripts' );

function alter_taxonomy_for_post() {
  unregister_taxonomy_for_object_type( 'category', 'post' );
  unregister_taxonomy_for_object_type( 'post_tag', 'post' );
}
add_action( 'init', 'alter_taxonomy_for_post' );

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';



function load_more_posts() {
   $page = (int)$_POST['page'];

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 6,
        'paged' => $page,
				'orderby' => 'date',
				'order'   => 'DESC',
				'post_status' => 'publish'
    );

    $post_list = new WP_Query( $args );


				if ( $post_list->have_posts() ) {
				while ( $post_list->have_posts() ) { $post_list->the_post(); 
				?>
        <div class="blog-wrap-box">
          <div class="blog-wrap-img">
              <?php echo get_the_post_thumbnail( get_the_ID(), 'large' ); ?>
          </div>
          <div class="blog-wrap-text">
              <div class="blog-t">
                  <div class="blog-text-d">
                      <p>By <?php the_author(); ?></p>
                      <svg xmlns="http://www.w3.org/2000/svg" width="6" height="7" viewBox="0 0 6 7" fill="none">
                        <circle cx="3" cy="3.95789" r="3" fill="#00ADEF"/>
                      </svg>
                  <p><span><?php echo get_the_date('M j, Y'); ?></span></p>
                  </div>
                  <h6><?php the_title(); ?></h6>
                  <p><?php echo get_the_excerpt(); ?></p>
              </div>
              <a class="wrap-b-button-main" href="<?php the_permalink(); ?>">mehr Laden</a>
          </div>
        </div> 
			<?php } }

    wp_die();
}

add_action('wp_ajax_load_more_posts', 'load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');