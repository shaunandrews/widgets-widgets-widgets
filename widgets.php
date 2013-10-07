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
	wp_enqueue_script( 'w3-main', plugins_url( 'scripts.js', __FILE__ ), array( 'jquery' ) );
	wp_enqueue_script( 'w3-live-filter', plugins_url( 'scripts/jquery.liveFilter.js', __FILE__ ) );
}

add_filter( 'load_default_widgets', 'w3_load_default_widgets' );
function w3_load_default_widgets( $load ) {
	require_once( 'default-widgets.php' );
	return false;
}

add_action( 'admin_menu', 'w3_settings_page' );
function w3_settings_page() {
	// create admin page
	$settings_page = add_submenu_page( 'themes.php', 'Widgets', 'Widgets', 'edit_theme_options', 'w3_widgets', 'w3_widgets_replace_core' );
}

function w3_widgets_replace_core() {
	/** WordPress (replaced by us) Administration Widgets API */
	require_once( 'core-widgets.php' );

	if ( ! current_user_can('edit_theme_options') )
		wp_die( __( 'Cheatin&#8217; uh?' ));

	echo '<div class="wrap w3-prototype">';
	screen_icon(); ?>
	<h2>Widgets</h2>
	<?php w3_new_widgets(); ?>
	</div><!-- .wrap -->' <?php
}

function w3_new_widgets() {
	global $wp_registered_sidebars;
	$current_theme = wp_get_theme();
	$theme_image = $current_theme->get_screenshot();
	?>
	<ol class="w3-tabs">
		<li class="w3-tab theme active" data-sidebar="sidebar-0" id="tab-sidebar-0">
			<span class="w3-sidebar-icon"><img src="<?php echo $theme_image; ?>"></span>
			<span class="w3-sidebar-title">Overview</span>
			<span class="w3-hide-overview" title="Don't show this again.">x</span>
		</li>

		<?php /* Hardcoded sidebares for 2012, grouped by template.
		<li class="w3-template">Blog</li>
		<li class="w3-tab" data-sidebar="sidebar-1" id="tab-sidebar-1">
			<span class="w3-sidebar-icon"><img src="<?php echo plugins_url( 'images/icon_default-sidebar.png' , __FILE__ ) ?>"></span>
			<span class="w3-sidebar-title">Main Sidebar</span>
			<span class="w3-widget-count">0</span>
		</li>

		<li class="w3-template">Homepage</li>
		<li class="w3-tab" data-sidebar="sidebar-2" id="tab-sidebar-2">
			<span class="w3-sidebar-icon"><img src="<?php echo plugins_url( 'images/icon_home-left.png' , __FILE__ ) ?>"></span>
			<span class="w3-sidebar-title">Left Side</span>
			<span class="w3-widget-count">0</span>
		</li>
		<li class="w3-tab" data-sidebar="sidebar-3" id="tab-sidebar-3">
			<span class="w3-sidebar-icon"><img src="<?php echo plugins_url( 'images/icon_home-right.png' , __FILE__ ) ?>"></span>
			<span class="w3-sidebar-title">Right Side</span>
			<span class="w3-widget-count">0</span>
		</li>
		 */ ?>

		<?php /* List of all sidebars */ ?>
		<li class="w3-template">
			Widget Areas
			<span class="w3-mission-control"><b>Mission Control</b></span>
		</li>
		<?php $i = 0; foreach ( $wp_registered_sidebars as $sidebar ) : ?>
			<li class="w3-tab<?php /* if ( $i == 0 ) echo ' active'; */ ?>" data-sidebar="<?php echo $sidebar['id']; ?>">
				<span class="widget-title"><?php echo str_replace( 'Widget Area', '', $sidebar['name'] ); ?></span>
				<span class="w3-widget-count"><?php echo w3_count_sidebar_widgets( $sidebar['id'] ); ?><span>
			</li>
		<?php $i++; endforeach; ?>
	</ol>

	<ol class="w3-sidebars">
		<li class="w3-sidebar widgets-overview active" id="sidebar-0" data-sidebar="sidebar-0">
			<div class="w3-sidebar-header">
				<span class="w3-sidebar-icon"><img src="<?php echo $theme_image; ?>"></span>
				<h2>Widgets Overview</h2>
				<p>Your current theme, <strong><?php echo $current_theme->name; ?></strong>, offers <?php echo count($wp_registered_sidebars); ?> widget areas.</p>
			</div>
			<div class="w3-sidebar-overview">
				<p>Widgets lets you add various bits of content througout your site. Your theme may offer any number of templates, each with specific widget areas.</p>
				<p><a href="http://codex.wordpress.org/WordPress_Widgets" target="_blank">Learn more about Widgets in WordPress</a></p>
			</div>
		</li>
		<?php
			$i = 0;
			foreach ( $wp_registered_sidebars as $sidebar => $registered_sidebar ) {
				if ( false !== strpos( $registered_sidebar['class'], 'inactive-sidebar' ) || 'orphaned_widgets' == substr( $sidebar, 0, 16 ) )
				continue;
		?>
		<li class="w3-sidebar" data-sidebar="<?php echo $registered_sidebar['id']; ?>" id="<?php echo $registered_sidebar['id']; ?>">
			<div class="w3-sidebar-header">
				<button data-url="<?php echo get_site_url(); ?>" class="sidebar-preview button-secondary">Preview Area</button>
				<h2><?php echo $registered_sidebar['name']; ?></h2>
				<p><?php echo $registered_sidebar['description']; ?></p>
			</div>
			<div class="w3-sidebar-widgets">
				<ol class="w3-widgets<?php if ( w3_count_sidebar_widgets( $registered_sidebar['id'] ) == 0 ) echo ' empty-sidebar'; ?>">
					<?php if ( w3_count_sidebar_widgets( $registered_sidebar['id'] ) == 0 ) : ?>
					<li class="w3-blank">Browse the list of available widgets to the right, and click one to add it to this&nbsp;area.</li>
					<?php endif; ?>
				<?php wp_list_widget_controls( $sidebar ); // Show the control forms for each of the widgets in this sidebar ?>
				</ol>
			</div>
		</li>
		<?php
			$i++;
		} ?>
	</ol>

	<div id="w3-available-widgets" class="inactive">
		<div class="w3-available-header">
			<!--
			<select class="filters">
				<option>Recently Used</option>
				<option>Alphabetical</option>
			</select>
			-->
			<h3>Available Widgets</h3>
		</div>
		<ol class="w3-widgets">
			<li class="notes">Click a widget to add it to the<br><strong class="current-sidebar">Main Sidebar</strong> area.</li>
			<?php wp_list_widgets(); ?>
		</ol>
		<div class="w3-available-footer">
			<input type="text" id="w3-live-filter-widgets" placeholder="Search for widgets&hellip;" />
		</div>
	</div>

	<div id="w3-mission-control">
	</div>

	<form action="" method="post">
	<?php wp_nonce_field( 'save-sidebar-widgets', '_wpnonce_widgets', false ); ?>
	</form>
<?php
}

function w3_count_sidebar_widgets( $sidebar_id ) {
	global $wp_registered_sidebars, $wp_registered_widgets;
	$sidebars_widgets = wp_get_sidebars_widgets();
	return count( $sidebars_widgets[$sidebar_id] );
}