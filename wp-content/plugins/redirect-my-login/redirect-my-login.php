<?php

/*

Plugin Name: Redirect My Login

Plugin URI: http://www.jfarthing.com/wordpress-plugins/redirect-my-login-plugin

Description: Allows you to set login redirection based upon user role.

Version: 1.0.5

Author: Jeff Farthing

Author URI: http://www.jfarthing.com

*/



global $wp_version;



if ($wp_version < '2.6') {

    if ( !defined('WP_CONTENT_DIR') )

        define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );

    if ( !defined('WP_CONTENT_URL') )

        define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');

    if ( !defined('WP_PLUGIN_DIR') )

        define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

    if ( !defined('WP_PLUGIN_URL') )

        define( 'WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins' );

        

    require (WP_PLUGIN_DIR . '/redirect-my-login/includes/compat.php');

}



if (!class_exists('RedirectMyLogin')) {

    class RedirectMyLogin {



        var $version = '1.0.5';

        var $options = array();

        var $permalink = '';



        function RedirectMyLogin() {

            $this->__construct();

        }



        function __construct() {

        

            load_plugin_textdomain('redirect-my-login', '/wp-content/plugins/redirect-my-login/language');



            register_activation_hook ( __FILE__, array( &$this, 'Activate' ) );

            register_deactivation_hook ( __FILE__, array( &$this, 'Deactivate' ) );

            

            add_action('admin_menu', array(&$this, 'AddAdminPage'));

            

            add_filter('login_redirect', array(&$this, 'LoginRedirect'), 10, 3);

            

            $this->LoadOptions();

        }



        function Activate() {

            global $wp_roles;



            $user_roles = $wp_roles->get_names();



            foreach ($user_roles as $role => $value) {

                $login_redirect[$role] = admin_url();

            }

            

            $this->SetOption('version', $this->version);

            $this->SetOption('login_redirect', $login_redirect);

            $this->SaveOptions();

        }



        function Deactivate() {

            if ($this->GetOption('uninstall')) {

                delete_option('redirect_my_login');

            }

        }



        function InitOptions() {

            $this->options['uninstall']         = 0;

            $this->options['login_redirect']    = '';

        }



        function LoadOptions() {



            $this->InitOptions();



            $storedoptions = get_option( 'redirect_my_login' );

            if ( $storedoptions && is_array( $storedoptions ) ) {

                foreach ( $storedoptions as $key => $value ) {

                    $this->options[$key] = $value;

                }

            } else update_option( 'redirect_my_login', $this->options );

        }



        function GetOption( $key ) {

            if ( array_key_exists( $key, $this->options ) ) {

                return $this->options[$key];

            } else return null;

        }



        function SetOption( $key, $value ) {

            $this->options[$key] = $value;

        }



        function SaveOptions() {

            $oldvalue = get_option( 'redirect_my_login' );

            if( $oldvalue == $this->options ) {

                return true;

            } else return update_option( 'redirect_my_login', $this->options );

        }



        function AddAdminPage(){

            add_options_page(__('Redirect My Login', 'redirect-my-login'), __('Redirect My Login', 'redirect-my-login'), 8, 'redirect-my-login', array(&$this, 'AdminPage'));

        }



        function AdminPage(){

            require (WP_PLUGIN_DIR . '/redirect-my-login/includes/admin-page.php');

        }

        

        function LoginRedirect($redirect_to, $request, $user, $post_from = 'page') {

            global $pagenow;

            

            $schema = ( isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https://' : 'http://';

            $self =  $schema . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

            

            if ( empty($redirect_to) || admin_url() == $redirect_to) {

                if ( empty($request) )

                    $redirect_to = ('wp-login.php' == $pagenow || 'page' == $post_from) ? $_SERVER['HTTP_REFERER'] : $self;

                else

                    $redirect_to = $request;

            }

            

            if ( is_object($user) && !is_wp_error($user) ) {

                $user_role = reset($user->roles);

                $login_redirect = $this->GetOption('login_redirect');

                if ( '' != $login_redirect[$user_role] )

                    $redirect_to = $login_redirect[$user_role];

            }



            return $redirect_to;

        }

    }

}



//instantiate the class

if (class_exists('RedirectMyLogin')) {

    $RedirectMyLogin = new RedirectMyLogin();

}



?>

