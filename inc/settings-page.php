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

	// $this->init_metabox();
	$this->update_options();
	$cache_message = WP_CACHE ? ' <strong>' . __( 'If a caching/performance plugin is active, please invalidate its cache to ensure that changes are reflected immediately.' ) . '</strong>' : '';
	echo '<div id="message" class="updated"><p>' . __( 'Options updated. Changes to the Admin Menu and Admin Bar will not appear until you leave or reload this page.', ALQUEMIE_AUDIENCE_TEXT_DOMAIN ) . $cache_message . '</p></div>';
}
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
<?php
echo 'Topic: ';
wp_dropdown_categories( array( 'id' => 'alq_topic', 'name' => 'alq_topic', 'class' => 'alq_audience_field', 'selected' => $topic, 'taxonomy' => 'audience-topic', 'hide_empty' => false, 'option_none_value' => '', 'show_option_none' => ' - Select Topic - ' ) );
?>
<?php print_r($this->options['taxonomies']); ?>
<?php wp_nonce_field( 'alquemie-audience-admin' ); ?>
<p class="submit"><input class="button-primary" type="submit" name="submit" value="<?php _e( 'Save Changes', ALQUEMIE_AUDIENCE_TEXT_DOMAIN) ?>"></p>
</form>
</div>
