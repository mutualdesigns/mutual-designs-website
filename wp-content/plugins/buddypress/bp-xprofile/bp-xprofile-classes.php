<?php



Class BP_XProfile_Group {

	var $id = null;

	var $name;

	var $description;

	var $can_delete;

	var $fields;



	function bp_xprofile_group( $id = null ) {

		global $bp, $wpdb;



		if ( $id ) {

			$this->populate( $id );

		}

	}



	function populate( $id ) {

		global $wpdb, $bp;



		$sql = $wpdb->prepare( "SELECT * FROM {$bp->profile->table_name_groups} WHERE id = %d", $id );



		if ( !$group = $wpdb->get_row($sql) )

			return false;



		$this->id = $group->id;

		$this->name = $group->name;

		$this->description = $group->description;

		$this->can_delete = $group->can_delete;

	}



	function save() {

		global $wpdb, $bp;



		$this->name = apply_filters( 'xprofile_group_name_before_save', $this->name, $this->id );

		$this->description = apply_filters( 'xprofile_group_description_before_save', $this->description, $this->id );



		do_action( 'xprofile_group_before_save', $this );



		if ( $this->id ) {

			$sql = $wpdb->prepare( "UPDATE {$bp->profile->table_name_groups} SET name = %s, description = %s WHERE id = %d", $this->name, $this->description, $this->id );

		} else {

			$sql = $wpdb->prepare( "INSERT INTO {$bp->profile->table_name_groups} (name, description, can_delete) VALUES (%s, %s, 1)", $this->name, $this->description );

		}



		if ( !$wpdb->query($sql) )

			return false;



		do_action( 'xprofile_group_after_save', $this );



		if ( $this->id )

			return $this->id;

		else

			return $wpdb->insert_id;

	}



	function delete() {

		global $wpdb, $bp;



		if ( !$this->can_delete )

			return false;



		$sql = $wpdb->prepare( "DELETE FROM {$bp->profile->table_name_groups} WHERE id = %d", $this->id );



		if ( !$wpdb->query($sql) ) {

			return false;

		} else {

			// Now the group is deleted, remove the group's fields.

			if ( BP_XProfile_Field::delete_for_group($this->id) ) {

				// Now delete all the profile data for the groups fields

				for ( $i = 0; $i < count($this->fields); $i++ ) {

					BP_XProfile_ProfileData::delete_for_field($this->fields[$i]->id);

				}

			}



			return true;

		}

	}



	/** Static Functions **/



	function get( $args = '' ) {

		global $wpdb, $bp;



		$defaults = array(

			'profile_group_id' => false,

			'user_id' => $bp->displayed_user->id,

			'hide_empty_groups' => false,

			'fetch_fields' => false,

			'fetch_field_data' => false

		);



		$r = wp_parse_args( $args, $defaults );

		extract( $r, EXTR_SKIP );



		if ( $profile_group_id )

			$group_id_sql = $wpdb->prepare( 'WHERE g.id = %d', $profile_group_id );



		if ( $hide_empty_groups )

			$groups = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT g.* FROM {$bp->profile->table_name_groups} g INNER JOIN {$bp->profile->table_name_fields} f ON g.id = f.group_id {$group_id_sql} ORDER BY g.id ASC" ) );

		else

			$groups = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT g.* FROM {$bp->profile->table_name_groups} g {$group_id_sql} ORDER BY g.id ASC" ) );



		if ( !$fetch_fields )

			return $groups;



		/* Get the group ids */

		foreach( (array)$groups as $group )

			$group_ids[] = $group->id;



		$group_ids = implode( ',', (array) $group_ids );



		if ( empty( $group_ids ) )

			return $groups;



		/* Fetch the fields */

		$fields = $wpdb->get_results( $wpdb->prepare( "SELECT id, name, description, type, group_id, is_required FROM {$bp->profile->table_name_fields} WHERE group_id IN ( {$group_ids} ) AND parent_id = 0 ORDER BY field_order" ) );



		if ( empty( $fields ) )

			return $groups;



		if ( $fetch_field_data ) {

			/* Fetch the field data for the user. */

			foreach( (array)$fields as $field )

				$field_ids[] = $field->id;



			$field_ids = implode( ',', (array) $field_ids );



			if ( !empty( $field_ids ) )

				$field_data = $wpdb->get_results( $wpdb->prepare( "SELECT field_id, value FROM {$bp->profile->table_name_data} WHERE field_id IN ( {$field_ids} ) AND user_id = %d", $user_id ) );



			if ( !empty( $field_data ) ) {

				foreach( (array)$fields as $field_key => $field ) {

					foreach( (array)$field_data as $data ) {

						if ( $field->id == $data->field_id )

							$fields[$field_key]->data->value = $data->value;

					}

				}

			}

		}



		/* Merge the field array back in with the group array */

		foreach( (array)$groups as $group_key => $group ) {

			foreach( (array)$fields as $field ) {

				if ( $group->id == $field->group_id )

					$groups[$group_key]->fields[] = $field;

			}

		}



		return $groups;

	}



	function admin_validate() {

		global $message;



		// Validate Form

		if ( empty( $_POST['group_name'] ) ) {

			$message = __('Please make sure you give the group a name.', 'buddypress');

			return false;

		} else {

			return true;

		}

	}



	// ADMIN AREA HTML. TODO: Get this out of here.



	function render_admin_form() {

		global $message;



		if ( !$this->id ) {

			$title = __('Add New Field Group', 'buddypress');

			$action = "admin.php?page=bp-profile-setup&amp;mode=add_group";

		} else {

			$title = __('Edit Field Group', 'buddypress');

			$action = "admin.php?page=bp-profile-setup&amp;mode=edit_group&amp;group_id=" . $this->id;

		}

	?>

		<div class="wrap">



			<h2><?php echo $title; ?></h2>

			<br />



			<?php

				if ( $message != '' ) {

					$type = ( 'error' == $type ) ? 'error' : 'updated';

			?>

				<div id="message" class="<?php echo $type; ?> fade">

					<p><?php echo $message; ?></p>

				</div>

			<?php } ?>



			<form action="<?php echo attribute_escape( $action ); ?>" method="post">



				<div id="titlediv">

					<label for="group_name"><?php _e( "Field Group Name", 'buddypress') ?></label>

					<div>

						<input type="text" name="group_name" id="group_name" value="<?php echo attribute_escape( $this->name ) ?>" style="width:50%" />

					</div>

				</div>



				<p class="submit" style="text-align: left">

					<input type="submit" name="saveGroup" value="<?php echo attribute_escape( $title ); ?> &rarr;" />

				</p>



			</form>

		</div>



		<?php

	}

}





Class BP_XProfile_Field {

	var $id;

	var $group_id;

	var $parent_id;

	var $type;

	var $name;

	var $description;

	var $is_required;

	var $can_delete;

	var $field_order;

	var $option_order;

	var $order_by;

	var $is_default_option;



	var $data;

	var $message = null;

	var $message_type = 'err';



	function bp_xprofile_field( $id = null, $user_id = null, $get_data = true ) {

		if ( $id ) {

			$this->populate( $id, $user_id, $get_data );

		}

	}



	function populate( $id, $user_id, $get_data ) {

		global $wpdb, $userdata, $bp;



		if ( is_null($user_id) ) {

			$user_id = $userdata->ID;

		}



		$sql = $wpdb->prepare( "SELECT * FROM {$bp->profile->table_name_fields} WHERE id = %d", $id );



		if ( $field = $wpdb->get_row($sql) ) {

			$this->id = $field->id;

			$this->group_id = $field->group_id;

			$this->parent_id = $field->parent_id;

			$this->type = $field->type;

			$this->name = stripslashes($field->name);

			$this->description = stripslashes($field->description);

			$this->is_required = $field->is_required;

			$this->can_delete = $field->can_delete;

			$this->field_order = $field->field_order;

			$this->option_order = $field->option_order;

			$this->order_by = $field->order_by;

			$this->is_default_option = $field->is_default_option;



			if ( $get_data ) {

				$this->data = $this->get_field_data($user_id);

			}

		}

	}



	function delete() {

		global $wpdb, $bp;



		if ( !$this->id ||

			// Prevent deletion by url when can_delete is false.

			!$this->can_delete ||

			// Prevent deletion of option 1 since this invalidates fields with options.

			( $this->parent_id && $this->option_order == 1 ) )

			return false;



		if ( !$wpdb->query( $wpdb->prepare( "DELETE FROM {$bp->profile->table_name_fields} WHERE id = %d OR parent_id = %d", $this->id, $this->id ) ) )

			return false;



		// delete the data in the DB for this field

		BP_XProfile_ProfileData::delete_for_field($this->id);



		return true;

	}



	function save() {

		global $wpdb, $bp;



		$error = false;



		$this->group_id = apply_filters( 'xprofile_field_group_id_before_save', $this->group_id, $this->id );

		$this->parent_id = apply_filters( 'xprofile_field_parent_id_before_save', $this->parent_id, $this->id );

		$this->type = apply_filters( 'xprofile_field_type_before_save', $this->type, $this->id );

		$this->name = apply_filters( 'xprofile_field_name_before_save', $this->name, $this->id );

		$this->description = apply_filters( 'xprofile_field_description_before_save', $this->description, $this->id );

		$this->is_required = apply_filters( 'xprofile_field_is_required_before_save', $this->is_required, $this->id );

		$this->order_by = apply_filters( 'xprofile_field_order_by_before_save', $this->order_by, $this->id );

		$this->field_order = apply_filters( 'xprofile_field_field_order_before_save', $this->field_order, $this->id );



		do_action( 'xprofile_field_before_save', $this );



		if ( $this->id != null )

			$sql = $wpdb->prepare("UPDATE {$bp->profile->table_name_fields} SET group_id = %d, parent_id = 0, type = %s, name = %s, description = %s, is_required = %d, order_by = %s, field_order = %d WHERE id = %d", $this->group_id, $this->type, $this->name, $this->description, $this->is_required, $this->order_by, $this->field_order, $this->id);

		else

			$sql = $wpdb->prepare("INSERT INTO {$bp->profile->table_name_fields} (group_id, parent_id, type, name, description, is_required, order_by, field_order ) VALUES (%d, %d, %s, %s, %s, %d, %s, %d )", $this->group_id, $this->parent_id, $this->type, $this->name, $this->description, $this->is_required, $this->order_by, $this->field_order );



		// Check for null so field options can be changed without changing any other part of the field.

		// The described situation will return 0 here.

		if ( $wpdb->query($sql) !== null ) {



			if ( $this->id )

				$field_id = $this->id;

			else

				$field_id = $wpdb->insert_id;



			// Only do this if we are editing an existing field

			if ( $this->id != null ) {

				// Remove any radio or dropdown options for this

				// field. They will be re-added if needed.

				// This stops orphan options if the user changes a

				// field from a radio button field to a text box.

				$this->delete_children();

			}



			// Check to see if this is a field with child options.

			// We need to add the options to the db, if it is.

			if ( 'radio' == $this->type || 'selectbox' == $this->type || 'checkbox' == $this->type || 'multiselectbox' == $this->type ) {

				if ( $this->id ) {

					$parent_id = $this->id;

				} else {

					$parent_id = $wpdb->insert_id;

				}



				if ( 'radio' == $this->type ) {



					$options = $_POST['radio_option'];

					$defaults = $_POST['isDefault_radio_option'];



				} else if ( 'selectbox' == $this->type ) {



					$options = $_POST['selectbox_option'];

					$defaults = $_POST['isDefault_selectbox_option'];



				} else if ( 'multiselectbox' == $this->type ) {



					$options = $_POST['multiselectbox_option'];

					$defaults = $_POST['isDefault_multiselectbox_option'];



				} else if ( 'checkbox' == $this->type ) {



					$options = $_POST['checkbox_option'];

					$defaults = $_POST['isDefault_checkbox_option'];



				}



				$counter = 1;

				if ( $options ) {

					foreach ( (array)$options as $option_key => $option_value ) {

						$is_default = 0;



						if ( is_array($defaults) ) {

							if ( isset($defaults[$option_key]) )

								$is_default = 1;

						} else {

							if ( (int) $defaults == $option_key )

								$is_default = 1;

						}



						if ( '' != $option_value ) {

							if ( !$wpdb->query( $wpdb->prepare("INSERT INTO {$bp->profile->table_name_fields} (group_id, parent_id, type, name, description, is_required, is_default_option, field_order, option_order, order_by, can_delete) VALUES (%d, %d, 'option', %s, '', 0, %d, 0, %d, '', 1)", (int)$this->group_id, (int)$parent_id, $option_value, (int)$is_default, (int)$counter ) ) )

								return false;

						}



						$counter++;

					}

				}

			}

		} else {

			$error = true;

		}



		if ( !$error ) {

			do_action( 'xprofile_field_after_save', $this );

			return $field_id;

		} else {

			return false;

		}

	}



	function get_field_data($user_id) {

		return new BP_XProfile_ProfileData($this->id, $user_id);

	}



	 function get_children($for_editing = false) {

		global $wpdb, $bp;



		// This is done here so we don't have problems with sql injection

		if ( 'asc' == $this->order_by && !$for_editing ) {

			$sort_sql = 'ORDER BY name ASC';

		} else if ( 'desc' == $this->order_by && !$for_editing ) {

			$sort_sql = 'ORDER BY name DESC';

		} else {

			$sort_sql = 'ORDER BY option_order ASC';

		}



		//This eliminates a problem with getting all fields when there is no id for the object

		if ( !$this->id ) {

			$parent_id = -1;

		} else {

			$parent_id = $this->id;

		}



		$sql = $wpdb->prepare( "SELECT * FROM {$bp->profile->table_name_fields} WHERE parent_id = %d AND group_id = %d $sort_sql", $parent_id, $this->group_id );



		if ( !$children = $wpdb->get_results($sql) )

			return false;



		return apply_filters( 'bp_xprofile_field_get_children', $children );

	}



	function delete_children() {

		global $wpdb, $bp;



		$sql = $wpdb->prepare("DELETE FROM {$bp->profile->table_name_fields} WHERE parent_id = %d", $this->id);



		$wpdb->query($sql);

	}



	// Static Functions



	function get_type( $field_id ) {

		global $wpdb, $bp;



		if ( $field_id ) {

			$sql = $wpdb->prepare( "SELECT type FROM {$bp->profile->table_name_fields} WHERE id = %d", $field_id );



			if ( !$field_type = $wpdb->get_var($sql) )

				return false;



			return $field_type;

		}



		return false;

	}



	function delete_for_group( $group_id ) {

		global $wpdb, $bp;



		if ( $group_id ) {

			$sql = $wpdb->prepare( "DELETE FROM {$bp->profile->table_name_fields} WHERE group_id = %d", $group_id );



			if ( $wpdb->get_var($sql) === false ) {

				return false;

			}



			return true;

		}



		return false;

	}



	function get_id_from_name( $field_name ) {

		global $wpdb, $bp;



		if ( !$bp->profile->table_name_fields || !$field_name )

			return false;



		return $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$bp->profile->table_name_fields} WHERE name = %s", $field_name ) );

	}



	function update_position( $field_id, $position ) {

		global $wpdb, $bp;



		if ( !is_numeric( $position ) )

			return false;



		return $wpdb->query( $wpdb->prepare( "UPDATE {$bp->profile->table_name_fields} SET field_order = %d WHERE id = %d", $position, $field_id ) );

	}



	// ADMIN AREA HTML. TODO: Get this out of here.



	function render_admin_form_children() {

		//This function populates the items for radio buttons checkboxes and drop down boxes

		$input_types = array( 'checkbox', 'selectbox', 'multiselectbox', 'radio' );



		foreach ($input_types as $type) {

			$default_name = '';



			if ( 'multiselectbox' == $type || 'checkbox' == $type ) {

				$default_input = 'checkbox';

			} else {

				$default_input = 'radio';

			}

		?>

			<div id="<?php echo $type ?>" class="options-box" style="<?php if ( $this->type != $type ) { ?>display: none;<?php } ?> margin-left: 15px;">

				<h4><?php _e('Please enter options for this Field:', 'buddypress') ?></h4>

				<p><?php _e( 'Order By:', 'buddypress' ) ?>



					<select name="sort_order_<?php echo $type ?>" id="sort_order_<?php echo $type ?>" >

						<option value="default" <?php if ( 'default' == $this->order_by ) {?> selected="selected"<?php } ?> ><?php _e( 'Order Entered', 'buddypress' ) ?></option>

						<option value="asc" <?php if ( 'asc' == $this->order_by ) {?> selected="selected"<?php } ?>><?php _e( 'Name - Ascending', 'buddypress' ) ?></option>

						<option value="desc" <?php if ( 'desc' == $this->order_by ) {?> selected="selected"<?php } ?>><?php _e( 'Name - Descending', 'buddypress' ) ?></option>

					</select>



				<?php

				if ( !$options = $this->get_children(true) ) {

					$i = 1;

					while ( isset( $_POST[$type . '_option'][$i] ) ) {

						(array) $options[] = (object) array(

							'id' => -1,

							'name' => $_POST[$type . '_option'][$i],

							'is_default_option' => ( 'multiselectbox' != $type &&

								'checkbox' != $type &&

								$_POST["isDefault_{$type}_option"] == $i ) ?

									1 :

									$_POST["isDefault_{$type}_option"][$i]

						);



						$i++;

					}

				}



				if ( !empty($options) ) {

					for ( $i = 0; $i < count($options); $i++ ) {

						$j = $i + 1;



						if ( 'multiselectbox' == $type || 'checkbox' == $type )

							$default_name = '[' . $j . ']';

					?>

						<p><?php _e('Option', 'buddypress') ?> <?php echo $j ?>:

						   <input type="text" name="<?php echo $type ?>_option[<?php echo $j ?>]" id="<?php echo $type ?>_option<?php echo $j ?>" value="<?php echo attribute_escape( $options[$i]->name ) ?>" />

						   <input type="<?php echo $default_input ?>" name="isDefault_<?php echo $type ?>_option<?php echo $default_name ?>" <?php if ( (int) $options[$i]->is_default_option ) {?> checked="checked"<?php } ?> " value="<?php echo $j ?>" /> <?php _e( 'Default Value', 'buddypress' ) ?>

							<?php if ( $j != 1 &&

								$options[$i]->id != -1 ) : ?><a href="admin.php?page=bp-profile-setup&amp;mode=delete_option&amp;option_id=<?php echo $options[$i]->id ?>" class="ajax-option-delete" id="delete-<?php echo $options[$i]->id ?>">[x]</a><?php endif ?></p>

						</p>

					<?php } // end for ?>

					<input type="hidden" name="<?php echo $type ?>_option_number" id="<?php echo $type ?>_option_number" value="<?php echo $j + 1 ?>" />



				<?php

				} else {

					if ( 'multiselectbox' == $type || 'checkbox' == $type )

						$default_name = '[1]';

				?>



					<p><?php _e('Option', 'buddypress') ?> 1: <input type="text" name="<?php echo $type ?>_option[1]" id="<?php echo $type ?>_option1" />

					<input type="<?php echo $default_input ?>" name="isDefault_<?php echo $type ?>_option<?php echo $default_name; ?>" id="isDefault_<?php echo $type ?>_option" value="1" /> <?php _e( 'Default Value', 'buddypress' ) ?>

					<input type="hidden" name="<?php echo $type ?>_option_number" id="<?php echo $type ?>_option_number" value="2" />



				<?php } // end if ?>

				<div id="<?php echo $type ?>_more"></div>

				<p><a href="javascript:add_option('<?php echo $type ?>')"><?php _e('Add Another Option', 'buddypress') ?></a></p>

			</div>



		<?php }

	}



	function render_admin_form( $message = '' ) {

		if ( !$this->id ) {

			$title = __('Add Field', 'buddypress');

			$action = "admin.php?page=bp-profile-setup&amp;group_id=" . $this->group_id . "&amp;mode=add_field";



			$this->name			= $_POST['title'];

			$this->description	= $_POST['description'];

			$this->is_required	= $_POST['required'];

			$this->type			= $_POST['fieldtype'];

			$this->order_by		= $_POST["sort_order_{$this->type}"];

		} else {

			$title = __('Edit Field', 'buddypress');

			$action = "admin.php?page=bp-profile-setup&amp;mode=edit_field&amp;group_id=" . $this->group_id . "&amp;field_id=" . $this->id;

		}

	?>



	<div class="wrap">



		<h2><?php echo $title; ?></h2>

		<br />



		<?php

			if ( $message != '' ) {

		?>

			<div id="message" class="error fade">

				<p><?php echo $message; ?></p>

			</div>

		<?php } ?>



		<form action="<?php echo $action ?>" method="post">

			<div id="poststuff">

				<div id="titlediv">

					<h3><label for="title"><?php _e("Field Title", 'buddypress') ?> *</label></h3>

					<div id="titlewrap">

						<input type="text" name="title" id="title" value="<?php echo attribute_escape( $this->name ) ?>" style="width:50%" />

					</div>

				</div>



				<div id="titlediv" class="inside">

					<h3><label for="description"><?php _e("Field Description", 'buddypress') ?></label></h3>

					<div id="titlewrap">

						<textarea name="description" id="description" rows="8" cols="60"><?php echo htmlspecialchars( $this->description ); ?></textarea>

					</div>

				</div>



				<div id="titlediv">

					<h3><label for="required"><?php _e("Is This Field Required?", 'buddypress') ?> *</label></h3>

					<select name="required" id="required" style="width: 30%">

						<option value="0"<?php if ( $this->is_required == '0' ) { ?> selected="selected"<?php } ?>><?php _e( 'Not Required', 'buddypress' ) ?></option>

						<option value="1"<?php if ( $this->is_required == '1' ) { ?> selected="selected"<?php } ?>><?php _e( 'Required', 'buddypress' ) ?></option>

					</select>

				</div>



				<div id="titlediv">

					<h3><label for="fieldtype"><?php _e("Field Type", 'buddypress') ?> *</label></h3>

					<select name="fieldtype" id="fieldtype" onchange="show_options(this.value)" style="width: 30%">

						<option value="textbox"<?php if ( $this->type == 'textbox' ) {?> selected="selected"<?php } ?>><?php _e( 'Text Box', 'buddypress' ) ?></option>

						<option value="textarea"<?php if ( $this->type == 'textarea' ) {?> selected="selected"<?php } ?>><?php _e( 'Multi-line Text Box', 'buddypress' ) ?></option>

						<option value="datebox"<?php if ( $this->type == 'datebox' ) {?> selected="selected"<?php } ?>><?php _e( 'Date Selector', 'buddypress' ) ?></option>

						<option value="radio"<?php if ( $this->type == 'radio' ) {?> selected="selected"<?php } ?>><?php _e( 'Radio Buttons', 'buddypress' ) ?></option>

						<option value="selectbox"<?php if ( $this->type == 'selectbox' ) {?> selected="selected"<?php } ?>><?php _e( 'Drop Down Select Box', 'buddypress' ) ?></option>

						<option value="multiselectbox"<?php if ( $this->type == 'multiselectbox' ) {?> selected="selected"<?php } ?>><?php _e( 'Multi Select Box', 'buddypress' ) ?></option>

						<option value="checkbox"<?php if ( $this->type == 'checkbox' ) {?> selected="selected"<?php } ?>><?php _e( 'Checkboxes', 'buddypress' ) ?></option>

					</select>

				</div>



				<?php $this->render_admin_form_children() ?>



				<p class="submit">

						&nbsp;<input type="submit" value="<?php _e("Save", 'buddypress') ?> &rarr;" name="saveField" id="saveField" style="font-weight: bold" />

						 <?php _e('or', 'buddypress') ?> <a href="admin.php?page=bp-profile-setup" style="color: red"><?php _e( 'Cancel', 'buddypress' ) ?></a>

				</p>



			<div class="clear"></div>



			<?php if ( function_exists('wp_nonce_field') )

				wp_nonce_field('xprofile_delete_option');

			?>



		</form>

	</div>



	<?php

	}



	function admin_validate() {

		global $message;



		// Validate Form

		if ( '' == $_POST['title'] || '' == $_POST['required'] || '' == $_POST['fieldtype'] ) {

			$message = __('Please make sure you fill out all required fields.', 'buddypress');

			return false;

		} else if ( empty($_POST['field_file']) && $_POST['fieldtype'] == 'radio' && empty($_POST['radio_option'][1]) ) {

			$message = __('Radio button field types require at least one option. Please add options below.', 'buddypress');

			return false;

		} else if ( empty($_POST['field_file']) && $_POST['fieldtype'] == 'selectbox' && empty($_POST['selectbox_option'][1]) ) {

			$message = __('Select box field types require at least one option. Please add options below.', 'buddypress');

			return false;

		} else if ( empty($_POST['field_file']) && $_POST['fieldtype'] == 'multiselectbox' && empty($_POST['multiselectbox_option'][1]) ) {

			$message = __('Select box field types require at least one option. Please add options below.', 'buddypress');

			return false;

		} else if ( empty($_POST['field_file']) && $_POST['fieldtype'] == 'checkbox' && empty($_POST['checkbox_option'][1]) ) {

			$message = __('Checkbox field types require at least one option. Please add options below.', 'buddypress');

			return false;

		} else {

			return true;

		}

	}

}





Class BP_XProfile_ProfileData {

	var $id;

	var $user_id;

	var $field_id;

	var $value;

	var $last_updated;



	function bp_xprofile_profiledata( $field_id = null, $user_id = null ) {

		if ( $field_id ) {

			$this->populate( $field_id, $user_id );

		}

	}



	function populate( $field_id, $user_id )  {

		global $wpdb, $bp;



		$sql = $wpdb->prepare( "SELECT * FROM {$bp->profile->table_name_data} WHERE field_id = %d AND user_id = %d", $field_id, $user_id );



		if ( $profiledata = $wpdb->get_row($sql) ) {



			$this->id = $profiledata->id;

			$this->user_id = $profiledata->user_id;

			$this->field_id = $profiledata->field_id;

			$this->value = stripslashes($profiledata->value);

			$this->last_updated = $profiledata->last_updated;

		}

	}



	function exists() {

		global $wpdb, $bp;



		// check to see if there is data already for the user.

		$sql = $wpdb->prepare( "SELECT id FROM {$bp->profile->table_name_data} WHERE user_id = %d AND field_id = %d", $this->user_id, $this->field_id );



		if ( !$wpdb->get_row($sql) )

			return false;



		return true;

	}



	function is_valid_field() {

		global $wpdb, $bp;



		// check to see if this data is actually for a valid field.

		$sql = $wpdb->prepare("SELECT id FROM {$bp->profile->table_name_fields} WHERE id = %d", $this->field_id );



		if ( !$wpdb->get_row($sql) )

			return false;



		return true;

	}



	function save() {

		global $wpdb, $bp;



		$this->user_id = apply_filters( 'xprofile_data_user_id_before_save', $this->user_id, $this->id );

		$this->field_id = apply_filters( 'xprofile_data_field_id_before_save', $this->field_id, $this->id );

		$this->value = apply_filters( 'xprofile_data_value_before_save', $this->value, $this->id );

		$this->last_updated = apply_filters( 'xprofile_data_last_updated_before_save', date( 'Y-m-d H:i:s' ), $this->id );



		do_action( 'xprofile_data_before_save', $this );



		if ( $this->is_valid_field() ) {

			if ( $this->exists() && !empty( $this->value ) && strlen( trim( $this->value ) ) ) {

				$result = $wpdb->query( $wpdb->prepare( "UPDATE {$bp->profile->table_name_data} SET value = %s, last_updated = %s WHERE user_id = %d AND field_id = %d", $this->value, $this->last_updated, $this->user_id, $this->field_id ) );

			} else if ( $this->exists() && empty( $this->value ) ) {

				// Data removed, delete the entry.

				$result = $this->delete();

			} else {

				$result = $wpdb->query( $wpdb->prepare("INSERT INTO {$bp->profile->table_name_data} (user_id, field_id, value, last_updated) VALUES (%d, %d, %s, %s)", $this->user_id, $this->field_id, $this->value, $this->last_updated ) );

			}



			if ( !$result )

				return false;



			do_action( 'xprofile_data_after_save', $this );



			return true;

		}



		return false;

	}



	function delete() {

		global $wpdb, $bp;



		if ( !$wpdb->query( $wpdb->prepare( "DELETE FROM {$bp->profile->table_name_data} WHERE field_id = %d AND user_id = %d", $this->field_id, $this->user_id ) ) )

			return false;



		return true;

	}



	/** Static Functions **/



	/**

	 * BP_XProfile_ProfileData::get_all_for_user()

	 *

	 * Get all of the profile information for a specific user.

	 */

	function get_all_for_user( $user_id ) {

		global $wpdb, $bp;



		$results = $wpdb->get_results( $wpdb->prepare( "SELECT g.id as field_group_id, g.name as field_group_name, f.id as field_id, f.name as field_name, f.type as field_type, d.value as field_data, u.user_login, u.user_nicename, u.user_email FROM {$bp->profile->table_name_groups} g LEFT JOIN {$bp->profile->table_name_fields} f ON g.id = f.group_id INNER JOIN {$bp->profile->table_name_data} d ON f.id = d.field_id LEFT JOIN {$wpdb->users} u ON d.user_id = u.ID WHERE d.user_id = %d AND d.value != ''", $user_id ) );



		if ( $results ) {

			$profile_data['user_login'] = $results[0]->user_login;

			$profile_data['user_nicename'] = $results[0]->user_nicename;

			$profile_data['user_email'] = $results[0]->user_email;



			foreach( (array) $results as $field ) {

				$profile_data[$field->field_name] = array(

					'field_group_id' => $field->field_group_id,

					'field_group_name' => $field->field_group_name,

					'field_id' => $field->field_id,

					'field_type' => $field->field_type,

					'field_data' => $field->field_data

				);

			}

		}



		return $profile_data;

	}



	function get_value_byid( $field_id, $user_ids = null ) {

		global $wpdb, $bp;



		if ( !$user_ids )

			$user_ids = $bp->displayed_user->id;



		if ( !$bp->profile )

			xprofile_setup_globals();



		if ( is_array( $user_ids ) ) {

			$user_ids = implode( ',', (array)$user_ids );

			$data = $wpdb->get_results( $wpdb->prepare( "SELECT user_id, value FROM {$bp->profile->table_name_data} WHERE field_id = %d AND user_id IN ({$user_ids})", $field_id ) );

		} else

			$data = $wpdb->get_var( $wpdb->prepare( "SELECT value FROM {$bp->profile->table_name_data} WHERE field_id = %d AND user_id = %d", $field_id, $user_ids ) );



		return $data;

	}



	function get_value_byfieldname( $fields, $user_id = null ) {

		global $bp, $wpdb;



		if ( !$fields )

			return false;



		if ( !$user_id )

			$user_id = $bp->displayed_user->id;



		if ( !$bp->profile )

			xprofile_setup_globals();



		$field_sql = '';



		if ( is_array($fields) ) {

			for ( $i = 0; $i < count($fields); $i++ ) {

				if ( $i == 0 )

					$field_sql .= $wpdb->prepare( "AND ( f.name = %s ", $fields[$i] );

				else

					$field_sql .= $wpdb->prepare( "OR f.name = %s ", $fields[$i] );

			}



			$field_sql .= ')';

		} else {

			$field_sql .= $wpdb->prepare( "AND f.name = %s", $fields );

		}



		$sql = $wpdb->prepare( "SELECT d.value, f.name FROM {$bp->profile->table_name_data} d, {$bp->profile->table_name_fields} f WHERE d.field_id = f.id AND d.user_id = %d AND f.parent_id = 0 $field_sql", $user_id );



		if ( !$values = $wpdb->get_results($sql) )

			return false;



		$new_values = array();



		if ( is_array($fields) ) {

			for ( $i = 0; $i < count($values); $i++ ) {

				for ( $j = 0; $j < count($fields); $j++ ) {

					if ( $values[$i]->name == $fields[$j] ) {

						$new_values[$fields[$j]] = $values[$i]->value;

					} else if ( !array_key_exists( $fields[$j], $new_values ) ) {

						$new_values[$fields[$j]] = NULL;

					}

				}

			}

		} else {

			$new_values = $values[0]->value;

		}



		return $new_values;

	}



	function delete_for_field( $field_id ) {

		global $wpdb, $userdata, $bp;



		if ( !$wpdb->query( $wpdb->prepare( "DELETE FROM {$bp->profile->table_name_data} WHERE field_id = %d", $field_id ) ) )

			return false;



		return true;

	}



	function get_last_updated( $user_id ) {

		global $wpdb, $bp;



		$last_updated = $wpdb->get_var( $wpdb->prepare( "SELECT last_updated FROM {$bp->profile->table_name_data} WHERE user_id = %d ORDER BY last_updated LIMIT 1", $user_id ) );



		return $last_updated;

	}



	function delete_data_for_user( $user_id ) {

		global $wpdb, $bp;



		return $wpdb->query( $wpdb->prepare( "DELETE FROM {$bp->profile->table_name_data} WHERE user_id = %d", $user_id ) );

	}



	function get_random( $user_id, $exclude_fullname ) {

		global $wpdb, $bp;



		if ( $exclude_fullname )

			$exclude_sql = $wpdb->prepare( " AND pf.id != 1" );



		return $wpdb->get_results( $wpdb->prepare( "SELECT pf.type, pf.name, pd.value FROM {$bp->profile->table_name_data} pd INNER JOIN {$bp->profile->table_name_fields} pf ON pd.field_id = pf.id AND pd.user_id = %d {$exclude_sql} ORDER BY RAND() LIMIT 1", $user_id ) );

	}



	function get_fullname( $user_id = false ) {

		global $bp;



		if ( !$user_id )

			$user_id = $bp->displayed_user->id;



		$data = xprofile_get_field_data( BP_XPROFILE_FULLNAME_FIELD_NAME, $user_id );



		return $data[BP_XPROFILE_FULLNAME_FIELD_NAME];

	}

}

?>

