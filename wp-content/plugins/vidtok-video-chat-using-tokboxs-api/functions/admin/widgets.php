<?php


/* VIDTOK WIDGET 
/*---------------------------*/

	function vidtok_widget()
		{
			
			/*REGISTER WIDGET*/
				register_widget('Vidtok_widget');
			
		}

/*  DEFINE CLASS EXTENDS
/*---------------------------*/

	class Vidtok_widget extends WP_Widget {
		
		/*CONSTRUCTOR*/
			function __construct ()
				{
					
					/*PARENT CONTRUCTOR*/
						parent::__construct('vidtok_widget', 'Vidtok: Widget', array('description' => 'The Vidtok Widget gives you the ability to allow your website users access to create the specific types of video chats you choose.'));
			
				}
		
		/*WIDGET*/
			function widget($args, $instance){		
				
				/*VARIABLES*/
					extract($args);
					
				/*OPTIONS*/
					$options = get_option('vidtok_options');	
				
				/*CONFIGURATION*/
					$widget_title 	= $instance['widget_title'];
					$individual		= $instance['individual'];
					$broadcasting	= $instance['broadcasting'];
					$group			= $instance['group'];
					$archive		= $instance['archive'];					
			
				
				/*DISPLAY WIDGET*/
				
					/*WIDGET TITLE*/		
						echo $before_widget;
						echo $before_title;
						echo apply_filters( 'widget_title', $widget_title );
						echo $after_title; 
					
					/*DISPLAY VIDTOK BUTTONS*/
						echo '<table border="0">';
						
						if($individual == 'yes'){ ?> 
						 
                          <tr>
                            <td width="40" align="left"><a href="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/actions/create.php?type=individual" target="_blank"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-128/user.png" alt="" width="24" height="24"></a></td>
                            <td><a href="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/actions/create.php?type=individual" target="_blank">1:1 Video Chat</a></td>
                          </tr> 							
						
                        <?php }  
						if($broadcasting == 'yes'){ ?>
						
                          <tr>
                            <td width="40" align="left"><a href="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/actions/create.php?type=broadcast" target="_blank"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-128/broadcasting.png" width="24" height="24"></a></td>
                            <td><a href="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/actions/create.php?type=broadcast" target="_blank">Broadcast</a></td>
                          </tr> 							
						
                        <?php } 
						
						if($options['vsubscription'] != 'free'){						
							if($group == 'yes'){ ?>
							
							  <tr>
								<td width="40" align="left"><a href="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/actions/create.php?type=group" target="_blank"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-128/group.png" width="24" height="24"></a></td>
								<td><a href="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/actions/create.php?type=group" target="_blank">Group Video Chat</a></td>
							  </tr> 
							
							<?php } 
						}
						
						if($options['vsubscription'] == 'advanced' || $options['vsubscription'] == 'premium'){
							if($archive == 'yes'){ ?>
							
							  <tr>
								<td width="40" align="left"><a href="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/actions/create.php?type=archive" target="_blank"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-128/record.png" width="24" height="24"></a></td>
								<td><a href="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/actions/create.php?type=archive" target="_blank">Archive Recording</a></td>
							  </tr> 
							
							<?php }  
						} 
							 
						echo '</table>'; 
						                      
					
					/*WIDGET END*/
						echo $after_widget;  
			
			}
			
					
		
		/*UPDATE*/
			function update($new_instance, $old_instance)
				{
					
					/*VARIABLES*/
						$instance = $old_instance;
						
						$instance['widget_title'] 	= strip_tags($new_instance['widget_title']);
						$instance['individual']		= strip_tags($new_instance['individual']);
						$instance['broadcasting']	= strip_tags($new_instance['broadcasting']);
						$instance['group']			= strip_tags($new_instance['group']);
						$instance['archive']		= strip_tags($new_instance['archive']);
					
					/*UPDATE*/
						return $instance;
						
				}
		

		
		/*FORM*/
			function form($instance)
				{
					
					/*PREVIOUS VALUES*/	
						$widget_title 	= (!empty($instance['widget_title'])  ? esc_attr($instance['widget_title'] ) : 'Vidtok: Widget' ); 
						$individual		= (!empty($instance['individual']) ? $instance['individual'] : 'yes');
						$broadcasting	= (!empty($instance['broadcasting']) ? $instance['broadcasting'] : 'yes');
						$group			= (!empty($instance['group']) ? $instance['group'] : 'yes');
						$archive		= (!empty($instance['archive']) ? $instance['archive'] : 'no');

					
				?>
					<!--DISPLAY FORM-->
                        <p><label for="<?php echo $this->get_field_id('widget_title'); ?>"><?php echo 'Widget Title:'; ?></label>
                          <input type="text" id="<?php echo $this->get_field_id('widget_title'); ?>" name="<?php echo $this->get_field_name('widget_title'); ?>" value="<?php echo $widget_title; ?>" /></p> 
                        <hr/>
                        <p>Use the settings to give your website users the ability to create the specific types of video chats.</p>                        	
                        <p><label for="<?php echo $this->get_field_id('individual'); ?>" style="width:120px; float:left;">Individual</label> 
                          <select id="<?php echo $this->get_field_id('individual'); ?>" name="<?php echo $this->get_field_name('individual'); ?>"> 
                            <option value="yes" <?php selected($individual, 'yes'); ?>>YES</option>
                                <option value="no"  <?php selected($individual, 'no'); ?>>NO</option>
                            </select></p>
                            
                        <p><label for="<?php echo $this->get_field_id('broadcasting'); ?>" style="width:120px; float:left;">Broadcasting</label>  
                          <select id="<?php echo $this->get_field_id('broadcasting'); ?>" name="<?php echo $this->get_field_name('broadcasting'); ?>">
                                <option value="yes" <?php selected($broadcasting, 'yes'); ?>>YES</option>
                                <option value="no"  <?php selected($broadcasting, 'no'); ?>>NO</option>
                            </select> </p>	
                            				
                        <p><label for="<?php echo $this->get_field_id('group'); ?>" style="width:120px; float:left;">Group</label>
                          <select id="<?php echo $this->get_field_id('group'); ?>" name="<?php echo $this->get_field_name('group'); ?>">
                                <option value="yes" <?php selected($group, 'yes'); ?>>YES</option>
                                <option value="no"  <?php selected($group, 'no'); ?>>NO</option>
                            </select></p>	
                            
                        <p><label for="<?php echo $this->get_field_id('archive'); ?>" style="width:120px; float:left;">Archive</label>
                          <select id="<?php echo $this->get_field_id('archive'); ?>" name="<?php echo $this->get_field_name('archive'); ?>">
                                <option value="yes" <?php selected($archive, 'yes'); ?>>YES</option>
                                <option value="no"  <?php selected($archive, 'no'); ?>>NO</option>
                            </select></p> 
                           
                        <hr/> 
                        <br/>    
                                             
                    				
		  <?php } 
		
			
	}  