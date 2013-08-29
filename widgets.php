<?php
/*
Plugin Name: Widgets, Widgets, Widgets
Plugin URI: http://shaunandrews.com/wordpress/widgets-widgets-widgets/
Description: A tabbed prototype for managing widgets.
Version: 0.2
Author: Shaun Andrews
Author URI: http://automattic.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Enqueue some new styles and drop in a few lines of js
add_action( 'admin_print_styles', 'w3_add_style' );
function w3_add_style() {
	if ( get_current_screen()->id != 'appearance_page_w3_widgets' )
		return;
	wp_enqueue_style( 'widgets-widgets-widgets', plugins_url( 'style.css', __FILE__ ) );
}

add_action( 'admin_enqueue_scripts', 'w3_add_scripts' );
function w3_add_scripts() {
	if ( get_current_screen()->id != 'appearance_page_w3_widgets' )
		return;
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-droppable' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'jquery-effects-highlight' );
	wp_enqueue_script( 'widgets-widgets-widgets', plugins_url( 'scripts.js', __FILE__ ), array( 'jquery' ) );
}

add_action( 'admin_menu', 'w3_settings_page' );
function w3_settings_page() {
	// create admin page
	$settings_page = add_submenu_page( 'themes.php', 'Widgets', 'Widgets Prototype', 'edit_theme_options', 'w3_widgets', 'w3_widgets_replace_core' );
}

function w3_widgets_replace_core() {
	/** WordPress (replaced by us) Administration Widgets API */
	require_once( 'core-widgets.php' );

	if ( ! current_user_can('edit_theme_options') )
		wp_die( __( 'Cheatin&#8217; uh?' ));

	echo '<div class="wrap w3-prototype">';
	screen_icon();?>
	<h2 class="nav-tab-wrapper">
		<a href="#" class="nav-tab nav-tab-active">Widgets</a>
		<a href="#" class="nav-tab">Locations</a>
	</h2>
	<?php w3_new_widgets();
	echo '</div><!-- .wrap -->';
}

function w3_new_widgets() {
	global $wp_registered_sidebars;
	$current_theme = wp_get_theme();
	$theme_image = $current_theme->get_screenshot();
	?>
	<ol class="w3-tabs">
		<!--
		<li class="tab-header">
			<h4>Widget Areas</h4>
			<img src="<?php echo $theme_image; ?>" height="50">
			<p><?php echo $current_theme; ?></p>
		</li>
		-->
		<li class="w3-template">Default Template</li>
		<li class="w3-tab active" data-sidebar="sidebar-1" id="tab-sidebar-1">
			<span class="widget-title">Main Sidebar</span>
			<span class="w3-widget-count">0</span>
		</li>
		<li class="w3-template">Front Page Template</li>
		<li class="w3-tab" data-sidebar="sidebar-2" id="tab-sidebar-2">
			<span class="widget-title">Left Side</span>
			<span class="w3-widget-count">0</span>
		</li>
		<li class="w3-tab" data-sidebar="sidebar-3" id="tab-sidebar-3">
			<span class="widget-title">Right Side</span>
			<span class="w3-widget-count">0</span>
		</li>
<!--
		<?php $i = 0; foreach ( $wp_registered_sidebars as $sidebar ) : ?>
			<li class="w3-tab<?php if ( $i == 0 ) echo ' active'; ?>" data-sidebar="<?php echo $sidebar['id']; ?>">
				<span class="widget-title"><?php echo str_replace( 'Widget Area', '', $sidebar['name'] ); ?></span>
				<span class="w3-widget-count"><?php echo w3_count_sidebar_widgets( $sidebar['id'] ); ?><span>
			</li>
		<?php $i++; endforeach; ?>
-->
	</ol>

	<ol class="w3-sidebars">
		<?php
			$i = 0;
			foreach ( $wp_registered_sidebars as $sidebar => $registered_sidebar ) {
				if ( false !== strpos( $registered_sidebar['class'], 'inactive-sidebar' ) || 'orphaned_widgets' == substr( $sidebar, 0, 16 ) )
				continue;
		?>
		<li class="w3-sidebar<?php if ( $i == 0 ) echo ' active'; ?>" data-sidebar="<?php echo $registered_sidebar['id']; ?>" id="<?php echo $registered_sidebar['id']; ?>">
			<div class="w3-sidebar-header">
				<h2><?php echo str_replace( 'Widget Area', '', $registered_sidebar['name'] ); ?></h2>
				<p><?php echo $registered_sidebar['description']; ?></p>
			</div>
			<div class="w3-sidebar-widgets">
				<ol class="w3-widgets<?php if ( w3_count_sidebar_widgets( $registered_sidebar['id'] ) == 0 ) echo ' empty-sidebar'; ?>">
					<?php
						if ( w3_count_sidebar_widgets( $registered_sidebar['id'] ) == 0 )
							echo '<li class="w3-blank">Widgets let you add little bits of content throughout your site. Click the button below to add a new widget.</li>';
						?>
				<?php wp_list_widget_controls( $sidebar ); // Show the control forms for each of the widgets in this sidebar ?>
				</ol>
			</div>
			<div class="w3-sidebar-footer">
				<button class="button add-a-widget">Add a Widget</button>
			</div>
		</li>
		<?php
			$i++;
		} ?>
	</ol>

	<div class="wp-modal">
		<div class="wp-modal-inner">
			<div class="wp-modal-close">Close</div>
			<ul class="wp-modal-menu">
				<li class="active">Available Widgets</li>
				<li>Inactive Widgets</li>
			</ul>
			<div class="wp-modal-title">
				<h1>Add a Widget</h1>
			</div>
			<div class="wp-modal-content">
				<ol class="w3-available-widgets">
					<?php wp_list_widgets(); ?>
				</ol>
				<div class="wp-modal-sidebar"></div>
			</div>
			<!--
			<div class="wp-modal-toolbar">
				<ol class="w3-selected-widgets"></ol>
			</div>
			-->
		</div>
	</div>
	<div class="wp-modal-backdrop"></div>
<?php
}

function w3_count_sidebar_widgets( $sidebar_id ) {
	global $wp_registered_sidebars, $wp_registered_widgets;
	$sidebars_widgets = wp_get_sidebars_widgets();
	return count( $sidebars_widgets[$sidebar_id] );
}