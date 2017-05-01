<?php

/**
 *
 * @link              https://www.alquemie.net
 * @since             1.0.0
 * @package           AlquemieAudience
 *
 * @wordpress-plugin
 * Plugin Name:       Audience Segment Taxonomies
 * Plugin URI:        https://www.alquemie.net/plugins/audience/
 * Description:       Adds the ability to tag and track content based on Audience Segments and Buyer Journey
 * Version:           1.1.0
 * Author:            Alquemie
 * Author URI:        https://www.alquemie.net
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       audience
 * Domain Path:       /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'ALQUEMIE_AUDIENCE_VERSION', '1.1.0' );
define( 'ALQUEMIE_AUDIENCE_DB_VERSION', '1' );
define( 'ALQUEMIE_AUDIENCE_TEXT_DOMAIN', 'audience' );

require_once( dirname( __FILE__ ) . '/inc/class-audience-taxonomies.php' );

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Alquemie_Audience_Segments
 * @author     Alquemie <plugins@alquemie.net>
 */
class Alquemie_Audience_Segments {

	/**
	 * The class instance.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      object    $instance    instance of Alquemie_Audience_Segments class.
	 */
	private static $instance = null;

	/**
	 * The plugin options.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      object    $options    Array of site defined plugin options.
	 */
	private $options;


	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The menu title of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $menu_title    The string used for the menu title of this plugin.
	 */
	protected $menu_title;

	/**
	 * The menu title of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $field_names    The string used for the menu title of this plugin.
	 */
	protected $field_names = array();

	/**
	 * The page title of the plugin options page.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $option_page_title    The string used for the page title of this plugin.
	 */
	protected $option_page_title;

	/**
	 * The page title of the plugin options page.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $option_page_title    The string used for the page title of this plugin.
	 */
	protected $division_page_title;

	/**
	 * The page title of the plugin options page.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $option_page_title    The string used for the page title of this plugin.
	 */
	protected $subjectmatter_page_title;

	/**
	 * The page title of the plugin options page.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $option_page_title    The string used for the page title of this plugin.
	 */
	protected $audience_page_title;

	public static function activate() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	private function __construct() {
		$this->plugin_name = 'audience';
		$this->menu_title = __('Audience Settings', ALQUEMIE_AUDIENCE_TEXT_DOMAIN );
		$this->option_page_title = __('Audience Segment Options', ALQUEMIE_AUDIENCE_TEXT_DOMAIN );
		$this->division_page_title = __('Division', ALQUEMIE_AUDIENCE_TEXT_DOMAIN );
		$this->subjectmatter_page_title = __('Subject Matter', ALQUEMIE_AUDIENCE_TEXT_DOMAIN );
		$this->audience_page_title = __('Segments', ALQUEMIE_AUDIENCE_TEXT_DOMAIN );
		$this->options = get_option( 'alquemie_audience_options', array() );
		$this->field_names = array (
			'division' => __('Division', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
			'subject' => __('Subject Matter', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
			'journey' => __('Buying Stage', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
			'audience1' => __('Primary Audience', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
			'audience2' => __('Secondary Audience', ALQUEMIE_AUDIENCE_TEXT_DOMAIN )
		);

		// If it looks like first run, check compatibility
		if( empty( $this->options ) ) {
			$this->check_compatibility();
		}

		// Upgrade DB if necessary
		$this->check_db_upgrades();

		// Activate taxonomies
		$taxes = Alquemie_Audience_Taxonomies::activate();
		$this->options['taxonomies'] = array('audience-division','audience-subject','audience-journey','audience-segment');

		$this->init_filters();
	}

	public function init_filters() {
		if( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

		// These can happen later
		//add_action( 'plugins_loaded', array( $this, 'register_text_domain' ) );
		add_action( 'wp_loaded', array( $this, 'init_wploaded_filters' ) );
	}

	private function check_compatibility() {
		if ( version_compare( $GLOBALS['wp_version'], '3.8', '<' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			deactivate_plugins( __FILE__ );
			if ( isset( $_GET['action'] ) && ( $_GET['action'] == 'activate' || $_GET['action'] == 'error_scrape' ) ) {
				exit( sprintf( __( 'Alquemie Audience requires WordPress version %s or greater.', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ), '3.8' ) );
			}
		}
	}

	private function check_db_upgrades() {
		$old_ver = isset( $this->options['db_version'] ) ? $this->options['db_version'] : 0;
		if( $old_ver < ALQUEMIE_AUDIENCE_DB_VERSION ) {
			// Insert any future DB changes here

			$this->options['db_version'] = ALQUEMIE_AUDIENCE_DB_VERSION;
			$this->update_options();
		}

	}

	private function update_options() {
		update_option( 'alquemie_audience_options', $this->options );
		// $this->refresh_taxonomy();
	}

	public function register_text_domain() {
		load_plugin_textdomain( ALQUEMIE_AUDIENCE_TEXT_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) .  '/lang' );
	}

	public function init_wploaded_filters(){
		// Filters for the admin only
		if( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'settings_menu' ) );
			add_filter( 'plugin_row_meta', array( $this, 'set_plugin_meta' ), 10, 2 );
			add_action( 'plugins_loaded', array( $this, 'refresh_taxonomy'));
			add_action( 'parent_file', array($this, 'menu_highlight' ) );
		}
		// Filters for front end only
		else {
			add_action('wp_head', array($this,'push_to_dataLayer'), 20);
		}
	}

	private function refresh_taxonomy() {
		$types = get_post_types( $typeargs, 'objects' );
		foreach( array_keys( $types ) as $type ) {
			if( $this->is_post_type_enabled( $type ) ) {	// the type doesn't support comments anyway
				register_taxonomy_for_object_type( 'audience-journey', $type->name );
				register_taxonomy_for_object_type( 'audience-subject', $type->name );
				register_taxonomy_for_object_type( 'audience-division', $type->name );
				register_taxonomy_for_object_type( 'audience-segment', $type->name );
			} elseif (post_type_supports( $type, 'editor' )) {
				unregister_taxonomy_for_object_type( 'audience-division', $type->name );
				unregister_taxonomy_for_object_type( 'audience-subject', $type->name );
				unregister_taxonomy_for_object_type( 'audience-journey', $type->name );
				unregister_taxonomy_for_object_type( 'audience-segment', $type->name );
			}
		}
	}
	/*
	 * Get an array of enabled post type.
	 */
	private function get_enabled_post_types() {
		$types = $this->options['audience_post_types'];

		return $types;
	}

	public function set_plugin_meta( $links, $file ) {
		static $plugin;
		$plugin = plugin_basename( __FILE__ );
		if ( $file == $plugin ) {
			$links[] = '<a href="https://github.com/Alquemie/audience">GitHub</a>';
		}
		return $links;
	}

	/*
	 * Check whether comments have been enabled on a given post type.
	 */
	private function is_post_type_enabled( $type ) {
		return in_array( $type, $this->get_enabled_post_types() );
	}

	public function settings_menu() {

		add_menu_page( $this->option_page_title, $this->menu_title, 'manage_options', 'alquemie_audience_settings', array( $this, 'settings_page' ), 'dashicons-groups', 100  );
		add_submenu_page( 'alquemie_audience_settings', $this->division_page_title, $this->division_page_title, 'manage_options', 'edit-tags.php?taxonomy=audience-division' );
		add_submenu_page( 'alquemie_audience_settings', $this->subjectmatter_page_title, $this->subjectmatter_page_title, 'manage_options',  'edit-tags.php?taxonomy=audience-subject' );
		add_submenu_page( 'alquemie_audience_settings', $this->audience_page_title,  $this->audience_page_title, 'manage_options',  'edit-tags.php?taxonomy=audience-segment' );
		// add_submenu_page( 'options-general.php', $title, $title, 'manage_options', 'alquemie_audience_settings', array( $this, 'settings_page' ) );
	}

	public function menu_highlight( $parent_file ) {
      global $current_screen;

      $taxonomy = $current_screen->taxonomy;
      if ( in_array($taxonomy, $this->options['taxonomies'] ) ) {
          $parent_file = 'alquemie_audience_settings';
      }

      return $parent_file;
  }

	public function settings_page() {
		include dirname( __FILE__ ) . '/inc/settings-page.php';
	}

  public function init_metabox() {
		add_action( 'add_meta_boxes',        array( $this, 'add_metabox' )         );
		add_action( 'save_post',             array( $this, 'save_metabox' ), 10, 2 );
	}

	public function add_metabox() {

		if (! empty($this->options['audience_post_types']) ) {
			add_meta_box(
				'alquemie-audience',
				__( $this->menu_title, ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
				array( $this, 'render_audience_mb' ),
				$this->options['audience_post_types'],
				'side',
				'default'
			);
		}
	}

	private function get_default_value ( $option ) {
		if (! empty($this->options['default_audience'][$option])) {
			return $this->options['default_audience'][$option];
		} else {
			return '';
		}
	}
	public function render_audience_mb( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'alqnonce_action', 'alqnonce' );

		// Retrieve an existing value from the database.
		$division = get_post_meta( $post->ID, 'alq_audience_division', true );
		$subject = get_post_meta( $post->ID, 'alq_audience_subject', true );
		$buyerstage = get_post_meta( $post->ID, 'alq_audience_buyerstage', true );
		$primaryaudience = get_post_meta( $post->ID, 'alq_audience_audience1', true );
		$secondaryaudience = get_post_meta( $post->ID, 'alq_audience_audience2', true );

		// Set default values.
		if( empty( $division ) ) $division = $this->get_default_value('division');
		if( empty( $subject ) ) $subject = $this->get_default_value('subject');
		if( empty( $buyerstage ) ) $buyerstage = $this->get_default_value('journey');
		if( empty( $primaryaudience ) ) $primaryaudience = $this->get_default_value('segment');
		if( empty( $secondaryaudience ) ) $secondaryaudience = '';

		echo '  <p class="post-attributes-label-wrapper"><label class="post-audience-label" for="alq_division">' .  $this->field_names['division'] . '</label></p>';
		wp_dropdown_categories( array( 'id' => 'alq_division', 'name' => 'alq_division', 'class' => 'alq_audience_field', 'selected' => $division, 'taxonomy' => 'audience-division', 'hide_empty' => false, 'option_none_value' => '', 'show_option_none' => ' - DEFAULT - ' ) );

		echo '  <p class="post-attributes-label-wrapper"><label class="post-audience-label" for="alq_subject">' .  $this->field_names['subject'] . '</label></p>';
		wp_dropdown_categories( array( 'id' => 'alq_subject', 'name' => 'alq_subject', 'class' => 'alq_audience_field', 'selected' => $subject, 'taxonomy' => 'audience-subject', 'hide_empty' => false, 'option_none_value' => '', 'show_option_none' => ' - DEFAULT - ' ) );

		echo '  <p class="post-attributes-label-wrapper"><label class="post-audience-label" for="alq_buyerstage">' .  $this->field_names['journey'] . '</label></p>';
		wp_dropdown_categories( array( 'id' => 'alq_buyerstage', 'name' => 'alq_buyerstage', 'class' => 'alq_audience_field', 'selected' => $buyerstage, 'taxonomy' => 'audience-journey', 'hide_empty' => false, 'option_none_value' => '', 'show_option_none' => ' - DEFAULT - ' ) );

		echo '  <p class="post-attributes-label-wrapper"><label class="post-audience-label" for="alq_primaryaudience">' .  $this->field_names['audience1'] . '</label></p>';
		wp_dropdown_categories( array( 'id' => 'alq_primaryaudience', 'name' => 'alq_primaryaudience', 'class' => 'alq_audience_field', 'selected' => $primaryaudience,  'taxonomy' => 'audience-segment', 'hide_empty' => false, 'option_none_value' => '', 'show_option_none' => ' - DEFAULT - ') );

		// echo '<div class="inside">';
		echo '  <p class="post-attributes-label-wrapper"><label class="post-audience-label" for="alq_secondaryaudience">' .  $this->field_names['audience2'] . '</label></p>';
		wp_dropdown_categories( array( 'id' => 'alq_secondaryaudience', 'name' => 'alq_secondaryaudience', 'class' => 'alq_audience_field', 'selected' => $secondaryaudience, 'taxonomy' => 'audience-segment', 'hide_empty' => false, 'option_none_value' => '', 'show_option_none' => ' - NONE - ' ) );
		// echo '</div>';

	}

	public function save_metabox( $post_id, $post ) {

		// Add nonce for security and authentication.
		$nonce_name   = isset( $_POST['alqnonce'] ) ? $_POST['alqnonce'] : '';
		$nonce_action = 'alqnonce_action';

		// Check if a nonce is set.
		if ( ! isset( $nonce_name ) )
			return;

		// Check if a nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
			return;

		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) )
			return;

		// Sanitize user input.
		$alqnew_div = isset( $_POST[ 'alq_division' ] ) ? sanitize_text_field( $_POST[ 'alq_division' ] ) : '';
		$alqnew_subject = isset( $_POST[ 'alq_subject' ] ) ? sanitize_text_field( $_POST[ 'alq_subject' ] ) : '';
		$alqnew_buyerstage = isset( $_POST[ 'alq_buyerstage' ] ) ? sanitize_text_field( $_POST[ 'alq_buyerstage' ] ) : '';
		$alqnew_primaryaudience= isset( $_POST[ 'alq_primaryaudience' ] ) ? sanitize_text_field( $_POST[ 'alq_primaryaudience' ] ) : '';
		$alqnew_secondaryaudience = isset( $_POST[ 'alq_secondaryaudience' ] ) ? sanitize_text_field( $_POST[ 'alq_secondaryaudience' ] ) : '';
    $audIDs = array($alqnew_primaryaudience,$alqnew_secondaryaudience );
    $audIDs = array_map( 'intval', $audIDs );
    $audIDs = array_unique( $audIDs );

		// Update the meta field in the database.
		wp_set_object_terms( $post_id, null, 'audience-division' );
		wp_set_object_terms( $post_id, null, 'audience-subject' );
		wp_set_object_terms( $post_id, null, 'audience-journey' );
    wp_set_object_terms( $post_id, null, 'audience-segment' );
		wp_set_object_terms( $post_id, intval($alqnew_div), 'audience-division' );
		wp_set_object_terms( $post_id, intval($alqnew_subject), 'audience-subject' );
    wp_set_object_terms( $post_id, intval($alqnew_buyerstage), 'audience-journey' );
    wp_set_object_terms( $post_id, $audIDs, 'audience-segment' );

		update_post_meta( $post_id, 'alq_audience_division', $alqnew_div );
		update_post_meta( $post_id, 'alq_audience_subject', $alqnew_subject );
		update_post_meta( $post_id, 'alq_audience_buyerstage', $alqnew_buyerstage );
		update_post_meta( $post_id, 'alq_audience_audience1', $alqnew_primaryaudience );
		update_post_meta( $post_id, 'alq_audience_audience2', $alqnew_secondaryaudience );

	}

  function push_to_dataLayer(){
    global $wp_query;
    $postid = $wp_query->post->ID;
    if ($postid) {
			$division = get_post_meta( $postid, 'alq_audience_division', true );
			$taxVal = ($division) ? get_term_by('id', $division, 'audience-division') : '';
			$division = (!empty($taxVal->name)) ? $taxVal->name : $this->get_default_value('division');

			$subject = get_post_meta( $postid, 'alq_audience_subject', true );
			$taxVal = ($subject) ? get_term_by('id', $subject, 'audience-subject') : '';
			$subject = (!empty($taxVal->name)) ? $taxVal->name : $this->get_default_value('subject');

      $buyerstage = get_post_meta( $postid, 'alq_audience_buyerstage', true );
			$taxVal = ($buyerstage) ? get_term_by('id', $buyerstage, 'audience-journey') : '';
			$buyerstage = (!empty($taxVal->name)) ? $taxVal->name : $this->get_default_value('journey');

  		$primaryaudience = get_post_meta( $postid, 'alq_audience_audience1', true );
			$taxVal = ($primaryaudience) ? get_term_by('id', $primaryaudience, 'audience-segment') : '';
			$primaryaudience = (!empty($taxVal->name)) ? $taxVal->name : $this->get_default_value('segment');

  		$secondaryaudience = get_post_meta( $postid, 'alq_audience_audience2', true );
			$taxVal = ($secondaryaudience) ? get_term_by('id', $secondaryaudience, 'audience-segment') : '';
			$secondaryaudience = (!empty($taxVal->name)) ? $taxVal->name : '';

			$dataLayer = (!empty($buyerstage)) ? '"pageBuyerJourney":"' . $buyerstage .'"' : '"pageBuyerJourney":"Awareness"';
			$dataLayer .= (!empty($division)) ? ',' . '"pageDivision":"' . $division .'"' : '';
			$dataLayer .= (!empty($subject)) ? ',' . '"pageSubjectMatter":"' . $subject .'"' : '';
			$dataLayer .= (!empty($primaryaudience)) ? ',' . '"pageAudience":"' . $primaryaudience .'"' : '';
      $dataLayer .= (!empty($secondaryaudience)) ? ',' . '"pageSecondAudience":"' . $secondaryaudience .'"' : '';

      $script = '<script>';
      $script .= "if (typeof dataLayer !== 'undefined') { dataLayer.push({" . $dataLayer ."}); }" . PHP_EOL;
      $script .= '</script>';
      // $script .= PHP_EOL . '<!-- ' . print_r($taxBuyerStage,true) . ' --->' . PHP_EOL;
      echo $script;
    }
  }
}

Alquemie_Audience_Segments::activate();
