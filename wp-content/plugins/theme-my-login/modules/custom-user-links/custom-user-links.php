<?php

/*

Plugin Name: Custom User Links

Description: Enabling this module will initialize custom user links. You will then have to configure the settings via the "User Links" tab.

*/



if ( !class_exists( 'Theme_My_Login_Custom_User_Links' ) ) :

/**

 * Theme My Login Custom User Links module class

 *

 * Adds the ability to define custom links to display to a user when logged in based upon their "user role".

 *

 * @since 6.0

 */

class Theme_My_Login_Custom_User_Links extends Theme_My_Login_Module {

	/**

	 * Gets the user links for the current user's role

	 *

	 * Callback for "tml_user_links" hook in method Theme_My_Login_Template::display()

	 *

	 * @see Theme_My_Login_Template::display()

	 * @since 6.0

	 * @access public

	 *

	 * @param array $links Default user links

	 * @return array New user links

	 */

	function get_user_links( $links = array() ) {



		if ( !is_user_logged_in() )

			return $links;



		$current_user = wp_get_current_user();



		foreach( (array) $current_user->roles as $role ) {

			if ( isset( $this->theme_my_login->options['user_links'][$role] ) ) {

				$links = $this->theme_my_login->options['user_links'][$role];

				break;

			}

		}



		// Allow for user_id variable in link

		foreach ( (array) $links as $key => $link ) {

			$links[$key]['url'] = str_replace( '%user_id%', $current_user->ID, $link['url'] );

		}



		return $links;

	}



	/**

	 * Activates this module

	 *

	 * Callback for "tml_activate_custom-user-links/custom-user-links.php" hook in method Theme_My_Login_Admin::activate_module()

	 *

	 * @see Theme_My_Login_Admin::activate_module()

	 * @since 6.0

	 * @access public

	 *

	 * @param object $theme_my_login Reference to global $theme_my_login object

	 */

	function activate( &$theme_my_login ) {

		$options = $this->init_options();

		if ( !isset( $theme_my_login->options['user_links'] ) ) {

			$theme_my_login->options['user_links'] = $options['user_links'];

		} else {

			$theme_my_login->options['user_links'] = $theme_my_login->array_merge_recursive( $options['user_links'], $theme_my_login->options['user_links'] );

		}

	}



	/**

	 * Initializes options for this module

	 *

	 * Callback for "tml_init_options" hook in method Theme_My_Login_Base::init_options()

	 *

	 * @see Theme_My_Login_Base::init_options()

	 * @since 6.0

	 * @access public

	 *

	 * @param array $options Options passd in from filter

	 * @return array Original $options array with module options appended

	 */

	function init_options( $options = array() ) {

		global $wp_roles;



		if ( empty( $wp_roles ) )

			$wp_roles =& new WP_Roles();



		$options = (array) $options;



		$options['user_links'] = array();

		foreach ( $wp_roles->get_names() as $role => $label ) {

			if ( 'pending' == $role )

				continue;

			$options['user_links'][$role] = array(

				array( 'title' => __( 'Dashboard', $this->theme_my_login->textdomain ), 'url' => admin_url() ),

				array( 'title' => __( 'Profile', $this->theme_my_login->textdomain ), 'url' => admin_url( 'profile.php' ) )

			);

		}

		return $options;

	}



	/**

	 * Loads the module

	 *

	 * @since 6.0

	 * @access public

	 */

	function load() {

		add_action( 'tml_activate_custom-user-links/custom-user-links.php', array( &$this, 'activate' ) );

		add_filter( 'tml_init_options', array( &$this, 'init_options' ) );

		add_filter( 'tml_user_links', array( &$this, 'get_user_links' ) );

	}

}



/**

 * Holds the reference to Theme_My_Login_Custom_User_Links object

 * @global object $theme_my_login_custom_user_links

 * @since 6.0

 */

$theme_my_login_custom_user_links = new Theme_My_Login_Custom_User_Links();



if ( is_admin() )

	include_once( TML_ABSPATH. '/modules/custom-user-links/admin/custom-user-links-admin.php' );



endif; // Class exists



?>