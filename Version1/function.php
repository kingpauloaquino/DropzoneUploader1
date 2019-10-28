<?php
class Functions{
	
	public static $timezone_taipei = "Asia/Taipei";
	public static $upload_image_path = "upload-images/compressed/";
    
    function generateRandomString($length = 12) {
        return substr(str_shuffle("23456789ABCDEFGHJKLMNPQRSTUVWXYZ"), 0, $length);
    }
	
	public function img_location($parents) {
		if(!empty($parents)) {
			return $parents . $this::$upload_image_path;
		}
 		return  $this::$upload_image_path;
	}
	
	public function Sentence_Case($string) {
		$string = strtoupper($string);
	    $sentences = preg_split('/([.?!]+)/', $string, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE); 
	    $new_string = ''; 
	    foreach ($sentences as $key => $sentence) { 
	        $new_string .= ($key & 1) == 0? 
	            ucfirst(strtolower(trim($sentence))) : 
	            $sentence.' '; 
	    } 
	    return trim($new_string); 
	} 
	
	public function Sentence_Cap($impexp, $sentence_split) {
	    $textbad=explode($impexp, $sentence_split);
	    $newtext = array();
	    foreach ($textbad as $sentence) {
	        $sentencegood=ucfirst(strtolower($sentence));
	        $newtext[] = $sentencegood;
	    }
	    $textgood = implode($impexp, $newtext);
	    return $textgood;
	}
	
	function formatMoney($number, $fractional=false) { 
	    if ($fractional) { 
	        $number = sprintf('%.2f', $number); 
	    } 
	    while (true) { 
	        $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number); 
	        if ($replaced != $number) { 
	            $number = $replaced; 
	        } else { 
	            break; 
	        } 
	    } 
	    return $number; 
	} 
	
	public function remaining_days($my_datetime){
		$timezone = $this::$timezone_taipei;
		$my_date = $this->dtStrToDateTime($timezone, "Y-m-d", $my_datetime);
		// var_dump($my_date);
		
		$my_time = strtotime($my_date);
		// var_dump($my_time);
		
		$exp_time = $this->dtGetExpirationTime($my_time, 30);
		// var_dump($exp_time);
		
		$exp_date = $this->dtGetExpirationDate($timezone, "Y-m-d", $exp_time);
		// var_dump($exp_date);
		
		$days_runs = round(($exp_time - time()) / 86400);
		// var_dump($days_runs);
		
		return (int)$days_runs;
	}
	
	public function dtDateToStr($strDate) {
		return strtotime($strDate);
	}
	
	public function dtGetCurrTimeZone($timezones, $IsDateOnly, $IsNotMilitary = TRUE) {
		date_default_timezone_set($timezones); 
		if ($IsDateOnly) {
			return date('Y-m-d');
		}
		else {
			
			if(!$IsNotMilitary) {
				return date('Y-m-d h:i:s a');
			}
			return date('Y-m-d H:i:s');
		}
	}
	
	public function dtDateTimeFormat($timezones, $format, $strTime = "") {
		date_default_timezone_set($timezones); 
		if($strTime != "") {
			return date($format, $strTime);
		}
		else {
			return date($format);
		}
	}
	
	public function dtStrToDateTime($timezones, $format, $mydate) {
		date_default_timezone_set($timezones); 
		return date($format, strtotime($mydate));
	}
	
	public function dtGetExpirationTime($mytime, $day_expiration) {
		$days = (int)$day_expiration;
		return $mytime + (60*60*24*$days*1);
	}
	
	public function dtGetExpirationDate($timezones, $format, $exp_date) {
		return  $this->dtDateTimeFormat($timezones, $format, $exp_date);
	}
	
	public function dtChkExpiration($timezones, $exp_date) {
		date_default_timezone_set($timezones);
		if ($exp_date < time()) {
		    return TRUE;
		} 
		else {
			return FALSE;
		}
	}
	
	public function get_serialNumber($timezones) {
		date_default_timezone_set($timezones);
		list($usec, $sec) = explode(" ", microtime());
	    $u = ((float)$usec + (float)$sec);
		list($u1, $u2) = explode(".", $u);
		
		$times = date("His");
		$dtimes = date("Y") . date("n") . date("j") . $times . $u2;
		return (string)$dtimes;
	}
	
	public function time_elapsed_string($datetime, $full = false) {
	    $now = new DateTime;
	    $ago = new DateTime($datetime, new DateTimeZone('Asia/Taipei'));
		
		$now->setTimezone(new DateTimeZone('Asia/Taipei'));
	    $diff = $now->diff($ago);
	
	    $diff->w = floor($diff->d / 7);
	    $diff->d -= $diff->w * 7;
	
	    $string = array(
	        'y' => 'yr',
	        'm' => 'mo',
	        'w' => 'wk',
	        'd' => 'day',
	        'h' => 'hr',
	        'i' => 'min',
	        's' => 'sec',
	    );
	    foreach ($string as $k => &$v) {
	        if ($diff->$k) {
	            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
	        } else {
	            unset($string[$k]);
	        }
	    }
	
	    if (!$full) $string = array_slice($string, 0, 1);
	    return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
	
	// get directory details
	
    public function counter($i) {
        
        $l = strlen($i);
        $i = $i + 1;
        if($l == 2) {
            $r = "0". $i;
        }
        else if ($l == 3) {
            $r = $i;
        }
        else {
            $r = "00". $i;
        }
        return $r;
        
    }
	
	public function g_dir_size($d_path, $limit_size){
		
	    $u_size = 0;
	    $dir = realpath($d_path);
		$l_size = $this->s_limit_size($limit_size);
		
	    if($dir!==false) {
	    	$f = $dir;
		    $obj = new COM ( 'scripting.filesystemobject' );
		    if ( is_object ( $obj ) ) {
		        $ref = $obj->getfolder ( $f );
		        $u_size = $ref->size;
		        $obj = null;
		    }
		    else {
		       return array('Err' => 'Error: Cannot create object.');
		    }
	    }
		
		$s_size = FALSE;
		$r_size = $l_size - $u_size;
		if($u_size > $l_size) {
			$s_size = TRUE;
		}
		
		$a_info = array(
		'Used' => $this->f_size($u_size), 
		'Remaining' => $this->f_size($r_size), 
		'Limit' => $this->f_size($l_size),
		'Directory' => $dir,
		'Status' => $s_size);
		
	    return $a_info;
	}
	
	public function s_limit_size($gb_size) {
		$sizes = (1073741824 * $gb_size);
		return $sizes;
	}
	
	public function f_size($size) {
	    $units = explode(' ', 'B KB MB GB TB PB');
	    $mod = 1024;
	
	    for ($i = 0; $size > $mod; $i++) {
	        $size /= $mod;
	    }
	
	    $endIndex = strpos($size, ".") + 3;
	    return substr( $size, 0, $endIndex) .' '. $units[$i];
	}
	
	public function countries($c_Id) {
		$list = "<select name='$c_Id' id='$c_Id'>
                    <option value='AF'>Afghanistan</option>
                    <option value='AA'>All Countries</option>
                    <option value='AI'>Anguilla</option>
                    <option value='AM'>Armenia</option>
                    <option value='AW'>Aruba</option>
                    <option value='AU'>Australia</option>
                    <option value='AT'>Austria</option>
                    <option value='AZ'>Azerbaijan</option>
                    <option value='BS'>Bahamas</option>
                    <option value='BH'>Bahrain</option>
                    <option value='BD'>Bangladesh</option>
                    <option value='BB'>Barbados</option>
                    <option value='BY'>Belarus</option>
                    <option value='BE'>Belgium</option>
                    <option value='BZ'>Belize</option>
                    <option value='BJ'>Benin</option>
                    <option value='BM'>Bermuda</option>
                    <option value='BT'>Bhutan</option>
                    <option value='BO'>Bolivia</option>
                    <option value='BV'>Bouvet Islands</option>
                    <option value='BR'>Brazil</option>
                    <option value='IO'>British Indian Ocean Territory</option>
                    <option value='VI'>British Virgin Islands</option>
                    <option value='BN'>Brunei</option>
                    <option value='BG'>Bulgaria</option>
                    <option value='BF'>Burkina Faso</option>
                    <option value='BI'>Burundi</option>
                    <option value='KH'>Cambodia</option>
                    <option value='CM'>Cameroon</option>
                    <option value='CA'>Canada</option>
                    <option value='CV'>Cape Verde</option>
                    <option value='KY'>Cayman Islands</option>
                    <option value='CF'>Central African Republic</option>
                    <option value='TD'>Chad</option>
                    <option value='CL'>Chile</option>
                    <option value='CN'>China</option>
                    <option value='CO'>Colombia</option>
                    <option value='KM'>Comoros</option>
                    <option value='CG'>Congo</option>
                    <option value='CR'>Costa Rica</option>
                    <option value='CI'>Cote D'Ivorie</option>
                    <option value='HR'>Croatia</option>
                    <option value='CY'>Cyprus</option>
                    <option value='CZ'>Czech Republic</option>
                    <option value='DK'>Denmark</option>
                    <option value='DJ'>Djibouti</option>
                    <option value='DM'>Dominica</option>
                    <option value='DO'>Dominican Republic</option>
                    <option value='EG'>Egypt</option>
                    <option value='SV'>El Salvador</option>
                    <option value='EC'>Equador</option>
                    <option value='GQ'>Equatorial Guinea</option>
                    <option value='ER'>Eritrea</option>
                    <option value='EE'>Estonia</option>
                    <option value='ET'>Ethiopia</option>
                    <option value='FK'>Falkland Islands</option>
                    <option value='FO'>Faroe Islands</option>
                    <option value='FM'>Federated States of Micronesia</option>
                    <option value='FJ'>Fiji</option>
                    <option value='FI'>Finland</option>
                    <option value='FR'>France</option>
                    <option value='GF'>French Guiana</option>
                    <option value='PF'>French Polynesia</option>
                    <option value='GA'>Gabon</option>
                    <option value='GM'>Gambia</option>
                    <option value='GE'>Georgia</option>
                    <option value='DE'>Germany</option>
                    <option value='GH'>Ghana</option>
                    <option value='GI'>Gibraltar</option>
                    <option value='GR'>Greece</option>
                    <option value='GL'>Greenland</option>
                    <option value='GD'>Grenada</option>
                    <option value='GP'>Guadeloupe</option>
                    <option value='GU'>Guam</option>
                    <option value='GT'>Guatemala</option>
                    <option value='GN'>Guinea</option>
                    <option value='GW'>Guinea-Bissau</option>
                    <option value='GY'>Guyana</option>
                    <option value='HT'>Haiti</option>
                    <option value='HN'>Honduras</option>
                    <option value='HK'>Hong Kong</option>
                    <option value='HU'>Hungary</option>
                    <option value='IS'>Iceland</option>
                    <option value='IN'>India</option>
                    <option value='ID'>Indonesia</option>
                    <option value='IE'>Ireland</option>
                    <option value='IL'>Israel</option>
                    <option value='IT'>Italy</option>
                    <option value='JM'>Jamaica</option>
                    <option value='JP'>Japan</option>
                    <option value='JO'>Jordan</option>
                    <option value='KZ'>Kazakhstan</option>
                    <option value='KE'>Kenya</option>
                    <option value='KI'>Kiribati</option>
                    <option value='KW'>Kuwait</option>
                    <option value='KG'>Kyrgyzstan</option>
                    <option value='LA'>Laos</option>
                    <option value='LV'>Latvia</option>
                    <option value='LB'>Lebanon</option>
                    <option value='LS'>Lesotho</option>
                    <option value='LR'>Liberia</option>
                    <option value='LI'>Liechtenstein</option>
                    <option value='LT'>Lithuania</option>
                    <option value='LU'>Luxembourg</option>
                    <option value='MO'>Macau</option>
                    <option value='MG'>Madagascar</option>
                    <option value='MW'>Malawi</option>
                    <option value='MY'>Malaysia</option>
                    <option value='MV'>Maldives</option>
                    <option value='ML'>Mali</option>
                    <option value='MT'>Malta</option>
                    <option value='MH'>Marshall Islands</option>
                    <option value='MQ'>Martinique</option>
                    <option value='MR'>Mauritania</option>
                    <option value='YT'>Mayotte</option>
                    <option value='FX'>Metropolitan France</option>
                    <option value='MX'>Mexico</option>
                    <option value='MD'>Moldova</option>
                    <option value='MN'>Mongolia</option>
                    <option value='MA'>Morocco</option>
                    <option value='MZ'>Mozambique</option>
                    <option value='NA'>Namibia</option>
                    <option value='NR'>Nauru</option>
                    <option value='NP'>Nepal</option>
                    <option value='AN'>Neterlands Antilles</option>
                    <option value='NL'>Netherlands</option>
                    <option value='NC'>New Caledonia</option>
                    <option value='NZ'>New Zealand</option>
                    <option value='NI'>Nicaragua</option>
                    <option value='NE'>Niger</option>
                    <option value='NG'>Nigeria</option>
                    <option value='MP'>Northern Mariana Islands</option>
                    <option value='NO'>Norway</option>
                    <option value='OM'>Oman</option>
                    <option value='PK'>Pakistan</option>
                    <option value='PW'>Palau</option>
                    <option value='PA'>Panama</option>
                    <option value='PG'>Papua New Guinea</option>
                    <option value='PY'>Paraguay</option>
                    <option value='PE'>Peru</option>
                    <option value='PH' selected='selected'>Philippines</option>
                    <option value='PN'>Pitcairn</option>
                    <option value='PL'>Poland</option>
                    <option value='PT'>Portugal</option>
                    <option value='PR'>Puerto Rico</option>
                    <option value='QA'>Qatar</option>
                    <option value='KR'>Republic of Korea</option>
                    <option value='MK'>Republic of Macedonia</option>
                    <option value='RE'>Reunion</option>
                    <option value='RO'>Romania</option>
                    <option value='RU'>Russia</option>
                    <option value='ST'>Sao Tome and Principe</option>
                    <option value='SA'>Saudi Arabia</option>
                    <option value='SN'>Senegal</option>
                    <option value='SC'>Seychelles</option>
                    <option value='SL'>Sierra Leone</option>
                    <option value='SG'>Singapore</option>
                    <option value='SK'>Slovakia</option>
                    <option value='SI'>Slovenia</option>
                    <option value='SB'>Solomon Islands</option>
                    <option value='SO'>Somalia</option>
                    <option value='ZA'>South Africa</option>
                    <option value='ES'>Spain</option>
                    <option value='LK'>Sri Lanka</option>
                    <option value='SH'>St. Helena</option>
                    <option value='KN'>St. Kitts and Nevis</option>
                    <option value='LC'>St. Lucia</option>
                    <option value='VC'>St. Vincent and the Grenadines</option>
                    <option value='SD'>Sudan</option>
                    <option value='SR'>Suriname</option>
                    <option value='SJ'>Svalbard and Jan Mayen Islands</option>
                    <option value='SZ'>Swaziland</option>
                    <option value='SE'>Sweden</option>
                    <option value='CH'>Switzerland</option>
                    <option value='SY'>Syria</option>
                    <option value='TW'>Taiwan</option>
                    <option value='TJ'>Tajikistan</option>
                    <option value='TZ'>Tanzania</option>
                    <option value='TH'>Thailand</option>
                    <option value='TG'>Togo</option>
                    <option value='TO'>Tonga</option>
                    <option value='TT'>Trinidad and Tobago</option>
                    <option value='TR'>Turkey</option>
                    <option value='TM'>Turkmenistan</option>
                    <option value='TC'>Turks and Caicos Islands</option>
                    <option value='TV'>Tuvalu</option>
                    <option value='UG'>Uganda</option>
                    <option value='UA'>Ukraine</option>
                    <option value='AE'>United Arab Emirates</option>
                    <option value='GB'>United Kingdom</option>
                    <option value='US'>United States</option>
                    <option value='UY'>Uruguay</option>
                    <option value='UZ'>Uzbekistan</option>
                    <option value='VU'>Vanuatu</option>
                    <option value='VA'>Vatican City</option>
                    <option value='VE'>Venezuela</option>
                    <option value='VN'>Vietnam</option>
                    <option value='EH'>Western Sahara</option>
                    <option value='YE'>Yemen</option>
                    <option value='YU'>Yugoslavia</option>
                    <option value='ZR'>Zaire</option>
                    <option value='ZM'>Zambia</option>
                    <option value='ZW'>Zimbabwe</option>
                </select>";
	}
	
	
}
$Function = new Functions;
?>








