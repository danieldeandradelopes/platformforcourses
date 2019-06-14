<?php
/**
 * Themex Shortcode
 *
 * Handles custom shortcodes
 *
 * @class ThemexShortcode
 * @author Themex
 */
 
class ThemexShortcode {

	/** @var array Contains module data. */
	public static $data;

	/**
	 * Adds actions and filters
     *
     * @access public
     * @return void
     */
	public static function init() {
	
		//require shortcodes
		require_once(THEMEX_PATH.'shortcodes.php');
	
		//refresh module data
		self::refresh();
	
		//init tinymce plugin
		add_action('admin_init', array( __CLASS__, 'initInterface' ));
		
		//init js scripts
		add_action('admin_enqueue_scripts', array( __CLASS__, 'initScripts'));
		
		//add shortcode filters
		add_filter('the_content', array( __CLASS__, 'filterShortcodes'));
		add_filter('widget_text', 'do_shortcode');
		add_filter('the_excerpt', 'do_shortcode');
	}
	
	/**
	 * Refreshes module data
     *
     * @access public
     * @return void
     */
	public static function refresh() {
		if(isset($_GET['shortcode'])) {
			foreach(ThemexCore::$components['shortcodes'] as $shortcode) {
				if($shortcode['id']==$_GET['shortcode']) {
					self::$data=$shortcode;
				}
			}
		}
	}
	
	/**
	 * Inits module scripts
     *
     * @access public
     * @return void
     */
	public static function initScripts() {
		$out='<script type="text/javascript">';
		$out.='themexURI="'.THEMEX_URI.'";';
		$out.='themexTitle="'.__('Insert Shortcode', 'academy').'";';
		$out.='themexShortcodes={';
		
		foreach(ThemexCore::$components['shortcodes'] as $shortcode) {
			$out.=$shortcode['id'].':"'.$shortcode['name'].'",';
		}
		
		$out.='}';	
		$out.='</script>';
		
		echo $out;
	}
	
	/**
	 * Inits module interface
     *
     * @access public
     * @return void
     */
	public static function initInterface() {
		add_filter('mce_external_plugins', array(__CLASS__, 'addPlugin'));
		add_filter('mce_buttons', array(__CLASS__, 'addButton'));
	}
	
	/**
	 * Adds tinymce plugin
     *
     * @access public
	 * @param array $plugins
     * @return array
     */
	public static function addPlugin($plugins) {
		$plugins['themex_shortcode'] = THEMEX_URI.'assets/js/themex.shortcode.js';
		return $plugins;
	}
	
	/**
	 * Adds tinymce button
     *
     * @access public
	 * @param array $buttons
     * @return array
     */
	public static function addButton($buttons) {
		array_push($buttons, '|', 'themex_shortcode');
		return $buttons;
	}
	
	/**
	 * Renders module settings
     *
     * @access public
     * @return string
     */
	public static function renderSettings() {
		$out='<table><tbody>';
		
		//render options
		if(isset(self::$data['options'])) {	
			$option['value']='';
			if(isset($option['default'])) {
				$option['value']=$option['default'];
			}
			
			foreach(self::$data['options'] as $option) {
				$out.='<tr>';
				$out.='<th><h4 class="themex-shortcode-title">'.$option['name'].'</h4></th>';				
				$out.='<td>'.ThemexInterface::renderOption($option).'</td>';				
				$out.='</tr>';
			}			
		}
		
		//render clone
		if(isset(self::$data['clone'])) {
			$ID='a'.uniqid();
			
			$out.='<tr><td colspan="2"><div class="themex-shortcode-pane"><div class="themex-shortcode-clone" id="'.$ID.'">';
			$out.='<div class="themex-shortcode-pattern hidden">'.self::$data['clone']['shortcode'].'</div>';
			$out.='<div class="themex-shortcode-value hidden"></div>';
			$out.='<a href="#" class="themex-button themex-remove-button themex-trigger" data-element="'.$ID.'" title="'.__('Remove', 'academy').'"></a>';
			$out.='<a href="#" class="themex-button themex-clone-button themex-trigger" data-element="'.$ID.'" data-value="'.$ID.'" title="'.__('Add', 'academy').'"></a>';
				
			foreach(self::$data['clone']['options'] as $option) {
				$out.=ThemexInterface::renderOption($option);
			}			
			
			$out.='</div></div></td></tr>';
		}
		
		$out.='<tr><th></th><td><div class="themex-option themex-submit"><input type="submit" class="themex-button" value="'.__('Insert Shortcode','academy').'" /></div></td></tr>';
		$out.='</tbody></table>';
		$out.='<div class="themex-shortcode-pattern hidden">'.self::$data['shortcode'].'</div>';
		$out.='<div class="themex-shortcode-value hidden"></div>';
		
		return $out;
	}
	
	/**
	 * Filters shortcodes markup
     *
     * @access public
     * @return void
     */
	public static function filterShortcodes($content) {
		$shortcodes=implode('|', array(
			'button',
			'courses',
			'contact_form', 
			'content',
			'map', 
			'image', 
			'plan', 
			'player',
			'posts', 
			'section', 
			'slider', 
			'tabs', 
			'tab',
			'testimonials', 
			'toggles', 
			'toggle', 
			'users',
			'one_sixth', 
			'one_sixth_last', 
			'one_fourth', 
			'one_fourth_last', 
			'one_third', 
			'one_third_last', 
			'five_twelfths', 
			'five_twelfths_last', 
			'one_half', 
			'one_half_last', 
			'seven_twelfths', 
			'seven_twelfths_last', 
			'two_thirds', 
			'two_thirds_last', 
			'three_fourths', 
			'three_fourths_last',
		));

		$filtered=preg_replace("/(<p>)?\[($shortcodes)(\s[^\]]+)?\](<\/p>)?/", "[$2$3]", $content);
		$filtered=preg_replace("/(<p>)?\[\/($shortcodes)](<\/p>)?/", "[/$2]", $filtered);
 
		return $filtered;
	}
}