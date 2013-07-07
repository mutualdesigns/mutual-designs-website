<?php

/*

Plugin Name: WP-Project

Plugin URI: http://www.plugin-developer.com

Description: WP-Project creates a complete project management system in the WordPress backend.

Author: Nick Ohrn

Version: 1.2.3

Author URI: http://www.plugin-developer.com/

*/



// Avoid name collisions

if( !class_exists( 'WP_Project' ) ) {



	class WP_Project {

		

		var $version = "1.2.3";

		var $options;

		var $is_installed = false;

		

		var $page_slugs;

		

		var $project_table;

		var $task_table;

		var $client_table;

		var $update_table;

		

		/**

		 * The following section has the constructor and functions that hook into WordPress.

		 */

		

		/**

		 * Default 

		 * constructor initializes variables and other data needed for the plugin to operate

		 * correctly.

		 *

		 * @return WP_Project A newly constructed instance of the WP_PM object with all data initialized.

		 */

		function WP_Project() {

			global $wpdb;

			

			// Setup the table names

			$this->project_table = $wpdb->prefix . 'project_projects';

			$this->task_table = $wpdb->prefix . 'project_tasks';

			$this->client_table = $wpdb->prefix . 'project_clients';

			$this->update_table = $wpdb->prefix . 'project_updates';

			

			if( isset( $_POST[ 'uninstall_wp_project_complete' ] ) ) {

				$this->uninstall();

			}



			

			if( get_option( 'WP-Project Options' ) !== FALSE ) {

				$this->options = unserialize( get_option( 'WP-Project Options' ) ); 

			} else {

				$this->options = array();

			}

			

			if( get_option( 'WP-Project Version' ) !== FALSE ) {

				$this->is_installed = true;

			}

			

			// Setup the page slug array

			$this->page_slugs = array();

			

		}

		

		/**

		 * Check to see if tables for the WP-Project plugin are installed and that the plugin is the current version.

		 * If those two things are true, then leave the data alone.  Otherwise, upgrade or install the necessary

		 * tables.

		 */

		function on_activate() {

			

			

			

			$current_version = get_option( 'WP-Project Version' );

			

			// Install the various tables if they don't exist already

			if( FALSE === $current_version ) {

				// The plugin isn't installed, so we'll install it

				$this->install();

				

			} else if( $this->version == $current_version ) {

				// The plugin is already updated and this is just a reactivation, so do nothing

				

			} else {

				// The plugin is being upgraded, let's do an upgrade

				$this->upgrade( $current_version );

				

			}

			

		}

		

		/**

		 * This function will not make any changes to data that exists in the database.  That is reserved for the 

		 * uninstall_data function.  For now, this is just a placeholder in case some action becomes necessary

		 * on deactivation.

		 */

		function on_deactivate() {

			// We're not really doing anything on a deactivation, because everything is being uninstalled

			// through a separate mechanism to ensure none of the good data gets erased.

			

		}

		

		/**

		 * Adds all additional pages necessary for the correct administration of WP-Project, as well as enqueueing any

		 * JavaScript files necessary for those files.

		 */

		function on_admin_menu() {

			$this->page_slugs[ 'top_level' ] = add_menu_page( 'WP-Project', 'WP-Project', 8, 'wp-project', array( &$this, 'top_level_page' ) );

			

			if( $this->is_installed ) {

				

				wp_enqueue_script( 'wp-project', get_bloginfo( 'siteurl' ) . '/wp-content/plugins/wp-project/js/wp-project.js', array( 'jquery' ) );

				$this->page_slugs[ 'projects' ]  = add_submenu_page( 'wp-project', 'Projects', 'Projects', 8, 'wp-project/projects', array( &$this, 'project_page' ) );

				$this->page_slugs[ 'clients' ]  = add_submenu_page( 'wp-project', 'Clients', 'Clients', 8, 'wp-project/clients', array( &$this, 'client_page' ) );

				$this->page_slugs[ 'tasks' ]  = add_submenu_page( 'wp-project', 'Tasks', 'Tasks', 8, 'wp-project/tasks', array( &$this, 'task_page' ) );

				// $this->page_slugs[ 'billing' ]  = add_submenu_page( 'wp-project', 'Billing', 'Billing', 8, 'wp-project/billing', array( &$this, 'billing_page' ) );

				// $this->page_slugs[ 'options' ]  = add_submenu_page( 'wp-project', 'Options', 'Options', 8, 'wp-project/options', array( &$this, 'option_page' ) );

				$this->page_slugs[ 'uninstall' ] = add_submenu_page( 'wp-project', 'Uninstall', 'Uninstall', 8, 'wp-project/uninstall', array( &$this, 'uninstall_page' ) );

			}

			

			$this->page_slugs[ 'about' ]  = add_submenu_page( 'wp-project', 'About', 'About', 8, 'wp-project/about', array( &$this, 'about_page' ) );

			$this->page_slugs[ 'donate' ]  = add_submenu_page( 'wp-project', 'Donate', 'Donate', 8, 'wp-project/donate', array( &$this, 'donate_page' ) );

		}

		

		/**

		 * Selectively prints information to the head section of the administrative HTML section.

		 */

		function on_admin_head() {

			if( strpos( $_SERVER['REQUEST_URI'], 'wp-project' ) ) {

				?>

				<link rel="stylesheet" href="<?php bloginfo( 'siteurl' ); ?>/wp-admin/css/dashboard.css?version=2.5.1" type="text/css" />

				<link rel="stylesheet" href="<?php bloginfo( 'siteurl' ); ?>/wp-content/plugins/WP-Project/css/wp-project.css" type="text/css" />

				<?php

				if( strpos( $_SERVER[ 'REQUEST_URI' ], 'tasks' ) ) {

				?>

				<script type="text/javascript">

					var old_id = <?php echo $this->options[ 'current_timer' ]; ?>

				</script>

				<?php

				}

			}

		}



		/**

		 * Perform initialization required after WordPress loads but before any HTTP headers

		 * are sent.

		 */

		function on_init() {

			

		}

		

		/**

		 * Toggles the currently active timer, and saves whatever time was 

		 *

		 */

		function on_timer_toggle() {

			$current_timer = $this->options[ 'current_timer' ];

			$current_timer_started = $this->options[ 'timer_started' ];

			

			$new_timer = $_POST[ 'timer_id' ];

			$new_timer_started = time();

			

			if( $current_timer == $new_timer ) {

				$this->options[ 'current_timer' ] = -1;

				$this->options[ 'timer_started' ] = 0;				

				

			} else {

				$this->options[ 'current_timer' ] = $new_timer;

				$this->options[ 'timer_started' ] = $new_timer_started;

				

			}

			

			

			update_option( 'WP-Project Options', serialize( $this->options ) );

			$this->add_seconds_to_task( $current_timer, $new_timer_started - $current_timer_started );

			

			echo $new_timer;

			

			exit;

		}

		

		/**

		 * The following functions are all utility functions for the plugin.

		 */

		

		/**

		 * Installs the plugin for the first time by creating all tables and storing all the options

		 * that need to be stored.

		 */

		function install() {

			

			global $wpdb;

			

			

			require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

			

			// Make sure the project table doesn't already exist

			if( $wpdb->get_var( "SHOW TABLES LIKE '$this->project_table'" ) != $this->project_table ) {

				

				// Create the table to hold information about projects

				$project_query = "CREATE TABLE $this->project_table (

							project_id INT NOT NULL AUTO_INCREMENT,

							client_id INT NOT NULL,

							project_title VARCHAR(200) NOT NULL,

							project_description VARCHAR(2000) NOT NULL,

							PRIMARY KEY (project_id))";

							

				dbDelta($project_query);

				$wpdb->query( "INSERT INTO $this->project_table (project_id, client_id, project_title, project_description) VALUES (-1, -1, 'No Project', '')" );

			} // End project table existence check

			

			// Make sure the client table doesn't already exist

			if( $wpdb->get_var( "SHOW TABLES LIKE '$this->client_table'" ) != $this->client_table ) {

				

				// Create the table to hold information about clients

				$client_query = "CREATE TABLE $this->client_table (

							client_id INT NOT NULL AUTO_INCREMENT,

							client_name VARCHAR(200) NOT NULL,

							client_email VARCHAR(200) NOT NULL,

							client_site VARCHAR(200) NOT NULL,

							client_description VARCHAR(2000) NOT NULL,

							PRIMARY KEY (client_id))";

				

				dbDelta($client_query);

				$wpdb->query( "INSERT INTO $this->client_table (client_id, client_name, client_email, client_site, client_description) VALUES (-1, 'No Client', '', '', '')" );

			} // End client table existence check

			

			// Make sure the task table doesn't already exist

			if( $wpdb->get_var( "SHOW TABLES LIKE '$this->task_table'" ) != $this->task_table ) {

				

				// Create the table to hold information about tasks

				$task_query = "CREATE TABLE $this->task_table (

							task_id INT NOT NULL AUTO_INCREMENT,

							project_id INT NOT NULL,

							task_name VARCHAR(200) NOT NULL,

							task_description VARCHAR(2000) NOT NULL,

							task_priority INT NOT NULL,

							task_time FLOAT NOT NULL,

							task_status VARCHAR(100) NOT NULL,

							PRIMARY KEY (task_id))";

					

				dbDelta($task_query);

			} // End task table existence check

		

			

			// If the version option doesn't exist, then add it.  Otherwise update it.

			if( FALSE === get_option( 'WP-Project Version' ) ) {

				add_option( 'WP-Project Version', $this->version );

			} else {

				update_option( 'WP-Project Version', $this->version );

			}

			

			if( FALSE === get_option( 'WP-Project Options' ) ) {

				add_option( 'WP-Project Options', serialize( array( 'current_timer' => -1, 'timer_started' => 0 ) ) );

			} else {

				update_option( 'WP-Project Options', serialize( array( 'current_timer' => -1, 'timer_started' => 0 ) ) );

			}

		}

		

		/**

		 * Completely remove all data and database tables concerned with the WP-Project plugin.  This function should be

		 * called only after the user is warned several times of what will happen if they proceed with this action.

		 * All data that they have entered will be erased permanently and will be unretrievable.

		 */

		function uninstall() {

			global $wpdb;

			$wpdb->show_errors();

			require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

			

			// Make sure the project table exists

			if( $wpdb->get_var( "SHOW TABLES LIKE '$this->project_table'" ) == $this->project_table ) {

				$wpdb->query( "DROP TABLE {$this->project_table}" );

			}

			

			// Make sure the client table exists

			if( $wpdb->get_var( "SHOW TABLES LIKE '$this->client_table'" ) == $this->client_table ) {

				$wpdb->query( "DROP TABLE {$this->client_table}" );

			}

			

			// Make sure the task table exists

			if( $wpdb->get_var( "SHOW TABLES LIKE '$this->task_table'" ) == $this->task_table ) {

				$wpdb->query( "DROP TABLE {$this->task_table}" );

			}

			

			// If the version option exists, delete it.

			if( FALSE !== get_option( 'WP-Project Version' ) ) {

				delete_option( 'WP-Project Version' );

			}

		}

		

		/**

		 * Upgrades the plugin from an old version by conditionally updating tables and doing some other sweet stuff.

		 *

		 * @param string $old_version The version number of the previous version that was installed.

		 */

		function upgrade( $old_version ) {

			switch( $old_version ) {

				case "0.0.1":

					$this->uninstall();

					$this->install();

					break;

				default:

					break;

			}

			

			update_option( 'WP-Project Version', $this->version );

		}

		

		/** 

		 * Prepare a backup of all data contained in the WP-Project tables.  A full SQL dump and a CSV dump are available. 

		 */

		function backup_data( $backup_type = 'cvs' ) {

			switch( $backup_type ) {

				case 'sql':

					

					break;

					

				// Perform a comma separated value data 

				case 'csv':

				default:

					

					break;

			}

		}

		

		// PROJECT

		

		/**

		 * Returns the number of projects currently in the system.

		 *

		 * @return int the number of projects currently active in the system.

		 */

		function project_count() {

			return count( $this->get_projects() );

		}

		

		/**

		 * Retrieves an optionally paginated list of projects.

		 * 

		 * @returns array An array of projects that are in the system.

		 */

		function get_projects( $page = null ) {

			global $wpdb;

			

			$query = "SELECT P.client_id, project_id, client_name, project_title, project_description FROM $this->project_table AS P, $this->client_table AS C WHERE P.client_id = C.client_id AND project_id >= 0";

			

			return $wpdb->get_results($query, OBJECT);

		}

		

		/**

		 * Returns a single project, as identified by its id.

		 *

		 * @param int $id the unique id for the project being searched for.

		 * @return array a project from the database.

		 */

		function get_project( $id ) {

			global $wpdb;

			

			$query = "SELECT project_id, project_title, project_description, P.client_id, client_name FROM $this->project_table P, $this->client_table C WHERE P.client_id = C.client_id AND project_id = " . $wpdb->escape( $id );

			

			return $wpdb->get_row( $query, ARRAY_A );

		}

		

		/**

		 * Validates a projects values.

		 *

		 * @param array $project_values the values for the project fields.

		 * @return array an array of error messages for the project.

		 */

		function validate_project( $project_values ) {

			$errors = array();

			

			if( strlen( $project_values[ 'project_title' ] ) <= 0 ) {

				$errors[] = 'Project name must be provided.  Please enter a project name.';

			}

			if( strlen( $project_values[ 'project_title' ] ) > 200 ) {

				$errors[] = 'Project name is too long.  Please limit project names to 200 characters.';	

			}



			if( strlen( $project_values[ 'project_description' ] ) > 2000 ) {

				$errors[] = 'Project description is too long.  Please limit project descriptions to 2000 characters.';

			}

			

			return $errors;

		}

		

		/**

		 * Adds a new project to the system.

		 *

		 * @param string $project_title the title the project should have after the update.

		 * @param int $client_id the unique id of the client that owns this project.

		 * @param string $project_description the description of the project.

		 * @return bool whether or not the addition operation was successful.

		 */

		function add_project( $title, $client, $description ) {

			global $wpdb;

			

			$title = $wpdb->escape( $title );

			$client = $wpdb->escape( $client );

			$description = $wpdb->escape( $description );

			

			$query = "INSERT INTO $this->project_table (project_title, project_description, client_id) VALUES ( '$title', '$description', $client )";

			

			return $wpdb->query( $query );

		}

		

		/**

		 * Edits an existing project.

		 *

		 * @param int $project_id the id of the existing project.

		 * @param string $project_title the title the project should have after the update.

		 * @param int $client_id the unique id of the client that owns this project.

		 * @param string $project_description the description of the project.

		 * @return bool whether or not the edit operation was successful.

		 */

		function edit_project( $id, $title, $client, $description ) {

			global $wpdb;

			

			$id = $wpdb->escape( $id );

			$title = $wpdb->escape( $title );

			$client = $wpdb->escape( $client );

			$description = $wpdb->escape( $description );

			

			$query = "UPDATE $this->project_table SET client_id = {$client}, project_title = '{$title}', project_description = '{$description}' WHERE project_id = {$id}";

			

			return $wpdb->query( $query );

		}

		

		/**

		 * Determines whether or not a project is currently being edited.

		 *

		 * @return array|bool an array describing the current project or false if a project isn't being edited.

		 */

		function is_editing_project( ) {

			if( $_GET[ 'action' ] == 'edit' && ( $project = $this->get_project( $_GET[ 'id' ] ) ) !== FALSE ) {

				return $project;

			} else {

				return FALSE;

			}

		}

		

		/**

		 * Deletes a single project and any associate tasks.

		 *

		 * @param int $id the unique identifier for the project.

		 */

		function delete_project( $id ) {

			global $wpdb;

			

			$wpdb->query( "DELETE FROM $this->project_table WHERE project_id = " . $wpdb->escape( $id ) );

			$wpdb->query( "DELETE FROM $this->task_table WHERE project_id = " . $wpdb->escape( $id ) );

		}

		

		/**

		 * Deletes a number of projects from the system.

		 *

		 * @param array $project_ids an array of integers.

		 */

		function delete_projects( $project_ids ) {

			global $wpdb;

			if( is_array( $project_ids ) ) {

				foreach( $project_ids as $id ) {

					$this->delete_project( $id );

				}

			}

		}

		

		// CLIENT

		

		/**

		 * Returns the number of clients currently in the system.

		 *

		 * @return int the number of clients currently in the system.

		 */

		function client_count() {

			return count( $this->get_clients() );

		}

		

		/**

		 * Retrieves an optionally paginated list of clients.

		 * 

		 * @return array An array of clients in the system.

		 */

		function get_clients( $page = null ) {

			global $wpdb;

			

			$query = "SELECT * FROM $this->client_table WHERE client_id >= 0";

			

			return $wpdb->get_results( $query, OBJECT );

		}

		

		/**

		 * Returns a single client, as identified by its id.

		 *

		 * @param int $id the id of the client to retrieve.

		 * @return array the client to be returned.

		 */

		function get_client( $id ) {

			global $wpdb;

			

			$query = "SELECT * FROM $this->client_table WHERE client_id = " . $wpdb->escape( $id );

			

			return $wpdb->get_row( $query, ARRAY_A );

		}

		

		/**

		 * Validates the values of a client declaration.

		 *

		 * @param array $client_values the values to store for a client.

		 */

		function validate_client( $client_values ) {

			$errors = array();

			

			if( strlen( $client_values[ 'client_name' ] ) <= 0 ) {

				$errors[] = 'Client name must be provided.  Please enter a name.';

			}

			

			if( strlen( $client_values[ 'client_name' ] ) > 200 ) {

				$errors[] = 'Client name is too long.  Please limit lient names to 200 characters.';

			}

			

			if( strlen( $client_values[ 'client_description' ] ) > 2000 ) {

				$errors[] = 'Client description is too long. Please limit client descriptions to 2000 characters.';

			}

			

			if( strlen( $client_values[ 'client_email' ] ) > 200 ) {

				$errors[] = 'Client email is too long. Please limit client email address to 200 characters.';

			}

			

			if( !is_email( $client_values[ 'client_email' ] ) ) {

				$errors[] = 'Client email is not valid.  Please enter a valid email address.';

			}

			

			if( strlen( $client_values[ 'client_site' ] ) > 200 ) {

				$errors[] = 'Client site address is too long.  Please limit client site addresses to 200 characters.';

			}

			

			return $errors;

		}

		

		/**

		 * Adds a new client to the system.

		 *

		 * @param string $name the name of the client.

		 * @param string $email the email of the client.

		 * @param string $site the client's web site.

		 * @param string $description the description of the client.

		 */

		function add_client( $name, $email, $site, $description ) {

			global $wpdb;

			

			$name = $wpdb->escape( $name );

			$email = $wpdb->escape( $email );

			$site = $wpdb->escape( $site );

			$description = $wpdb->escape( $description );

			

			$query = "INSERT INTO $this->client_table (client_name, client_email, client_site, client_description) VALUES( '$name', '$email', '$site', '$description' )";

			

			return $wpdb->query( $query );

		}

		

		/**

		 * Edits a currently existing client.

		 *

		 * @param int $id the unique identifier for the client being edited.

		 * @param string $name the name of the client.

		 * @param string $email the email of the client.

		 * @param string $site the client's web site.

		 * @param string $description the description of the client.

		 */

		function edit_client( $id, $name, $email, $site, $description ) {

			global $wpdb;



			$id = $wpdb->escape( $id );

			$name = $wpdb->escape( $name );

			$email = $wpdb->escape( $email );

			$site = $wpdb->escape( $site );

			$description = $wpdb->escape( $description );

			

			$query = "UPDATE $this->client_table SET client_name = '$name', client_email = '$email', client_site = '$site', client_description = '$description' WHERE client_id = $id";

			

			$wpdb->query( $query );

		}

		

		/**

		 * Determines whether or not a client is currently being edited.

		 *

		 * @return array the current client being edited.

		 */

		function is_editing_client( ) {

			if( $_GET[ 'action' ] == 'edit' && ( $client = $this->get_client( $_GET[ 'id' ] ) ) !== FALSE ) {

				return $client;

			} else {

				return FALSE;

			}

		}

		

		/**

		 * Deletes a single client entry and any associated projects.

		 *

		 * @param int $id the unique identifier for the client.

		 */

		function delete_client( $id ) {

			global $wpdb;

			$wpdb->query( "DELETE FROM $this->client_table WHERE client_id = " . $wpdb->escape ( $id ) );

			$projects = $wpdb->get_results( "SELECT project_id FROM $this->project_table P, $this->client_table C WHERE C.client_id = P.client_id", OBJECT );

			foreach( $projects as $project ) {

				$this->delete_project( $project->project_id );

			}

		}

		

		/**

		 * Deletes a number of clients from the system.

		 * 

		 * @param array the ids of clients that should be deleted.

		 */

		function delete_clients( $client_ids ) {

			if( is_array( $client_ids ) ) {

				foreach( $client_ids as $id ) {

					$this->delete_client( $id );;

				}

			}

		}

		

		// TASK

		

		/**

		 * Returns the number of tasks currently in the system, optionally for a specific project.

		 *

		 * @param int $project_id the id of the project to determine the number of tasks for.

		 */

		function task_count( $project_id = null ) {

			return count( $this->get_tasks( $project_id ) );

		}

		

		/**

		 * Retrieves an optionally paginated list of tasks for a particular project.

		 * 

		 * @return array An array of tasks.

		 */

		function get_tasks( $project_id = null, $page = null ) {

			global $wpdb;

			

			$query = "SELECT task_id, project_title, T.project_id, task_name, task_description, task_priority, task_time, task_status FROM $this->task_table T, $this->project_table P WHERE P.project_id = T.project_id";

			

			if( $project_id !== null ) {

				$query .= " AND T.project_id = " . $wpdb->escape( $project_id );

			}

			

			$query .= " ORDER BY task_priority DESC";

			

			return $wpdb->get_results( $query, OBJECT );

		}

		

		/**

		 * Returns a task object for the passed unique identifier

		 *

		 * @param int $id the unique identifier for the task.

		 * @return mixed FALSE if no task exists for the specified id and the task object otherwise.

		 */

		function get_task( $id ) {

			global $wpdb;

			

			$query = "SELECT task_id, project_title, T.project_id, task_name, task_description, task_priority, task_time, task_status FROM $this->task_table T, $this->project_table P WHERE P.project_id = T.project_id AND task_id = " . $wpdb->escape( $id );

			

			return $wpdb->get_row( $query, ARRAY_A );

		}

		

		/**

		 * Validates task values and returns an array of errors messages based on validation.

		 *

		 * @param array $task_values an array of task values to use to create or edit a task.

		 * @return array an array filled with any error messages generated.

		 */

		function validate_task( $task_values ) {

			$errors = array();

			

			if( !is_numeric( $task_values[ 'project_id' ] ) ) {

				$errors[] = 'Invalid project id.';

			}

			

			if( strlen( $task_values[ 'task_name' ] ) <= 0 ) {

				$errors[] = 'Task name must be provided.  Please enter a name.';

			}

			

			if( strlen( $task_values[ 'task_name' ] ) > 200 ) {

				$errors[] = 'Task name is too long.  Please limit task name to 200 characters.';

			}

			

			if( strlen( $task_values[ 'task_description' ] ) > 2000 ) {

				$errors[] = 'Task description is too long.  Please limit task description to 2000 characters.';

			}

			

			if( !is_numeric( $task_values[ 'task_priority' ] ) ) {

				$errors[] = 'Task priority must be be numeric.';

			}

			

			if( !is_numeric( $task_values[ 'task_time' ] ) ) {

				$errors[] = 'Task time must be numeric.';

			}

			

			if( strlen( $task_values[ 'task_status' ] ) > 100 ) {

				$errors[] = 'Task status is too long.  Please limit task status to 100 characters.';

			}

			

			return $errors;

		}

		

		/**

		 * Adds a task to the system.

		 *

		 * @param int $project_id the unique identifier for the project this task is concerned with.

		 * @param string $name the name of the task.

		 * @param string $description a brief description of the task.

		 * @param int $priority the numeric priority of the task.

		 * @param float $time the number of hours this task has taken so far.

		 * @param string $status the status of the task (complete, incomplete, needs testing)

		 */

		function add_task( $project_id, $name, $description, $priority, $time, $status ) {

			global $wpdb;

			

			$project_id = $wpdb->escape( $project_id );

			$name = $wpdb->escape( $name );

			$description = $wpdb->escape( $description );

			$priority = $wpdb->escape( $priority );

			$time = $wpdb->escape( $time );

			$status = $wpdb->escape( $status );



			$query = "INSERT INTO $this->task_table (project_id, task_name, task_description, task_priority, task_time, task_status) VALUES ($project_id, '$name', '$description', $priority, $time, '$status')";

			

			$wpdb->query( $query );

		}

		

		/**

		 * Edits an existing task.

		 * 

		 * @param int $id the unique identifier for the task being edited.

		 * @param int $project_id the unique identifier for the project this task is concerned with.

		 * @param string $name the name of the task.

		 * @param string $description a brief description of the task.

		 * @param int $priority the numeric priority of the task.

		 * @param float $time the number of hours this task has taken so far.

		 * @param string $status the status of the task (complete, incomplete, needs testing)

		 */

		function edit_task($id, $project_id, $name, $description, $priority, $time, $status ) {

			global $wpdb;

			

			$id = $wpdb->escape( $id );

			$project_id = $wpdb->escape( $project_id );

			$name = $wpdb->escape( $name );

			$description = $wpdb->escape( $description );

			$priority = $wpdb->escape( $priority );

			$time = $wpdb->escape( $time );

			$status = $wpdb->escape( $status );



			$query = "UPDATE $this->task_table SET project_id = $project_id, task_name = '$name', task_description = '$description', task_priority = $priority, task_time = $time, task_status = '$status' WHERE task_id = $id";

			

			$wpdb->query( $query );

		}

		

		/**

		 * Adds the specified number of seconds to the task identified by id.

		 *

		 * @param int $id the unique identifier for the task.

		 * @param int $seconds the number of seconds to add to the total time.

		 */

		function add_seconds_to_task( $id, $seconds ) {

			global $wpdb;

			

			$wpdb->query( "UPDATE $this->task_table SET task_time = task_time + " . $this->seconds_to_hours( $seconds ) . " WHERE task_id = " . $wpdb->escape( $id ) );

		}

		

		/**

		 * Checks the request variables to decide whether a valid client is being edited.

		 *

		 * @return mixed FALSE if a task is not being edited or the task object if one is being

		 * edited.

		 */

		function is_editing_task( ) {

			if( $_GET[ 'action' ] == 'edit' && ( $task = $this->get_task( $_GET[ 'id' ] ) ) !== FALSE ) {

				return $task;

			} else {

				return FALSE;

			}

		}

		

		/**

		 * Deletes a single task entry from the system.

		 *

		 * @param int $id the unique identifer for the task to delete.

		 */

		function delete_task( $id ) {

			global $wpdb;

			$wpdb->query( "DELETE FROM $this->task_table WHERE task_id = " . $wpdb->escape( $id ) );

		}

		

		/**

		 * Removes the tasks with specified Ids from the system.

		 *

		 * @param array $task_ids an array of task Ids to delete.

		 */

		function delete_tasks( $task_ids ) {

			if( is_array( $task_ids ) ) {

				foreach( $task_ids as $id ) {

					$this->delete_task( $id );

				}

			}

		}

	

		/**

		 * Returns an array of priority options.

		 */

		function priority_options() {

			return array( 5 => 'Critical', 4 => 'High', 3 => 'Normal', 2 => 'Low', 1 => 'Trivial' );

		}

		

		/**

		 * Converts seconds to hours.

		 *

		 * @param int $seconds

		 * @return float the amount of hours the seconds is equal to.

		 */

		function seconds_to_hours( $seconds ) {

			return $seconds / 3600;

		}

		

		/**

		 * The following functions are the display functions for the various pages in the WP-Project 

		 * plugin's interface.

		 */

		

		/**

		 * Displays the dashboard for the WP-Project plugin.

		 */

		function top_level_page() { 

			if( $this->is_installed ) {

				include( dirname( __FILE__ ) . '/pages/dashboard.php' );

			} else {

				echo '<div class="wrap"><p>WP-Project is uninstalled.  Please deactivate the plugin.</p></div>';

			}

		}

	

		/**

		 * Displays the project page for the WP-Project plugin.

		 */

		function project_page() { 

			include( dirname( __FILE__ ) . '/pages/projects.php' );

		}

	

		/**

		 * Displays the client page for the WP-Project plugin.

		 */

		function client_page() { 

			include( dirname( __FILE__ ) . '/pages/clients.php' );

		}

	

		/**

		 * Displays the task page for the WP-Project plugin.

		 */

		function task_page() { 

			include( dirname( __FILE__ ) . '/pages/tasks.php' );

		}

	

		/**

		 * Displays the billing page for the WP-Project plugin.

		 */

		function billing_page() { 

			include( dirname( __FILE__ ) . '/pages/billing.php' );

		}

	

		/**

		 * Displays the option page for the WP-Project plugin.

		 */

		function option_page() { 

			include( dirname( __FILE__ ) . '/pages/options.php' );

		}

				

		/**

		 * Displays the uninstall page for the WP-Project plugin.

		 */

		function uninstall_page() {

			include( dirname( __FILE__ ) . '/pages/uninstall.php' );

		}

		

		/**

		 * Displays the about page for the WP-Project plugin.

		 */

		function about_page() { 

			include( dirname( __FILE__ ) . '/pages/about.php' );

		}

		

		/**

		 * Displays the help page for the WP-Project plugin.

		 */

		function donate_page() { 

			include( dirname( __FILE__ ) . '/pages/donate.php' );

		}

		

		/**

		 * The following section contains display helpers.

		 */

		

		/**

		 * Truncates a string if it is longer than 125 characters.  Adds 

		 * ellipses if the string is longer than it should be.

		 *

		 * @param string $string the string to truncate.

		 * @return string the truncated string.

		 */

		function truncate( $string, $length = 125 ) {

			return ( strlen( $string ) > $length ? substr( $string, 0, $length ) . '...' : $string );

		}

		

		/**

		 * Prints paginated rows of projects for use in the project table.

		 *

		 * @param int $page the page to use for pagination.

		 */

		function project_rows( $page = null ) {

			$projects = $this->get_projects( $page );

			

			foreach( $projects as $project ) {

				$class = ( $class == 'alternate' ? '' : 'alternate' );

			?>

			<tr class="<?php echo $class; ?>" id="project_row-<?php echo $project->project_id; ?>">

				<th class="check-column" scope="row"><input id="project_cb-<?php echo $project->project_id; ?>" name="project_cb[<?php $project->project_id; ?>]" type="checkbox" value="<?php echo $project->project_id; ?>" /></th>

				<td><a href="<?php $this->friendly_page_link( 'projects' ); ?>&amp;action=edit&amp;id=<?php echo $project->project_id; ?>"><?php echo $project->project_title; ?></a></td>

				<td><a href="<?php $this->friendly_page_link( 'clients' ); ?>&amp;action=edit&amp;id=<?php echo $project->client_id; ?>"><?php echo $project->client_name; ?></a></td>

				<td><?php echo $this->truncate( $project->project_description ); ?></td>

			</tr>

			<?php	

			}

		}

		

		/**

		 * Prints paginated rows of clients for use in the client management table.

		 *

		 * @param int $page the page to print.

		 */

		function client_rows( $page = null ) {

			$clients = $this->get_clients( $page );

			

			foreach( $clients as $client ) {

				$class = ( $class == 'alternate' ? '' : 'alternate' );

			?>

			<tr class="<?php echo $class; ?>" id="client_ro-<?php echo $client->client_id; ?>">

				<th class="check-column" scope="row"><input id="client_cb-<?php echo $client->client_id; ?>" name="client_cb[<?php echo $client->client_id; ?>]" type="checkbox" value="<?php echo $client->client_id; ?>" /></th>

				<td><a href="<?php $this->friendly_page_link( 'clients' ); ?>&amp;action=edit&amp;id=<?php echo $client->client_id; ?>"><?php echo $client->client_name; ?></a></td>

				<td><a href="mailto:<?php echo $client->client_email; ?>"><?php echo $client->client_email; ?></a></td>

				<td><a href="<?php echo $client->client_site; ?>"><?php echo $this->truncate( $client->client_site, 55 ); ?></a></td>

				<td><?php echo $this->truncate( $client->client_description ); ?></td>

			</tr> 

			

			<?php	

			}

		}

		

		/**

		 * Prints paginated rows of tasks for use in the task management table.

		 *

		 * @param int $page the page to print.

		 */

		function task_rows( $project_id = null, $page = null ) {

			$tasks = $this->get_tasks( $project_id, $page );

			$priorities = $this->priority_options();



			$current_task_running = $this->options[ 'current_timer' ];

			

			foreach( $tasks as $task ) {

				$class = ( $class == 'alternate' ? '' : 'alternate' );

				?>

				<tr class="<?php echo $class; ?>" id="task_row-<?php echo $task->task_id; ?>">

					<th class="check-column" scope="row"><input id="task_cb-<?php echo $task->task_id; ?>" name="task_cb[<?php echo $task->task_id; ?>]" type="checkbox" value="<?php echo $task->task_id; ?>" /></th>

					<td><a href="<?php $this->friendly_page_link( 'tasks' ); ?>&amp;action=edit&amp;id=<?php echo $task->task_id; ?>"><?php echo $task->task_name; ?></a></td>

					<td><?php echo $this->truncate( $task->task_description ); ?></td>

					<td><a href="<?php $this->friendly_page_link( 'projects' ); ?>&amp;action=edit&amp;id=<?php echo $task->project_id; ?>"><?php echo $task->project_title; ?></a></td>

					<td><?php echo $priorities[ $task->task_priority ]; ?></td>

					<td><a id="timer_toggle_<?php echo $task->task_id; ?>" class="timer<?php if( $current_task_running == $task->task_id ) { echo " timer_on"; } ?>" title="<?php echo bloginfo( 'siteurl' ); ?>" rel="<?php echo $task->task_id; ?>" href="<?php $this->friendly_page_link( 'tasks' ); ?>&amp;action=start_timer&amp;id=<?php echo $task->task_id; ?>"></a><span id="timer_time_<?php echo $task->task_id; ?>"><?php printf( "%.2f", $task->task_time + ( $this->options[ 'current_timer' ] == $task->task_id ? ( $this->seconds_to_hours( time() - $this->options[ 'timer_started' ] ) ) : 0 ) ); ?></span> hr(s)</td>

					<td><?php echo $task->task_status; ?></td>

				</tr>

				<?php

			}

		}

		

		/**

		 * Returns or displays a friendly page slug.

		 *

		 * @param string $slug_id The string identifying the page to be referenced.

		 * @param bool $display Whether to return or display the value.

		 */

		function friendly_page_slug( $slug_id, $display = true ) {

			if( isset( $this->page_slugs[ $slug_id ] ) ) {

				$array = explode( '_page_', $this->page_slugs[ $slug_id ] );

				if( $display ) {

					echo $array[ 1 ];

				} else {

					return $array[ 1 ];

				}

			} else if( $slug_id == 'top_level' ) {

				if( $display ) {

					echo 'wp-project';

				} else {

					return 'wp-project';

				}

			}

		}

		

		/**

		 * Returns or display a friendly link between pages in WP-Project. 

		 *

		 * @param string $slug_id the id for the page to be displayed.

		 * @param bool $display Whether to display or return the value.

		 */

		function friendly_page_link( $slug_id, $display = true ) {

			$page_slug = $this->friendly_page_slug( $slug_id, false );

			

			$value = get_bloginfo('wpurl') . '/wp-admin/admin.php?page=' . $page_slug;

			if( $display ) {

				echo $value;

			} else {

				return $value;

			}

		}

			

	}



}

// Ensure the class exists before instantiating an object of this type

if( class_exists( 'WP_Project' ) ) {

	

	$wp_project = new WP_Project();

	

	// Activation and Deactivation

	register_activation_hook( __FILE__, array( &$wp_project, 'on_activate' ) );

	register_deactivation_hook( __FILE__, array( &$wp_project, 'on_deactivate' ) );

	

	// Actions

	add_action( 'admin_menu', array( &$wp_project, 'on_admin_menu' ) );

	add_action( 'admin_head', array( &$wp_project, 'on_admin_head' ) );

	add_action( 'init', array( &$wp_project, 'on_init' ) );

	add_action( 'wp_ajax_timer_toggle', array( &$wp_project, 'on_timer_toggle' ) );

	

	// Filters

	

}







?>