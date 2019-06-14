<?php
/**
 * Decodes HTML entities
 *
 * @param string $string
 * @return string
 */
function themex_html($string) {
	return do_shortcode(html_entity_decode($string));
}

/**
 * Removes slashes
 *
 * @param string $string
 * @return string
 */
function themex_stripslashes($string) {
	if(!is_array($string)) {
		return stripslashes(stripslashes($string));
	}
	
	return $string;	
}

/**
 * Gets string sections
 *
 * @param string $content
 * @param int $sections
 * @return string
 */
function themex_sections($content, $sections) {	
	$paragraphs=explode('</p>', $content);
	$excerpt='';

	for($j=0; $j<intval($sections); $j++) {
		if(isset($paragraphs[$j])) {
			$excerpt.=$paragraphs[$j].'</p>';
		}
	}
	
	return $excerpt;
}

/**
 * Gets page number
 *
 * @return int
 */
function themex_paged() {
	$paged=get_query_var('paged')?get_query_var('paged'):1;
	$paged=(get_query_var('page'))?get_query_var('page'):$paged;
	
	return $paged;
}

/**
 * Gets array value
 *
 * @param array $array
 * @param string $key
 * @param string $default
 * @return mixed
 */
function themex_value($array, $key, $default='') {
	$value='';
	
	if(isset($array[$key])) {
		if(is_array($array[$key])) {
			$value=reset($array[$key]);
		} else {
			$value=$array[$key];
		}		
	} else if ($default!='') {
		$value=$default;
	}
	
	return $value;
}

/**
 * Implodes array or value
 *
 * @param string $value
 * @param string $prefix
 * @return string
 */
function themex_implode($value, $prefix='') {
	if(is_array($value)) {
		$value=array_map('sanitize_key', $value);
		$value=implode("', '".$prefix, $value);
	} else {
		$value=sanitize_key($value);
	}
	
	$value="'".$prefix.$value."'";	
	return $value;
}

/**
 * Shuffles array
 *
 * @param array $array
 * @return array
 */
function themex_shuffle($array) {
	$keys=array_keys($array);
	shuffle($keys);
	
	$shuffled=array();
	foreach($keys as $key) {
		$shuffled[$key]=$array[$key]; 
	}
	
	return $shuffled;
}

/**
 * Gets current URL
 *
 * @param bool $private
 * @return string
 */
function themex_url($private=false, $default='') {
	$url=@($_SERVER['HTTPS'] != 'on') ? 'http://'.$_SERVER['SERVER_NAME'] :  'https://'.$_SERVER['SERVER_NAME'];
	$url.=($_SERVER['SERVER_PORT']!=='80') ? ':'.$_SERVER['SERVER_PORT'] : '';
	$url.=$_SERVER['REQUEST_URI'];
	
	if($private && !is_user_logged_in()) {
		if(empty($default)) {
			$url=SITE_URL;
		} else {
			$url=$default;
		}		
	}
	
	return $url;
}

/**
 * Filters array
 *
 * @param array $array
 * @return array
 */
function themex_filter($array) {
	$filtered=array();
	
	if(is_array($array) && !empty($array)) {
		$first=reset($array);
		if(is_array($first) && !empty($first)) {
			$first=reset($first);
			if(!empty($first)) {
				$filtered=$array;
			}
		}
	}
	
	return $filtered;
}

/**
 * Outputs flag
 *
 * @param mixed $value
 * @return string
 */
function themex_flag($value=null) {
	$flag='';

	if(!is_null($value)) {
		$flag='error';
	
		if($value) {
			$flag='success';
		}
	}	
	
	return $flag;
}

/**
 * Shows posts dropdown
 *
 * @param array $args
 * @return void
 */
function themex_dropdown_posts($args) {
	$defaults = array(
		'post_type' => 'post',
		'selected' => 0,
		'show_option_all' => '',		
		'name' => 'post_id',
		'id' => '',
		'class' => '',
    );
	
	$args=wp_parse_args($args, $defaults);
	$args['author']=0;
	if(!current_user_can('manage_options')) {
		$args['author']=get_current_user_id();
	}
	
	$posts=get_posts(array(
		'post_type' => $args['post_type'],
		'author' => $args['author'],
		'posts_per_page' => -1,		
	));
	
	$out='<select name="'.$args['name'].'" id="'.$args['id'].'" class="'.$args['class'].'">';
	$out.='<option value="0">'.$args['show_option_all'].'</option>';
	
	if(!empty($posts)) {
		foreach($posts as $post) {
			$selected='';
			if($post->ID==$args['selected']) {
				$selected='selected="selected"';
			}
			
			$out.='<option value="'.$post->ID.'" '.$selected.'>'.$post->post_title.'</option>';
		}
	}
	
	$out.='</select>';
	echo $out;
}

/**
 * Converts array to CSV
 *
 * @param array $fields
 * @param array $columns
 * @return string
 */
function themex_csv($fields, $columns) {
	$out='"'.implode('";"', $columns).'"'."\r\n";
	
	foreach($fields as $field) {
		$field=array_intersect_key($field, $columns);
		$out.='"'.implode('";"', $field).'"'."\r\n";
	}
	
	return $out;
}

/**
 * Encodes parameters to string
 *
 * @param int $basic
 * @param int $shifted
 * @return string
 */
function themex_encode($basic, $shifted) {
	$code=strlen($basic).$basic.$shifted;
	
	return $code;
}

/**
 * Decodes string with parameters
 *
 * @param string $code
 * @param bool $shift
 * @return int
 */
function themex_decode($code, $shift=false) {
	$value=intval(substr($code, 1, intval(substr($code, 0, 1))));

	if($shift) {
		$value=intval(substr($code, intval(substr($code, 0, 1))+1));
	}
	
	return $value;
}

/**
 * Replaces string keywords
 *
 * @param string $string
 * @param array $keywords
 * @return string
 */
function themex_keywords($string, $keywords) {
	foreach($keywords as $keyword => $value) {
		$string=str_replace('%'.$keyword.'%', $value, $string);
	}
	
	return $string;
}

/**
 * Sends encoded email
 *
 * @param string $recipient
 * @param string $subject
 * @param string $message
 * @return bool
 */
function themex_mail($recipient, $subject, $message) {
	$headers='MIME-Version: 1.0'.PHP_EOL;
	$headers.='Content-Type: text/html; charset=UTF-8'.PHP_EOL;
	$subject='=?UTF-8?B?'.base64_encode($subject).'?=';
	
	if(wp_mail($recipient, $subject, $message, $headers)) {
		return true;
	}
	
	return false;
}

/**
 * Gets static string
 *
 * @param string $key
 * @param string $type
 * @param string $default
 * @return string
 */
function themex_get_string($key, $type, $default) {
	$name=$key.'-'.$type;
	$string=$default;	
	$strings=array();
	include(THEMEX_PATH.'strings.php');

	if(isset($strings[$name])) {
		$string=$strings[$name];
	}
	
	return themex_stripslashes($string);
}

/**
 * Adds static string
 *
 * @param string $key
 * @param string $type
 * @param string $string
 * @return void
 */
function themex_add_string($key, $type, $string) {
	$name=$key.'-'.$type;
	$string=themex_stripslashes($string);
	$strings=array();
	include(THEMEX_PATH.'strings.php');
	
	if(!isset($strings[$name])) {
		$string=str_replace("'", "’", $string);
		$file=@fopen(THEMEX_PATH.'strings.php', 'a');
		
		if($file!==false) {
			fwrite($file, "\r\n".'$strings'."['".$name."']=__('".$string."', 'academy');");
			fclose($file);
		}
	}
}

/**
 * Removes static strings
 *
 * @return void
 */
function themex_remove_strings() {
	$file=@fopen(THEMEX_PATH.'strings.php', 'w');	
	if($file!==false) {
		fwrite($file, '<?php ');
		fclose($file);
	}
}

/**
 * Sanitizes key
 *
 * @param string $string
 * @return string
 */
function themex_sanitize_key($string) {
	$replacements=array(
		// Latin
		'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 
		'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 
		'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O', 
		'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 
		'ß' => 'ss', 
		'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 
		'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 
		'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o', 
		'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th', 
		'ÿ' => 'y',
 
		// Greek
		'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
		'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
		'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
		'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
		'Ϋ' => 'Y',
		'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
		'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
		'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
		'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
		'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
 
		// Turkish
		'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
		'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g', 
 
		// Russian
		'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
		'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
		'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
		'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
		'Я' => 'Ya',
		'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
		'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
		'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
		'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
		'я' => 'ya',
 
		// Ukrainian
		'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
		'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
 
		// Czech
		'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U', 
		'Ž' => 'Z', 
		'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
		'ž' => 'z', 
 
		// Polish
		'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z', 
		'Ż' => 'Z', 
		'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
		'ż' => 'z',
 
		// Latvian
		'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N', 
		'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
		'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
		'š' => 's', 'ū' => 'u', 'ž' => 'z'
	);	
	
	$string=str_replace(array_keys($replacements), $replacements, $string);
	$string=preg_replace('/\s+/', '-', $string);
	$string=preg_replace('!\-+!', '-', $string);
	$filtered=$string;
	
	$string=preg_replace('/[^A-Za-z0-9-]/', '', $string);
	$string=strtolower($string);
	$string=trim($string, '-');
	
	if(empty($string) || $string[0]=='-') {
		$string='a'.md5($filtered);
	}
	
	return $string;
}

/**
 * Resize image
 *
 * @param string $url
 * @param int $width
 * @param int $height
 * @return array
 */
function themex_resize($url, $width, $height) {
	add_filter('image_resize_dimensions', 'themex_scale', 10, 6);

	$upload_info=wp_upload_dir();
	$upload_dir=$upload_info['basedir'];
	$upload_url=$upload_info['baseurl'];
	
	//check prefix
	$http_prefix='http://';
	$https_prefix='https://';
	
	if(!strncmp($url, $https_prefix, strlen($https_prefix))){
		$upload_url=str_replace($http_prefix, $https_prefix, $upload_url);
	} else if (!strncmp($url, $http_prefix, strlen($http_prefix))){
		$upload_url=str_replace($https_prefix, $http_prefix, $upload_url);		
	}	

	//check URL
	if (strpos($url, $upload_url)===false) {
		return $url;
	}

	//define path
	$rel_path=str_replace($upload_url, '', $url);
	$img_path=$upload_dir.$rel_path;

	//check file
	if (!file_exists($img_path) or !getimagesize($img_path)) {
		return $url;
	}

	//get file info
	$info=pathinfo($img_path);
	$ext=$info['extension'];
	list($orig_w, $orig_h)=getimagesize($img_path);

	//get image size
	$dims=image_resize_dimensions($orig_w, $orig_h, $width, $height, true);
	$dst_w=$dims[4];
	$dst_h=$dims[5];

	//resize image
	if(!$dims && ((($height===null && $orig_w==$width) xor ($width===null && $orig_h==$height)) xor ($height==$orig_h && $width==$orig_w))) {
		$img_url=$url;
		$dst_w=$orig_w;
		$dst_h=$orig_h;
	} else {
		$suffix=$dst_w.'x'.$dst_h;
		$dst_rel_path=str_replace('.'.$ext, '', $rel_path);
		$destfilename=$upload_dir.$dst_rel_path.'-'.$suffix.'.'.$ext;

		if(!$dims) {
			return $url;
		} else if(file_exists($destfilename) && getimagesize($destfilename) && empty($_FILES)) {
			$img_url=$upload_url.$dst_rel_path.'-'.$suffix.'.'.$ext;
		} else {
			if (function_exists('wp_get_image_editor')) {
				$editor=wp_get_image_editor($img_path);
				if (is_wp_error($editor) || is_wp_error($editor->resize($width, $height, true))) {
					return $url;
				}

				$resized_file=$editor->save();

				if (!is_wp_error($resized_file)) {
					$resized_rel_path=str_replace($upload_dir, '', $resized_file['path']);
					
					$img_url=$upload_url.$resized_rel_path.'?'.time();
				} else {
					return $url;
				}
			} else {
				$resized_img_path=image_resize($img_path, $width, $height, true);
				
				if (!is_wp_error($resized_img_path)) {
					$resized_rel_path=str_replace($upload_dir, '', $resized_img_path);
					$img_url=$upload_url.$resized_rel_path;
				} else {
					return $url;
				}
			}
		}
	}

	remove_filter('image_resize_dimensions', 'themex_scale');
	return $img_url;
}

/**
 * Scale image
 *
 * @param string $default
 * @param int $orig_w
 * @param int $orig_h
 * @param int $dest_w
 * @param int $dest_h
 * @param bool $crop
 * @return array
 */
function themex_scale($default, $orig_w, $orig_h, $dest_w, $dest_h, $crop) {
	$aspect_ratio=$orig_w/$orig_h;
	$new_w=$dest_w;
	$new_h=$dest_h;

	if (!$new_w) {
		$new_w=intval($new_h*$aspect_ratio);
	}

	if (!$new_h) {
		$new_h=intval($new_w/$aspect_ratio);
	}

	$size_ratio=max($new_w/$orig_w, $new_h/$orig_h);
	$crop_w=round($new_w/$size_ratio);
	$crop_h=round($new_h/$size_ratio);

	$s_x=floor(($orig_w-$crop_w)/2);
	$s_y=floor(($orig_h-$crop_h)/2);
	$scale=array(0, 0, (int)$s_x, (int)$s_y, (int)$new_w, (int)$new_h, (int)$crop_w, (int)$crop_h);

	return $scale;
}

/**
 * Gets time in days
 *
 * @param int $time
 * @param bool $convert
 * @return string
 */
function themex_time($time, $convert=false) {
	$time=round($time/86400);
	$out='';
	
	if($convert) {
		switch($time) {
			case 7: 
				$out.=__('week', 'academy');
			break;
			
			case 31: 
				$out.=__('month', 'academy');
			break;
			
			case 365: 
				$out.=__('year', 'academy');
			break;
			
			default:
				$out.=round($time/31).' '.__('months', 'academy');
			break;
		}
	} else if($time!=0) {
		$out.=$time.' ';
		if($time==1) {
			$out.=__('day', 'academy');
		} else {
			$out.=__('days', 'academy');
		}
	} else {
		$out.=__('less than a day', 'academy');
	}	
	
	return $out;
}