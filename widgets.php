<?php
/*
Plugin Name: Widgets, Widgets, Widgets
Plugin URI: http://shaunandrews.com/wordpress/widgets-widgets-widgets/
Description: Rethinking Widgets in WordPress
Version: 0.1
Author: Shaun Andrews
Author URI: http://automattic.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Enqueue some new styles and drop in a few lines of js
add_action( 'admin_print_styles', 'w3_add_style' );
function w3_add_style() {
	wp_enqueue_style( 'widgets-widgets-widgets', plugins_url( "style.css", __FILE__ ), array(), '20111209' );
}

add_action( 'widgets_admin_page', 'w3_new_widgets' );
function w3_new_widgets() {
	global $wp_registered_sidebars;
?>
	<ol class="w3-tabs">
		<?php $i = 0; foreach ( $wp_registered_sidebars as $sidebar ) : ?>
			<li class="w3-tab<?php if ( $i == 0 ) echo ' active'; ?>" data-sidebar="<?php echo $sidebar['id']; ?>">
				<?php echo $sidebar['name']; ?>
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
				<h2><?php echo $registered_sidebar['name']; ?></h2>
				<p><?php echo $registered_sidebar['description']; ?></p>
			</div>
			<div class="w3-sidebar-widgets">
				<ol class="w3-widgets ui-sortables">
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

	<ul class="w3-available-widgets widgets-sortables ui-sortable">
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

	<script type="text/javascript">
		(function($) {
			$( '.w3-tab' ).click( function() {
				$( '.w3-tabs .active' ).removeClass( 'active' );
				$( this ).toggleClass( 'active' );
				var sidebar = $( this ).data( 'sidebar' );

				$( '.w3-sidebars .active' ).removeClass( 'active' );
				$( '.w3-sidebars #sb-' + sidebar ).addClass( 'active' );
			});

			$( '.w3-widget-edit' ).click( function() {
				$( this ).parent().parent().toggleClass( 'editing' );
				$( this ).parent().next().slideToggle( 'fast' );

				if ( $( this ).parent().parent().hasClass( 'editing' ) ) {
					$( this ).html( 'Cancel' );
				}
				else {
					$( this ).html( 'Edit' );
				}
			});
		})(jQuery);
	</script>
<?php
	//exit();
}

/**
 * Show the widgets and their settings for a sidebar.
 * Used in the admin widget config screen.
 *
 * @since 2.5.0
 *
 * @param string $sidebar id slug of the sidebar
 */
function wp_list_widget_controls( $sidebar ) {
	add_filter( 'dynamic_sidebar_params', 'wp_list_widget_controls_dynamic_sidebar' );
	dynamic_sidebar( $sidebar );
}

function w3_count_sidebar_widgets( $sidebar_id ) {
	global $wp_registered_sidebars, $wp_registered_widgets;
	$sidebars_widgets = wp_get_sidebars_widgets();
	return count( $sidebars_widgets[$sidebar_id] );
}

/**
 * Meta widget used to display the control form for a widget.
 *
 * Called from dynamic_sidebar().
 *
 * @since 2.5.0
 *
 * @param array $sidebar_args
 * @return array
 */
function wp_widget_control( $sidebar_args ) {
	global $wp_registered_widgets, $wp_registered_widget_controls, $sidebars_widgets;

	$widget_id = $sidebar_args['widget_id'];
	$sidebar_id = isset($sidebar_args['id']) ? $sidebar_args['id'] : false;
	$key = $sidebar_id ? array_search( $widget_id, $sidebars_widgets[$sidebar_id] ) : '-1'; // position of widget in sidebar
	$control = isset($wp_registered_widget_controls[$widget_id]) ? $wp_registered_widget_controls[$widget_id] : array();
	$widget = $wp_registered_widgets[$widget_id];

	$id_format = $widget['id'];
	$widget_number = isset($control['params'][0]['number']) ? $control['params'][0]['number'] : '';
	$id_base = isset($control['id_base']) ? $control['id_base'] : $widget_id;
	$multi_number = isset($sidebar_args['_multi_num']) ? $sidebar_args['_multi_num'] : '';
	$add_new = isset($sidebar_args['_add']) ? $sidebar_args['_add'] : '';

	$query_arg = array( 'editwidget' => $widget['id'] );
	if ( $add_new ) {
		$query_arg['addnew'] = 1;
		if ( $multi_number ) {
			$query_arg['num'] = $multi_number;
			$query_arg['base'] = $id_base;
		}
	} else {
		$query_arg['sidebar'] = $sidebar_id;
		$query_arg['key'] = $key;
	}

	// We aren't showing a widget control, we're outputting a template for a multi-widget control
	if ( isset($sidebar_args['_display']) && 'template' == $sidebar_args['_display'] && $widget_number ) {
		// number == -1 implies a template where id numbers are replaced by a generic '__i__'
		$control['params'][0]['number'] = -1;
		// with id_base widget id's are constructed like {$id_base}-{$id_number}
		if ( isset($control['id_base']) )
			$id_format = $control['id_base'] . '-__i__';
	}

	$wp_registered_widgets[$widget_id]['callback'] = $wp_registered_widgets[$widget_id]['_callback'];
	unset($wp_registered_widgets[$widget_id]['_callback']);

	$widget_title = esc_html( strip_tags( $sidebar_args['widget_name'] ) );
	$has_form = 'noform';

	//echo $sidebar_args['before_widget']; ?>
	<li class="w3-widget">
		<div class="w3-widget-header">
			<h3><?php echo $widget_title ?></h3>
			<p><?php echo ( $widget_description = wp_widget_description($widget_id) ) ? "$widget_description\n" : "$widget_title\n"; ?></p>
			<span class="w3-widget-edit">Edit</span>
			<!--
			<div class="widget-title-action">
				<a class="widget-action hide-if-no-js" href="#available-widgets"></a>
				<a class="widget-control-edit hide-if-js" href="<?php echo esc_url( add_query_arg( $query_arg ) ); ?>">
					<span class="edit"><?php _ex( 'Edit', 'widget' ); ?></span>
					<span class="add"><?php _ex( 'Add', 'widget' ); ?></span>
					<span class="screen-reader-text"><?php echo $widget_title; ?></span>
				</a>
			</div>
			-->
		</div>

		<div class="w3-widget-settings">
			<form action="" method="post">
				<?php
					if ( isset($control['callback']) )
						$has_form = call_user_func_array( $control['callback'], $control['params'] );
					else
						echo "\t\t<p>" . __('There are no options for this widget.') . "</p>\n";
				?>

				<input type="hidden" name="widget-id" class="widget-id" value="<?php echo esc_attr($id_format); ?>" />
				<input type="hidden" name="id_base" class="id_base" value="<?php echo esc_attr($id_base); ?>" />
				<input type="hidden" name="widget-width" class="widget-width" value="<?php if (isset( $control['width'] )) echo esc_attr($control['width']); ?>" />
				<input type="hidden" name="widget-height" class="widget-height" value="<?php if (isset( $control['height'] )) echo esc_attr($control['height']); ?>" />
				<input type="hidden" name="widget_number" class="widget_number" value="<?php echo esc_attr($widget_number); ?>" />
				<input type="hidden" name="multi_number" class="multi_number" value="<?php echo esc_attr($multi_number); ?>" />
				<input type="hidden" name="add_new" class="add_new" value="<?php echo esc_attr($add_new); ?>" />

				<div class="widget-control-actions">
					<a class="widget-control-remove" href="#remove"><?php _e('Delete'); ?></a> |
					<a class="widget-control-close" href="#close"><?php _e('Close'); ?></a>

					<?php submit_button( __( 'Save' ), 'button-primary widget-control-save right', 'savewidget', false, array( 'id' => 'widget-' . esc_attr( $id_format ) . '-savewidget' ) ); ?>

					<span class="spinner"></span>
				</div>
			</form>
		</div>
	</li>
	<?php
	//echo $sidebar_args['after_widget'];
	return $sidebar_args;
}