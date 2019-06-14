<?php
/**
 * Themex Style
 *
 * Adds custom styles and fonts
 *
 * @class ThemexStyle
 * @author Themex
 */
 
class ThemexStyle {

	/**
	 * Adds actions and filters
     *
     * @access public
     * @return void
     */
	public static function init() {
	
		//add custom styles
		add_action('wp_head', array(__CLASS__,'renderStyles'));
		
		//add editor styles
		add_filter('mce_buttons_2', array(__CLASS__,'addEditorStyles'));
		add_filter('tiny_mce_before_init', array(__CLASS__,'renderEditorStyles'));
		
		//add custom fonts
		add_action('wp_head', array(__CLASS__,'renderFonts'));
		
		//add login logo
		add_action('login_head', array(__CLASS__,'renderLogo'));
	}
	
	/**
	 * Renders login logo
     *
     * @access public
     * @return void
     */
	public static function renderLogo() {
		$logo=ThemexCore::getOption('login_logo');		
		if(!empty($logo)) {
			echo '<style type="text/css">h1 a { background-image:url('.$logo.') !important; }</style>';
		}
	}
	
	/**
	 * Adds custom styles
     *
     * @access public
     * @return void
     */
	public static function renderStyles() {
	
		$out='<link rel="shortcut icon" href="'.ThemexCore::getOption('favicon', THEMEX_URI.'assets/images/favicon.ico').'" />';	
		$out.='<style type="text/css">';
		
		if(isset(ThemexCore::$components['custom_styles'])) {
			foreach(ThemexCore::$components['custom_styles'] as $style) {
				$out.=$style['elements'].'{';
				
				foreach($style['attributes'] as $attribute) {
					$option=ThemexCore::getOption($attribute['option']);
					
					if($option) {
						if($attribute['name']=='background-image') {
							$option='url('.$option.')';
						} else if($attribute['name']=='font-size') {
							$option=$option.'px';
						} else if($attribute['name']=='font-family') {
							$option=$option.', Arial, Helvetica, sans-serif';
						}
						
						$out.=$attribute['name'].':'.$option.';';
					}
				}				
				
				$out.='}';				
			}
		}
		
		$out.=ThemexCore::getOption('css');
		$out.='</style>';

		echo $out;
	}
	
	/**
	 * Adds editor styles
     *
     * @access public
	 * @param array $buttons
     * @return array
     */
	public static function addEditorStyles($buttons) {
		if(!empty(ThemexCore::$components['editor_styles'])) {
			array_unshift($buttons, 'styleselect');
		}

		return $buttons;
	}
	
	/**
	 * Renders editor styles
     *
     * @access public
	 * @param array $options
     * @return array
     */
	public static function renderEditorStyles($options) {
		$styles='';
		$formats=array(
			'title' => __('Custom', 'academy'),
		);
		
		foreach(ThemexCore::$components['editor_styles'] as $class=>$name) {
			$styles.=$name.'='.$class.';';
			$formats['items'][]=array(
				'title' => $name,
				'classes' => $class,
				'selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,table,img',				
			);
		}
	
		$options['theme_advanced_styles']=substr($styles, 0, -1);
		$options['theme_advanced_buttons2_add_before']='styleselect';
		
		$options['style_formats_merge']=true;
		$options['style_formats']=json_encode(array($formats));
		
		return $options;
	}
	
	/**
	 * Adds custom fonts
     *
     * @access public
     * @return void
     */
	public static function renderFonts() {
		$fonts=array();
		$out='';
		
		foreach(ThemexCore::$options as $option) {
			if($option['type']=='select_font') {
				$font=ThemexCore::getOption($option['id'], $option['default']);
				
				if($font=='Open Sans') {
					$font.=':400,400italic,600';
				}
				
				if($font=='Crete Round') {
					$out.='<style type="text/css">@font-face {
						font-family: "Crete Round";
						src: url("'.THEME_URI.'fonts/creteround-regular-webfont.eot");
						src: url("'.THEME_URI.'fonts/creteround-regular-webfont.eot?#iefix") format("embedded-opentype"),
							 url("'.THEME_URI.'fonts/creteround-regular-webfont.woff") format("woff"),
							 url("'.THEME_URI.'fonts/creteround-regular-webfont.ttf") format("truetype"),
							 url("'.THEME_URI.'fonts/creteround-regular-webfont.svg#crete_roundregular") format("svg");
						font-weight: normal;
						font-style: normal;
					}</style>';
				} else {
					$fonts[]=$font;
				}
			}
		}
		
		if(!empty($fonts)) {
			$out.='<script type="text/javascript">
			WebFontConfig = {google: { families: [ "'.implode($fonts, '","').'" ] } };
			(function() {
				var wf = document.createElement("script");
				wf.src = ("https:" == document.location.protocol ? "https" : "http") + "://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js";
				wf.type = "text/javascript";
				wf.async = "true";
				var s = document.getElementsByTagName("script")[0];
				s.parentNode.insertBefore(wf, s);
			})();
			</script>';
			
			echo $out;
		}		
	}
	
	/**
	 * Renders background
     *
     * @access public
     * @return void
     */
	public static function renderBackground() {
		global $post;	
		$out='';
		
		if(ThemexCore::getOption('background_type', 'fullwidth')!='tiled') {
			$background=ThemexCore::getOption('background_image', THEME_URI.'images/bgs/site_bg.jpg');
			
			if(is_page()) {
				$background=ThemexCore::getPostMeta($post->ID, 'page_background', $background);
			} else if(is_singular('course')) {
				$background=ThemexCore::getPostMeta($post->ID, 'course_background', $background);
			} else if(is_singular('lesson')) {
				$course=ThemexCore::getPostRelations($post->ID, 0, 'lesson_course', true);
				if($course!=0) {
					$background=ThemexCore::getPostMeta($course, 'course_background', $background);
				}
			}
			
			$out='<img src="'.$background.'" class="fullwidth" alt="" />';
		}
		
		echo $out;
	}
}