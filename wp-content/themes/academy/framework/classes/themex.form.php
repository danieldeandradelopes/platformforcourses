<?php
/**
 * Themex Form
 *
 * Handles custom forms
 *
 * @class ThemexForm
 * @author Themex
 */
 
class ThemexForm {

	/** @var array Contains module data. */
	public static $data;

	/**
	 * Adds actions and filters
     *
     * @access public
     * @return void
     */
	public static function init() {
	
		//refresh data
		self::refresh();
		
		//add field action
		add_action('wp_ajax_themex_form_add', array(__CLASS__, 'addField'));
		
		//submit form action
		add_action('wp_ajax_themex_form_submit', array(__CLASS__, 'submitData'));
		add_action('wp_ajax_nopriv_themex_form_submit', array(__CLASS__, 'submitData'));
	}
	
	/**
	 * Refreshes module data
     *
     * @access public
     * @return void
     */
	public static function refresh() {
		self::$data=(array)ThemexCore::getOption(__CLASS__);
	}
	
	/**
	 * Renders module settings
     *
     * @access public
	 * @param string $slug
     * @return string
     */
	public static function renderSettings($slug) {
		global $post;
		$out='';		

		if($slug!='profile') {
			$out.=ThemexInterface::renderOption(array(
				'id' => __CLASS__.'['.$slug.'][message]',
				'name' => __('Form Message', 'academy'),
				'type' => 'textarea',
				'description' => __('Enter message to show when email is successfully sent', 'academy'),
				'value' => isset(self::$data[$slug]['message'])?self::$data[$slug]['message']:'',
			));
			
			$out.=ThemexInterface::renderOption(array(
				'id' => __CLASS__.'['.$slug.'][captcha]',
				'name' => __('Enable Captcha Protection', 'academy'),
				'type' => 'checkbox',
				'value' => isset(self::$data[$slug]['captcha'])?self::$data[$slug]['captcha']:'',
			));
		}
		
		$out.=ThemexInterface::renderOption(array(
			'name' => __('Form Fields', 'academy'),
			'type' => 'title',
		));
		
		if(self::isActive($slug)) {			
			foreach(self::$data[$slug]['fields'] as $ID=>$field) {				
				$field['form']=$slug;
				$field['id']=$ID;
				$out.=self::renderField($field);
			}
		} else {
			$out.=self::renderField(array(
				'id' => uniqid(),
				'name' => '',
				'type' => 'string',
				'form' => $slug,
			));
		}
			
		return $out;
	}
	
	/**
	 * Saves module options
     *
     * @access public
	 * @param array $data
     * @return void
     */
	public static function saveOptions($data) {
		if(is_array($data)) {
			foreach($data as $slug => $form) {
				if(isset($form['fields']) && is_array($form['fields'])) {
					foreach($form['fields'] as $field) {
						$ID=themex_sanitize_key($field['name']);
						if(isset($field['name']) && !empty($field['name'])) {
							themex_add_string($ID, 'name', $field['name']);
						}
						
						if(isset($field['options']) && !empty($field['options'])) {
							themex_add_string($ID, 'options', $field['options']);
						}
					}
				}
			
				if(isset($form['message']) && !empty($form['message'])) {
					themex_add_string($slug, 'message', $form['message']);
				}
			}
		}
	}
	
	/**
	 * Renders module data
     *
     * @access public
	 * @param string $slug
	 * @param array $optionst
	 * @param array $values
     * @return void
     */
	public static function renderData($slug, $options=array(), $values=array()) {
		$options=wp_parse_args($options, array(
			'edit' => true,
			'before_title' => '',
			'after_title' => '',
			'before_content' => '',
			'after_content' => '',			
		));
		
		$out='';
		$counter=0;
		
		if(self::isActive($slug)) {
			foreach(self::$data[$slug]['fields'] as $field) {
				if(!empty($field['name'])) {
					$ID=themex_sanitize_key($field['name']);
					$field['name']=themex_get_string($ID, 'name', $field['name']);
					$counter++;
					
					if($options['edit']) {
						if(!empty($options['before_title']) || !empty($options['after_title'])) {
							$out.=$options['before_title'].$field['name'].$options['after_title'];
						}
						
						if(!empty($options['before_content'])) {
							$out.=$options['before_content'];
						}
						
						$args=array(
							'id' => $ID,
							'type' => $field['type'],
							'value' => themex_value($values, $ID),
							'wrap' => false,
						);

						if($field['type']=='textarea') {
							$out.='<div class="clear"></div>';
						} else {
							$out.='<div class="sixcol column ';
						
							if($counter%2==0) {
								$out.='last">';
							} else {
								$out.='">';
							}
						}
						
						if($field['type']=='select') {
							$field['options']=themex_get_string($ID, 'options', $field['options']);
							$args['options']=array_merge(array('0' => $field['name']), explode(',', $field['options']));
							$out.='<div class="select-field">';
						} else {
							$args['attributes']=array('placeholder' => $field['name']);
							$out.='<div class="field-wrapper">';
						}
						
						if(in_array($field['type'], array('number', 'email'))) {
							$args['type']='text';
						}
						
						$out.=ThemexInterface::renderOption($args);
						
						$out.='</div>';
						if($field['type']!='textarea') {
							$out.='</div>';
							if($counter%2==0) {
								$out.='<div class="clear"></div>';
							}
						}
						
						if(!empty($options['after_content'])) {
							$out.=$options['after_content'];
						}
					} else if(isset($values[$ID])) {
						$out.=$options['before_title'].$field['name'].$options['after_title'].$options['before_content'];
						
						if($field['type']=='select') {
							$field['options']=themex_get_string($ID, 'options', $field['options']);
							$items=array_merge(array('0' => '&ndash;'), explode(',', $field['options']));							
							if(isset($items[$values[$ID]])) {
								$values[$ID]=$items[$values[$ID]];
							}
						}
						
						if(empty($values[$ID])) {
							$values[$ID]='&ndash;';
						}
					
						$out.=$values[$ID];
						
						$out.=$options['after_content'];
					}
				}
			}
			
			if($options['edit'] && isset(self::$data[$slug]['captcha'])) {
				$out.='<div class="clear"></div>';
				$out.='<div class="form-captcha clearfix">';
				$out.='<img src="'.THEMEX_URI.'assets/images/captcha/captcha.php" alt="" />';
				$out.='<input type="text" name="captcha" id="captcha" size="6" value="" placeholder="'.__('Code', 'academy').'" /></div>';
			}
		}
		
		echo $out;
	}
	
	/**
	 * Adds new field
     *
     * @access public
     * @return void
     */
	public static function addField() {
		$slug=sanitize_text_field($_POST['value']);
		$out=self::renderField(array(
			'id' => uniqid(),
			'name' => '',
			'type' => 'string',
			'form' => $slug,
		));
		
		echo $out;		
		die();
	}
	
	/**
	 * Renders field option
     *
     * @access public
	 * @param array $field
     * @return string
     */
	public static function renderField($field) {
		$out='<div class="themex-form-item themex-option" id="'.$field['form'].'_'.$field['id'].'">';
		$out.='<a href="#" class="themex-button themex-remove-button themex-trigger" title="'.__('Remove', 'academy').'" data-action="themex_form_remove" data-element="'.$field['form'].'_'.$field['id'].'"></a>';
		
		$out.=ThemexInterface::renderOption(array(
			'id' => $field['form'].'_'.$field['id'].'_value',
			'type' => 'hidden',
			'value' => $field['form'],
			'wrap' => false,
			'after' => '<a href="#" class="themex-button themex-add-button themex-trigger" title="'.__('Add', 'academy').'" data-action="themex_form_add" data-element="'.$field['form'].'_'.$field['id'].'" data-value="'.$field['form'].'_'.$field['id'].'_value"></a>',				
		));
		
		$out.=ThemexInterface::renderOption(array(
			'id' => __CLASS__.'['.$field['form'].'][fields]['.$field['id'].'][name]',
			'type' => 'text',
			'attributes' => array('placeholder' => __('Name', 'academy')),
			'value' => isset(self::$data[$field['form']]['fields'][$field['id']]['name'])?themex_stripslashes(self::$data[$field['form']]['fields'][$field['id']]['name']):'',
			'wrap' => false,
		));
	
		if($field['form']=='profile') {
			$out.=ThemexInterface::renderOption(array(
				'id' => __CLASS__.'['.$field['form'].'][fields]['.$field['id'].'][type]',
				'type' => 'select',
				'options' => array(
					'text' => __('String', 'academy'),
					'select' => __('Select', 'academy'),		
				),
				'value' => isset(self::$data[$field['form']]['fields'][$field['id']]['type'])?self::$data[$field['form']]['fields'][$field['id']]['type']:'',
				'wrap' => false,
			));
		} else {
			$out.=ThemexInterface::renderOption(array(
				'id' => __CLASS__.'['.$field['form'].'][fields]['.$field['id'].'][type]',
				'type' => 'select',
				'options' => array(
					'text' => __('String', 'academy'),
					'number' => __('Number', 'academy'),
					'email' => __('Email', 'academy'),
					'textarea' => __('Text', 'academy'),
					'select' => __('Select', 'academy'),
				),
				'value' => isset(self::$data[$field['form']]['fields'][$field['id']]['type'])?self::$data[$field['form']]['fields'][$field['id']]['type']:'',
				'wrap' => false,
			));
		}
		
		$out.=ThemexInterface::renderOption(array(
			'id' => __CLASS__.'['.$field['form'].'][fields]['.$field['id'].'][options]',
			'type' => 'text',
			'attributes' => array('placeholder' => __('Options', 'academy')),
			'value' => isset(self::$data[$field['form']]['fields'][$field['id']]['options'])?self::$data[$field['form']]['fields'][$field['id']]['options']:'',
			'wrap' => false,
		));
		
		$out.='</div>';
		
		return $out;
	}
	
	/**
	 * Submits form data
     *
     * @access public
     * @return void
     */
	public static function submitData() {
		self::refresh();
		parse_str($_POST['data'], $data);		
		
		if(isset($data['slug']) && self::isActive($data['slug'])) {
			if(isset(self::$data[$data['slug']]['captcha'])) {
				session_start();
				$posted_code=md5($data['captcha']);
				$session_code=$_SESSION['captcha'];
				
				if($session_code!= $posted_code) {
					ThemexInterface::$messages[]=__('The verification code is incorrect', 'academy');
				}
			}
			
			foreach(self::$data[$data['slug']]['fields'] as $field) {
				$ID=themex_sanitize_key($field['name']);
				$field['name']=themex_get_string($ID, 'name', $field['name']);
				
				if((!isset($data[$ID]) || trim($data[$ID])=='') && !isset($field['optional']) && $field['type']!='checkbox') {
					ThemexInterface::$messages[]='"'.$field['name'].'" '.__('field is required', 'academy');
				} else {
					if($field['type']=='number' && !is_numeric($data[$ID])) {
						ThemexInterface::$messages[]='"'.$field['name'].'" '.__('field can only contain numbers', 'academy');
					}
					
					if($field['type']=='email' && !is_email($data[$ID])) {
						ThemexInterface::$messages[]=__('You have entered an invalid email address', 'academy');
					}
				}
			}
			
			if(!empty(ThemexInterface::$messages)) {
				ThemexInterface::renderMessages();
			} else {
				$email=get_option('admin_email');
				$subject=__('Contact', 'academy');			
				$message='';
				
				foreach(self::$data[$data['slug']]['fields'] as $field) {
					$ID=themex_sanitize_key($field['name']);
					$field['name']=themex_get_string($ID, 'name', $field['name']);
					
					if($field['type']=='select') {
						$field['options']=themex_get_string($ID, 'options', $field['options']);
						$items=explode(',', $field['options']);
						if(isset($items[$data[$ID]-1])) {
							$data[$ID]=$items[$data[$ID]-1];
						} else {
							$data[$ID]='&ndash;';
						}
					} else if($field['type']=='textarea') {
						$data[$ID]=nl2br($data[$ID]);
					}
				
					$message.='<strong>'.$field['name'].'</strong>: '.$data[$ID].'<br />';
				}
				
				if(themex_mail($email, $subject, $message) && isset(self::$data[$data['slug']]['message'])) {
					$message=themex_get_string($data['slug'], 'message', self::$data[$data['slug']]['message']);
					ThemexInterface::$messages[]=$message;
				}
				
				ThemexInterface::renderMessages(true);					
			}
		}
		
		die();
	}
	
	/**
	 * Checks form activity
     *
     * @access public
	 * @param string $slug
     * @return bool
     */
	public static function isActive($slug) {
		if(isset(self::$data[$slug]['fields']) && !empty(self::$data[$slug]['fields'])) {
			$field=reset(self::$data[$slug]['fields']);
			if(!empty($field['name'])) {	
				return true;
			}
		}
		
		return false;
	}
}