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
	<h2>Widgets</h2>
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
			<span class="w3-sidebar-icon"><img src="<?php echo plugins_url( 'images/icon_default-sidebar.png' , __FILE__ ) ?>"></span>
			<span class="w3-sidebar-title">Main Sidebar</span>
			<span class="w3-widget-count">0</span>
		</li>
		<li class="w3-template">Front Page Template</li>
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
		
<?php /* 2012 Templates
		<?php $i = 0; foreach ( $wp_registered_sidebars as $sidebar ) : ?>
			<li class="w3-tab<?php if ( $i == 0 ) echo ' active'; ?>" data-sidebar="<?php echo $sidebar['id']; ?>">
				<span class="widget-title"><?php echo str_replace( 'Widget Area', '', $sidebar['name'] ); ?></span>
				<span class="w3-widget-count"><?php echo w3_count_sidebar_widgets( $sidebar['id'] ); ?><span>
			</li>
		<?php $i++; endforeach; ?>
*/ ?>
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
				<?php if ( 'Main Sidebar' == $registered_sidebar['name'] ) { ?>
				<span class="w3-sidebar-icon"><img src="<?php echo plugins_url( 'images/icon_default-sidebar.png' , __FILE__ ) ?>"></span>
				<?php $sidebar_name = 'Main Sidebar'; ?>
				<?php } else if ( 'First Front Page Widget Area' == $registered_sidebar['name'] ) { ?>
				<span class="w3-sidebar-icon"><img src="<?php echo plugins_url( 'images/icon_home-left.png' , __FILE__ ) ?>"></span>
				<?php $sidebar_name = 'Left Side'; ?>
				<?php } else if ( 'Second Front Page Widget Area' == $registered_sidebar['name'] ) { ?>
				<span class="w3-sidebar-icon"><img src="<?php echo plugins_url( 'images/icon_home-right.png' , __FILE__ ) ?>"></span>
				<?php $sidebar_name = 'Right Side'; ?>
				<?php } ?>

				<h2><?php echo $sidebar_name; ?></h2>
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
			<!--
			<div class="w3-sidebar-footer">
				<button class="button add-a-widget">Add a Widget</button>
			</div>
			-->
		</li>
		<?php
			$i++;
		} ?>
	</ol>

	<div id="w3-available-widgets">
		<div class="w3-available-header">
			<!--
			<select class="filters">
				<option>Recently Used</option>
				<option>Alphabetical</option>
			</select>
			-->
			<h3>Widgets</h3>
		</div>
		<ol class="w3-widgets">
			<li class="notes">Click a widget to add it to the <strong class="current-sidebar">Main Sidebar</strong> area.</li>
			<?php wp_list_widgets(); ?>
			<!--
			<li id="widget-8_nav_menu-2" class="w3-widget">
				<div class="w3-widget-header">
					<h3>Custom Menu</h3>
					<p>Use this widget to add one of your custom menus as a widget.</p>
					<span class="w3-widget-edit">Edit</span>
				</div>

				<div class="w3-widget-settings">
					<form action="" method="post">
						<div class="widget-content">
							<p>
								<label for="widget-nav_menu-2-title">Title:</label>
								<input type="text" class="widefat" id="widget-nav_menu-2-title" name="widget-nav_menu[2][title]" value="Widgets">
							</p>
							<p>
								<label for="widget-nav_menu-2-nav_menu">Select Menu:</label>
								<select id="widget-nav_menu-2-nav_menu" name="widget-nav_menu[2][nav_menu]">
									<option value="91">Primary Navigation</option>
									<option value="90" selected="selected">Widgets Redesign</option>
								</select>
							</p>
						</div>

						<input type="hidden" name="widget-id" class="widget-id" value="nav_menu-2">
						<input type="hidden" name="id_base" class="id_base" value="nav_menu">
						<input type="hidden" name="widget-width" class="widget-width" value="250">
						<input type="hidden" name="widget-height" class="widget-height" value="200">
						<input type="hidden" name="widget_number" class="widget_number" value="2">
						<input type="hidden" name="multi_number" class="multi_number" value="">
						<input type="hidden" name="add_new" class="add_new" value="">

						<div class="w3-widget-actions">
							<input type="submit" name="savewidget" id="widget-nav_menu-2-savewidget" class="button button-primary w3-widget-save" value="Save">					<a class="w3-widget-remove" href="#remove">Delete</a>
							<span class="spinner"></span>
						</div>
					</form>
				</div>
			</li>
			-->
		</ol>
		<div class="w3-available-footer">
			<input type="search" id="w3-live-filter-widgets" placeholder="Search for widgets&hellip;" />
		</div>
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