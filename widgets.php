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
	screen_icon();
	echo '<h2>'. __( 'Widgets' ) .'</h2>';
	w3_new_widgets();
	echo '</div><!-- .wrap -->';
}

function w3_new_widgets() {
	global $wp_registered_sidebars;
	$current_theme = wp_get_theme();
	$theme_image = $current_theme->get_screenshot();
	?>
	<ol class="w3-tabs">
		<li class="tab-header">
			<h4>Widget Areas</h4>
			<img src="<?php echo $theme_image; ?>" height="50">
			<p><?php echo $current_theme; ?> offers <?php echo count( $wp_registered_sidebars ); ?> widget areas.</p>
			<p><?php echo count( get_page_templates() ); ?> templates</p>
		</li>
		<?php $i = 0; foreach ( $wp_registered_sidebars as $sidebar ) : ?>
			<li class="w3-tab<?php if ( $i == 0 ) echo ' active'; ?>" data-sidebar="<?php echo $sidebar['id']; ?>">
				<span class="widget-title"><?php echo str_replace( 'Widget Area', '', $sidebar['name'] ); ?></span>
				<span class="w3-widget-count"><?php echo w3_count_sidebar_widgets( $sidebar['id'] ); ?><span>
			</li>
		<?php $i++; endforeach; ?>
	</ol>

	<ol class="w3-sidebars">
		<?php
			$i = 0;
			foreach ( $wp_registered_sidebars as $sidebar => $registered_sidebar ) {
				if ( false !== strpos( $registered_sidebar['class'], 'inactive-sidebar' ) || 'orphaned_widgets' == substr( $sidebar, 0, 16 ) )
				continue;
		?>
		<li class="w3-sidebar<?php if ( $i == 0 ) echo ' active'; ?>" id="sb-<?php echo $registered_sidebar['id']; ?>">
			<div class="w3-sidebar-header">
				<h2><?php echo str_replace( 'Widget Area', '', $registered_sidebar['name'] ); ?></h2>
				<p><?php echo $registered_sidebar['description']; ?></p>
			</div>
			<div class="w3-sidebar-widgets">
				<ol class="w3-widgets<?php if ( w3_count_sidebar_widgets( $registered_sidebar['id'] ) == 0 ) echo ' empty-sidebar'; ?>">
					<?php
						if ( w3_count_sidebar_widgets( $registered_sidebar['id'] ) == 0 )
							echo '<li class="blank">Drag a widget from the right and drop it here to place it.</li>';
						?>
				<?php wp_list_widget_controls( $sidebar ); // Show the control forms for each of the widgets in this sidebar ?>
				</ol>
			</div>
		</li>
		<?php
			$i++;
		} ?>

		<!--
			<li class="w3-sidebar" id="sb-home-left">
				<div class="w3-sidebar-header">
					<h2>Home Left</h2>
					<p>Appears on the bottom-left of the homepage.</p>
				</div>
				<div class="w3-sidebar-widgets">
					<ol class="w3-widgets">
						<li class="w3-widget">

						</li>
					</ol>
				</div>
				<div class="w3-sidebar-footer">
					<a class="button" href="#">Add a Widget</a>
				</div>
			</li>
			<li class="w3-sidebar active" id="sb-home-right">
				<div class="w3-sidebar-header">
					<h2>Home Right</h2>
					<p>Appears on the bottom-right of the homepage.</p>
				</div>
				<div class="w3-sidebar-widgets">
					<ol class="w3-widgets">
						<li class="w3-widget">
							<div class="w3-widget-header">
								<h3>Select Design Work</h3>
								<p>Custom Menu</p>
								<span class="w3-widget-edit">Edit</span>
							</div>
							<div class="w3-widget-settings">
								<fieldset>
									<label>Title</label>
									<input type="text" value="Select Design Work">
								</fieldset>
								<fieldset>
									<label>Menu</label>
									<select>
										<option>Choose a menu&hellip;</option>
										<optgroup label="Your Menus">
											<option selected>Select Design Work</option>
											<option>Main Menu</option>
											<option>Some Other Menu</option>
										</optgroup>
										<optgroup label="Smart Menus">
											<option>All Pages, Nested</option>
											<option>All Pages, Flat</option>
											<option>All Top Level Pages</option>
										</optgroup>
									</select>
								</fieldset>
								<fieldset>
									<a href="#">Delete</a>
									<button class="button">Save</button>
								</fieldset>
							</div>
						</li>
						<li class="w3-widget">
							<div class="w3-widget-header">
								<h3>Recent Comments</h3>
								<p>No title</p>
								<span class="w3-widget-edit">Edit</span>
							</div>
							<div class="w3-widget-settings">
								<fieldset>
									<label>Title</label>
									<input type="text">
								</fieldset>
								<fieldset>
									<label>No. of Comments</label>
									<input type="text">
								</fieldset>
								<fieldset>
									<a href="#">Delete</a>
									<button class="button">Save</button>
								</fieldset>
							</div>
						</li>
					</ol>
				</div>
				<div class="w3-sidebar-footer">
					<a class="button" href="#">Add a Widget</a>
				</div>
			</li>
			<li class="w3-sidebar" id="sb-blog-side">
				<div class="w3-sidebar-header">
					<h2>Blog Sidebar</h2>
					<p>Appears in the sidebar of your blog.</p>
				</div>
				<div class="w3-sidebar-widgets">
					<ol class="w3-widgets">
						<li class="w3-widget">
						</li>
					</ol>
				</div>
				<div class="w3-sidebar-footer">
					<a class="button" href="#">Add a Widget</a>
				</div>
			</li>
			<li class="w3-sidebar" id="sb-about-page">
				<div class="w3-sidebar-header">
					<h2>About Page</h2>
					<p>Appears on the bottom of the about page.</p>
				</div>
				<div class="w3-sidebar-widgets">
					<ol class="w3-widgets">
						<li class="w3-widget">
						</li>
					</ol>
				</div>
				<div class="w3-sidebar-footer">
					<a class="button" href="#">Add a Widget</a>
				</div>
			</li>
			<li class="w3-sidebar" id="sb-footer">
				<div class="w3-sidebar-header">
					<h2>Footer</h2>
					<p>Appears on the bottom of every page.</p>
				</div>
				<div class="w3-sidebar-widgets">
					<ol class="w3-widgets">
						<li class="w3-widget">
						</li>
					</ol>
				</div>
				<div class="w3-sidebar-footer">
					<a class="button" href="#">Add a Widget</a>
				</div>
			</li>
		-->
	</ol>

	<ul class="w3-available-widgets">
		<li class="w3-section-header">Available Widgets</li>
		<?php wp_list_widgets(); ?>
		<!-- My best hopes...
				<ol class="w3-recent-widgets">
					<li class="w3-widget">
						<div class="w3-widget-header">
							<img src="http://fpoimg.com/40x40">
							<h3>Custom Menu</h3>
							<p>Select a menu to display.</p>
							<span class="w3-widget-edit">Edit</span>
						</div>
						<div class="w3-widget-settings">
							<fieldset>
								<label>Title</label>
								<input type="text" value="Select Design Work">
							</fieldset>
							<fieldset>
								<label>Menu</label>
								<select>
									<option>Choose a menu&hellip;</option>
									<optgroup label="Your Menus">
										<option selected>Select Design Work</option>
										<option>Main Menu</option>
										<option>Some Other Menu</option>
									</optgroup>
									<optgroup label="Smart Menus">
										<option>All Pages, Nested</option>
										<option>All Pages, Flat</option>
										<option>All Top Level Pages</option>
									</optgroup>
								</select>
							</fieldset>
							<fieldset>
								<button class="button">Add</button>
							</fieldset>
						</div>
					</li>
					<li class="w3-widget ui-draggable">
						<div class="w3-widget-header">
							<img src="http://fpoimg.com/40x40">
							<h3>Recent Comments</h3>
							<p>Show a list of recent coments</p>
							<span class="w3-widget-edit">Edit</span>
						</div>
						<div class="w3-widget-settings">
							<fieldset>
								<label>Title</label>
								<input type="text">
							</fieldset>
							<fieldset>
								<label>No. of Comments</label>
								<input type="text">
							</fieldset>
							<fieldset>
								<button class="button">Add</button>
							</fieldset>
						</div>
					</li>
				</ol>
				<a href="#" class="button">View All Widgets</a>
		-->
	</ul>
<?php
}

function w3_count_sidebar_widgets( $sidebar_id ) {
	global $wp_registered_sidebars, $wp_registered_widgets;
	$sidebars_widgets = wp_get_sidebars_widgets();
	return count( $sidebars_widgets[$sidebar_id] );
}