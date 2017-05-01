<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class to manage the custom taxonomies.
 *
 *
 * @since      1.0.0
 * @package    AlquemieAudience
 * @author     Alquemie <plugins@alquemie.net>
 */
class Alquemie_Audience_Taxonomies {
	/**
	 * The class instance.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      object    $instance    instance of Alquemie_Audience_Segments class.
	 */
	private static $instance = null;

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
		add_action( 'init', array($this, 'create_segment_taxonomy'), 0 );
    add_action( 'init', array($this, 'create_journey_taxonomy'), 0 );
		add_action( 'init', array($this, 'create_div_taxonomy'), 0 );
    add_action( 'init', array($this, 'create_subj_taxonomy'), 0 );
	}

	public static function activate() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
  public function create_segment_taxonomy() {
    if (!taxonomy_exists('audience-segment')) {
      $labels = array(
      	'name'                       => _x( 'Audience Segments', 'Taxonomy General Name', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'singular_name'              => _x( 'Audience Segment', 'Taxonomy Singular Name', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'menu_name'                  => __( 'Audience Segements', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'all_items'                  => __( 'All Audience Segements', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'parent_item'                => __( 'Parent Audience Segement', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'parent_item_colon'          => __( 'Parent Audience Segement:', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'new_item_name'              => __( 'New Segement Name', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'add_new_item'               => __( 'Add New Audience Segement', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'edit_item'                  => __( 'Edit Audience Segement', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'update_item'                => __( 'Update Audience Segement', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'view_item'                  => __( 'View Audience Segement', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'separate_items_with_commas' => __( 'Separate items with commas', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'add_or_remove_items'        => __( 'Add or remove Audience Segements', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'choose_from_most_used'      => __( 'Choose from the most used', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'popular_items'              => __( 'Popular Audience Segements', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'search_items'               => __( 'Search Audience Segments', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'not_found'                  => __( 'Not Found', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'no_terms'                   => __( 'No Audience Segements', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'items_list'                 => __( 'Audience Segements list', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'items_list_navigation'      => __( 'Items list navigation', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      );

      $args = array(
        'labels'                     => $labels,
      	'hierarchical'               => false,
      	'public'                     => true,
      	'show_ui'                    => true,
      	'show_admin_column'          => false,
      	'show_in_nav_menus'          => false,
      	'show_tagcloud'              => false,
        'show_in_quick_edit'        => false,
        'meta_box_cb' => false,
      	'rewrite'                    => false,
      );

      register_taxonomy( 'audience-segment', null, $args );
    }
  }

	public function create_div_taxonomy() {
    if (!taxonomy_exists('audience-division')) {
      $labels = array(
      	'name'                       => _x( 'Content Division', 'Taxonomy General Name', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'singular_name'              => _x( 'Content Division', 'Taxonomy Singular Name', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'menu_name'                  => __( 'Content Division', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'all_items'                  => __( 'All Content Divisions', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'parent_item'                => __( 'Parent Division', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'parent_item_colon'          => __( 'Parent Division:', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'new_item_name'              => __( 'New Division Name', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'add_new_item'               => __( 'Add New Division', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'edit_item'                  => __( 'Edit Division', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'update_item'                => __( 'Update Division', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'view_item'                  => __( 'View Division ', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'separate_items_with_commas' => __( 'Separate items with commas', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'add_or_remove_items'        => __( 'Add or remove Divisions', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'choose_from_most_used'      => __( 'Choose from the most used', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'popular_items'              => __( 'Popular Divisions', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'search_items'               => __( 'Search Content Divisions', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'not_found'                  => __( 'Not Found', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'no_terms'                   => __( 'No Divisions', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'items_list'                 => __( 'Content Division list', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'items_list_navigation'      => __( 'Items list navigation', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      );

      $args = array(
        'labels'                     => $labels,
      	'hierarchical'               => false,
      	'public'                     => true,
      	'show_ui'                    => true,
      	'show_admin_column'          => false,
      	'show_in_nav_menus'          => false,
      	'show_tagcloud'              => false,
        'show_in_quick_edit'        => false,
        'meta_box_cb' => false,
      	'rewrite'                    => false,
      );

      register_taxonomy( 'audience-division', null, $args );
    }
  }

	public function create_subj_taxonomy() {
    if (!taxonomy_exists('audience-subject')) {
      $labels = array(
      	'name'                       => _x( 'Subject Matter', 'Taxonomy General Name', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'singular_name'              => _x( 'Subject Matter', 'Taxonomy Singular Name', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'menu_name'                  => __( 'Subject Matter', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'all_items'                  => __( 'All Subject Matters', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'parent_item'                => __( 'Parent Subject Matter', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'parent_item_colon'          => __( 'Parent Subject Matter:', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'new_item_name'              => __( 'New Subject Matter', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'add_new_item'               => __( 'Add New Subject Matter', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'edit_item'                  => __( 'Edit Subject Matter', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'update_item'                => __( 'Update Subject Matter', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'view_item'                  => __( 'View Subject Matter', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'separate_items_with_commas' => __( 'Separate items with commas', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'add_or_remove_items'        => __( 'Add or remove Subject Matters', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'choose_from_most_used'      => __( 'Choose from the most used', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'popular_items'              => __( 'Popular Subject Matters', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'search_items'               => __( 'Search Subject Matters', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'not_found'                  => __( 'Not Found', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'no_terms'                   => __( 'No Audience Segements', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'items_list'                 => __( 'Subject Matter list', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      	'items_list_navigation'      => __( 'Items list navigation', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      );

			$capabilities = array(
        'manage_terms' => 'manage_categories',
        'edit_terms' => 'manage_categories',
        'delete_terms' => 'manage_categories',
        'assign_terms' => 'edit_posts'
      );

      $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => false,
        'show_in_nav_menus'          => false,
        'show_tagcloud'              => false,
        'show_in_quick_edit'        => 	true,
        'meta_box_cb' => false,
        'rewrite'                    => false,
      );

      register_taxonomy( 'audience-subject', null, $args );
    }
  }

  public function create_journey_taxonomy() {

    if (!taxonomy_exists('audience-journey')) {

      $labels = array(
        'name'                       => _x( 'Buying Phase',ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
        'singular_name'              => _x( 'Buying Phase', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
        'menu_name'                  => __( 'Buying Phases', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
        'all_items'                  => __( 'All Buying Phases', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
        'parent_item'                => __( 'Parent Buying Phase', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
        'parent_item_colon'          => __( 'Parent Buying Phase:', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ),
      );

      $capabilities = array(
        'manage_terms' => '',
        'edit_terms' => '',
        'delete_terms' => '',
        'assign_terms' => 'edit_posts'
      );

      $args = array(
        'labels'                     => $labels,
        'capabilities'               => $capabilities,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => false,
        'show_admin_column'          => false,
        'show_in_nav_menus'          => false,
        'show_tagcloud'              => false,
        'show_in_quick_edit'        => 	true,
        'meta_box_cb' => false,
        'rewrite'                    => false,
      );

      register_taxonomy( 'audience-journey', null, $args );

      // Insert Buyer's Journey Terms
      wp_insert_term('Awareness', 'audience-journey', array('slug' => 'awareness') );
      wp_insert_term('Consideration', 'audience-journey', array('slug' => 'consideration'));
      wp_insert_term('Decision', 'audience-journey', array('slug' => 'decision'));
      wp_insert_term('Loyalty|Advocacy', 'audience-journey', array('slug' => 'loyalty'));
     }
  }

}
