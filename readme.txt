=== Widgets Widgets Widgets ===
Contributors: shaunandrews
Tags: widgets
Requires at least: 3.6 + MP6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Stable tag: trunk
Version: 0.1

== Description ==

Rethinking how widgets look and work in WordPress. Aiming to be part of core in 3.8. This is totally broken. Please don't install unless you have plans to make this code better. :)

== Requires Modifications to Core ==

Just in case you were looking for another reason to NOT install this plugin, it requires a few modications to WordPress Core. Since this is a replacement of the existing WordPress widgets, a few functions will need to be made "pluggable." This lets us overwrite the function as needed. Here's a diff for what needs to be updated:

diff --git wp-admin/includes/widgets.php wp-admin/includes/widgets.php
index dae9550..98089ca 100644
--- wp-admin/includes/widgets.php
+++ wp-admin/includes/widgets.php
@@ -56,6 +56,7 @@ function _sort_name_callback( $a, $b ) {
 	return strnatcasecmp( $a['name'], $b['name'] );
 }
 
+if ( !function_exists('wp_set_current_user') ) :
 /**
  * Show the widgets and their settings for a sidebar.
  * Used in the admin widget config screen.
@@ -80,6 +81,7 @@ function wp_list_widget_controls( $sidebar ) {
 	dynamic_sidebar( $sidebar );
 	echo "</div>\n";
 }
+endif;
 
 /**
  * {@internal Missing Short Description}}
@@ -123,6 +125,7 @@ function next_widget_id_number($id_base) {
 	return $number;
 }
 
+if ( !function_exists('wp_set_current_user') ) :
 /**
  * Meta widget used to display the control form for a widget.
  *
@@ -226,3 +229,4 @@ function wp_widget_control( $sidebar_args ) {
 	echo $sidebar_args['after_widget'];
 	return $sidebar_args;
 }
+endif;
\ No newline at end of file