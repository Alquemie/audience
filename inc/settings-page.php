<?php
if( !defined( 'ABSPATH' ) ) {
	exit;
}

$typeargs = array( 'public' => true );
$types = get_post_types( $typeargs, 'objects' );
foreach( array_keys( $types ) as $type ) {
	if( ! in_array( $type, $this->types_enabled ) && ! post_type_supports( $type, 'editor' ) )	// the type doesn't support comments anyway
		unset( $types[$type] );
}

if ( isset( $_POST['submit'] ) ) {
	check_admin_referer( 'alquemie-audience-admin' );

	$enabled_post_types =  empty( $_POST['enabled_types'] ) ? array() : (array) $_POST['enabled_types'];
	$enabled_post_types = array_intersect( $enabled_post_types, array_keys( $types ) );
	$this->options['audience_post_types'] = $enabled_post_types;

	$this->options['default_audience'] = array( 'division' => sanitize_text_field($_POST['default-audience-division']),
		'subject' => sanitize_text_field($_POST['default-audience-subject']),
		'journey' => sanitize_text_field($_POST['default-audience-journey']),
		'segment' => sanitize_text_field($_POST['default-audience-segment'] )
	);

	// $this->init_metabox();
	$this->update_options();
	$cache_message = WP_CACHE ? ' <strong>' . __( 'If a caching/performance plugin is active, please invalidate its cache to ensure that changes are reflected immediately.' ) . '</strong>' : '';
	echo '<div id="message" class="updated"><p>' . __( 'Options updated. Changes to the Admin Menu and Admin Bar will not appear until you leave or reload this page.', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ) . $cache_message . '</p></div>';
}

$division = (! empty($this->options['default_audience'])) ? $this->options['default_audience']['division'] : '';
$subject = (! empty($this->options['default_audience'])) ? $this->options['default_audience']['subject'] : '';
$journey = (! empty($this->options['default_audience'])) ? $this->options['default_audience']['journey'] : 'Awareness';
$segment = (! empty($this->options['default_audience'])) ? $this->options['default_audience']['segment'] : '';

?>
<style> .indent {padding-left: 2em} </style>
<div class="wrap">
<h1><?php _e( $this->option_page_title, ALQUEMIE_AUDIENCE_TEXT_DOMAIN) ?></h1>
<?php
if( WP_CACHE )
	echo '<div class="updated"><p>' . __( "It seems that a caching/performance plugin is active on this site. Please manually invalidate that plugin's cache after making any changes to the settings below.", ALQUEMIE_AUDIENCE_TEXT_DOMAIN) . '</p></div>';
?>
<form action="" method="post" id="alquemie-audience">
<ul>

<li><label for="selected_types"><strong><?php _e( 'Enable Audience Segments for the following Post Types', ALQUEMIE_AUDIENCE_TEXT_DOMAIN) ?></strong>:</label>
	<p></p>
	<ul class="indent" id="listoftypes">
		<?php foreach( $types as $k => $v ) echo "<li><label for='post-type-$k'><input type='checkbox' name='enabled_types[]' value='$k' ". checked( in_array( $k, $this->options['audience_post_types'] ), true, false ) ." id='post-type-$k'> {$v->labels->name}</label></li>";?>
	</ul>
</li>
</ul>
<h2>Default Values</h2>
<p><label for='default-audience-division'><strong><?php _e('Division', ALQUEMIE_AUDIENCE_TEXT_DOMAIN); ?>:</strong></label>
<?php
wp_dropdown_categories( array( 'id' => 'default-audience-division', 'name' => 'default-audience-division', 'class' => 'audience_select', 'selected' => $division, 'taxonomy' => 'audience-division', 'hide_empty' => false, 'option_none_value' => '', 'show_option_none' => ' - NONE - ' ) );
?></p>
<p><label for='default-audience-subject'><strong><?php _e('Subject Matter', ALQUEMIE_AUDIENCE_TEXT_DOMAIN); ?>:</strong></label>
<?php
wp_dropdown_categories( array( 'id' => 'default-audience-subject', 'name' => 'default-audience-subject', 'class' => 'audience_select', 'selected' => $subject, 'taxonomy' => 'audience-subject', 'hide_empty' => false, 'option_none_value' => '', 'show_option_none' => ' - NONE - ' ) );
?></p>
<p><label for='default-audience-journey'><strong><?php _e('Buying Stage', ALQUEMIE_AUDIENCE_TEXT_DOMAIN); ?>:</strong></label>
<?php
wp_dropdown_categories( array( 'id' => 'default-audience-journey', 'name' => 'default-audience-journey', 'class' => 'audience_select', 'selected' => $journey, 'taxonomy' => 'audience-journey', 'hide_empty' => false, 'option_none_value' => 'Awareness' ) );
?></p>
<p><label for='default-audience-segment'><strong><?php _e('Segment', ALQUEMIE_AUDIENCE_TEXT_DOMAIN); ?>:</strong></label>
<?php
wp_dropdown_categories( array( 'id' => 'default-audience-segment', 'name' => 'default-audience-segment', 'class' => 'audience_select', 'selected' => $segment, 'taxonomy' => 'audience-segment', 'hide_empty' => false, 'option_none_value' => '', 'show_option_none' => ' - NONE - ' ) );
?></p>

<?php wp_nonce_field( 'alquemie-audience-admin' ); ?>
<p class="submit"><input class="button-primary" type="submit" name="submit" value="<?php _e( 'Save Changes', ALQUEMIE_AUDIENCE_TEXT_DOMAIN) ?>"></p>
</form>
</div>
