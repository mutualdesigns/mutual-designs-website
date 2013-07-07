<?php
/*
Plugin Name: Mutual Designs Project Manager
Plugin URI: http://mutual-designs.com/projectmanager/
Description: This plugin is designed to help in the efficiency of a designer or small design company by tracking clients, projects, tasks, and time.
Version: 1.0
Author: Jessica Price of Mutual Designs
Author URI: http://www.mutual-designs.com
License: GPL2
*/
?>
<?php
/*  Copyright 2010  Mutual Designs  (email : contactus@mutual-designs.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>

<?php /* ---Make Database--- */

$pm_db_version = "1.0";

function pm_install () {
   global $wpdb;
   global $pm_db_version;

   $table_name = $wpdb->prefix . "pmclients";
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
      
      $sql = "CREATE TABLE " . $table_name . " (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  time bigint(11) DEFAULT '0' NOT NULL,
	  name tinytext NOT NULL,
	  text text NOT NULL,
	  url VARCHAR(55) NOT NULL,
	  UNIQUE KEY id (id)
	);";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
 
      add_option("pm_db_version", $pm_db_version);

   }
   
   $installed_ver = get_option( "pm_db_version" );

   if( $installed_ver != $pm_db_version ) {

      $sql = "CREATE TABLE " . $table_name . " (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  time bigint(11) DEFAULT '0' NOT NULL,
	  name tinytext NOT NULL,
	  text text NOT NULL,
	  url VARCHAR(100) NOT NULL,
	  UNIQUE KEY id (id)
	);";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);

      update_option( "pm_db_version", $pm_db_version );
   }
}

register_activation_hook(__FILE__,'pm_install');

/* ---create Admin menu--- */
add_action('admin_menu', 'my_plugin_menu');

add_menu_page('mdprojectmanager.php', 'Project Manager', 'contributor', handle, [function], [icon_url]);

function my_plugin_menu() {
  add_options_page('My Plugin Options', 'My Plugin', 'capability_required', 'your-unique-identifier', 'my_plugin_options');
}

function my_plugin_options() {
  echo '<div class="wrap">';
  echo '<p>Here is where the form would go if I actually had options.</p>';
  echo '</div>';
}
?>