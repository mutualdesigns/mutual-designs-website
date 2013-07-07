<?php



global $wp_roles;

$user_roles = $wp_roles->get_names();



if ( $_POST ) {



    check_admin_referer('redirect-my-login');

    

    $uninstall = isset($_POST['uninstall']) ? true : false;

    

    foreach ($user_roles as $role => $value) {

        $login_redirect[$role] = clean_url(trim($_POST['login_redirect'][$role]));

    }

    

    $this->SetOption('uninstall', $uninstall);

    $this->SetOption('login_redirect', $login_redirect);

    $this->SaveOptions();



    if ($uninstall)

        $success = __('To complete uninstall, deactivate this plugin. If you do not wish to uninstall, please uncheck the "Complete Uninstall" checkbox.', 'redirect-my-login');

    else

        $success =__('Settings saved.', 'redirect-my-login');

}



$login_redirect = $this->GetOption('login_redirect');



?>



<div class="updated" style="background:aliceblue; border:1px solid lightblue">

    <p><?php _e('If you like this plugin, please help keep it up to date by <a href="http://www.jfarthing.com/donate">donating through PayPal</a>!', 'redirect-my-login'); ?></p>

</div>



<div class="wrap">

<?php if ( isset($success) && strlen($success) > 0 ) { ?>

    <div id="message" class="updated fade">

        <p><strong><?php echo $success; ?></strong></p>

    </div>

<?php } ?>

    <div id="icon-options-general" class="icon32"><br /></div>

    <h2><?php _e('Redirect My Login Settings', 'redirect-my-login'); ?></h2>



    <form action="" method="post" id="redirect-my-login-settings">

    <?php if ( function_exists('wp_nonce_field') ) wp_nonce_field('redirect-my-login'); ?>



    <h3><?php _e('General Settings', 'redirect-my-login'); ?></h3>

    <table class="form-table">

        <tr valign="top">

            <th scope="row"><label for="uninstall"><?php _e('Plugin', 'redirect-my-login'); ?></label></th>

            <td>

                <input name="uninstall" type="checkbox" id="uninstall" value="1" <?php if ($this->GetOption('uninstall')) { echo 'checked="checked"'; } ?> />

                <?php _e('Uninstall', 'redirect-my-login'); ?>

            </td>

        </tr>

    </table>



    <h3><?php _e('Redirection Settings', 'redirect-my-login'); ?></h3>

    <p class="setting-description"><?php _e('Leave blank to send a user back to the page that they logged in from.', 'redirect-my-login'); ?></p>

    <table class="form-table">

        <?php foreach ($user_roles as $role => $value) : ?>

        <tr valign="top">

            <th scope="row"><label for="login_redirect[<?php echo $role; ?>]"><?php echo ucwords($role); ?> <?php _e('Login Redirect', 'redirect-my-login'); ?></label></th>

            <td>

                <input name="login_redirect[<?php echo $role; ?>]" type="text" id="login_redirect[<?php echo $role; ?>]" value="<?php echo htmlspecialchars($login_redirect[$role]); ?>" class="regular-text" />

            </td>

        </tr>

        <?php endforeach; ?>

    </table>



    <p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />

    </form>

</div>

