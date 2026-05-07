<?php
use App\Models\Admin;
use Illuminate\Support\Facades\DB;

function gv($params, $keys, $default = Null) {
	return (isset($params[$keys]) AND $params[$keys]) ? $params[$keys] : $default;
}

function gbv($params, $keys) {
	return (isset($params[$keys]) AND $params[$keys]) ? 1 : 0;
}

/*
 *  Used to check whether date is valid or not
 *  @param
 *  $date as timestamp or date variable
 *  @return true if valid date, else if not
 */

function validateDate($date) {
	$d = DateTime::createFromFormat('Y-m-d', $date);
	return $d && $d->format('Y-m-d') === $date;
}

/*
 *  Used to calculate date difference between two dates
 */

function dateDiff($date1, $date2) {
	if ($date2 > $date1) {
		return date_diff(date_create($date1), date_create($date2))->days;
	} else {
		return date_diff(date_create($date2), date_create($date1))->days;
	}
}

/*
 *  Used to get date with start midnight time
 *  @param
 *  $date as timestamp or date variable
 *  @return date with start midnight time
 */

function getStartOfDate($date) {
	return date('Y-m-d', strtotime($date)) . ' 00:00';
}

/*
 *  Used to get date with end midnight time
 *  @param
 *  $date as timestamp or date variable
 *  @return date with end midnight time
 */

function getEndOfDate($date) {
	return date('Y-m-d', strtotime($date)) . ' 23:59';
}

/*
 *  Used to get date in desired format
 *  @return date format
 */

function getDateFormat() {
	if (config('config.date_format') === 'DD-MM-YYYY') {
		return 'd-m-Y';
	} elseif (config('config.date_format') === 'MM-DD-YYYY') {
		return 'm-d-Y';
	} elseif (config('config.date_format') === 'DD-MMM-YYYY') {
		return 'd-M-Y';
	} elseif (config('config.date_format') === 'MMM-DD-YYYY') {
		return 'M-d-Y';
	} else {
		return 'd-m-Y';
	}
}

/*
 *  Used to convert date for database
 *  @param
 *  $date as date
 *  @return date
 */

function toDate($date) {
	if (!$date) {
		return;
	}

	return date('Y-m-d', strtotime($date));
}

/*
 *  Used to convert time for database
 *  @param
 *  $time as time
 *  @return time
 */

function toTime($time) {
	if (!$time) {
		return;
	}

	return date('H:i', strtotime($time));
}

/*
 *  Used to convert date in desired format
 *  @param
 *  $date as date
 *  @return date
 */

function showDate($date) {
	if (!$date) {
		return;
	}

	$date_format = getDateFormat();
	return date($date_format, strtotime($date));
}

/*
 *  Used to convert time in desired format
 *  @param
 *  $datetime as datetime
 *  @return datetime
 */

function showDateTime($time = '') {
	if (!$time) {
		return;
	}

	$date_format = getDateFormat();
	if (config('config.time_format') === 'H:mm') {
		return date($date_format . ',H:i', strtotime($time));
	} else {
		return date($date_format . ',h:i a', strtotime($time));
	}
}

/*
 *  Used to convert time in desired format
 *  @param
 *  $time as time
 *  @return time
 */

function showTime($time = '') {
	if (!$time) {
		return;
	}

	if (config('config.time_format') === 'H:mm') {
		return date('H:i', strtotime($time));
	} else {
		return date('h:i a', strtotime($time));
	}
}
//////////////////////////////////////////////////////////////////////// Date helper function ends

//////////////////////////////////////////////////////////////////////// String helper function starts

/*
 *  Used to convert slugs into human readable words
 *  @param
 *  $word as string
 *  @return string
 */

function toWord($word) {
	$word = str_replace('_', ' ', $word);
	$word = str_replace('-', ' ', $word);
	$word = ucwords($word);
	return $word;
}

function toSpan($data) {
	$per = explode('.', $data);
	return toWord($per[1] ?? $per[0]);
}
//permission
function split_name($name) {
	$data = [];
	foreach ($name as $value) {
		$per = explode('.', $value->name);
		$data[toWord($per[0])][] = $value->name;
	}
	return $data;

}

function getUserRoleName($user_id) {
	// $user = User::findOrFail($user_id);

	// $roles = $user->getRoleNames();

	// $role_name = '';

	// if (!empty($roles[0])) {
	// 	$array = explode('#', $roles[0], 2);
	// 	$role_name = !empty($array[0]) ? $array[0] : '';
	// }
	// return $role_name;
    return '';
}

/*
 *  Used to generate random string of certain lenght
 *  @param
 *  $length as numeric
 *  $type as optional param, can be token or password or username. Default is token
 *  @return random string
 */

function randomString($length, $type = 'token') {
	if ($type === 'password') {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
	} elseif ($type === 'username') {
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
	} else {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	}
	$token = substr(str_shuffle($chars), 0, $length);
	return $token;
}

/*
 *  Used to whether string contains unicode
 *  @param
 *  $string as string
 *  @return boolean
 */

function checkUnicode($string) {
	if (strlen($string) != strlen(utf8_decode($string))) {
		return true;
	} else {
		return false;
	}
}

/*
 *  Used to generate slug from string
 *  @param
 *  $string as string
 *  @return slug
 */

function createSlug($string) {
	if (!$string) {
		return;
	}

	if (checkUnicode($string)) {
		$slug = str_replace(' ', '-', $string);
	} else {
		$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string));
	}
	return $slug;
}

/*
 *  Used to remove script tag from input
 *  @param
 *  $string as string
 *  @return slug
 */

function scriptStripper($string) {
	return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $string);
}

function isInteger($input) {
	return (ctype_digit(strval($input)));
}

//////////////////////////////////////////////////////////////////////////////////// String helper function ends

//////////////////////////////////////////////////////////////////////////////////// Select helper function starts

/*
 *  Used to generate select option for vue.js multiselect plugin
 *  @param
 *  $data as array of key & value pair
 *  @return select options
 */

function generateSelectOption($data) {
	$options = array();
	foreach ($data as $key => $value) {
		$options[] = ['name' => $value, 'id' => $key];
	}
	return $options;
}

/*
 *  Used to generate translated select option for vue.js multiselect plugin
 *  @param
 *  $data as array of key & value pair
 *  @return select options
 */

function generateTranslatedSelectOption($data) {
	$options = array();
	foreach ($data as $key => $value) {
		$options[] = ['name' => trans('list.' . $value), 'id' => $value];
	}
	return $options;
}

/*
 *  Used to generate select option for default select box
 *  @param
 *  $data as array of key & value pair
 *  @return select options
 */

function generateNormalSelectOption($data) {
	$options = array();
	foreach ($data as $key => $value) {
		$options[] = ['text' => $value, 'value' => $key];
	}
	return $options;
}

/*
 *  Used to generate select option for default select box (translated)
 *  @param
 *  $data as array of key & value pair
 *  @return select options
 */

function generateNormalTranslatedSelectOption($data) {
	$options = array();
	foreach ($data as $key => $value) {
		$options[] = ['text' => trans('list.' . $value), 'value' => $value];
	}
	return $options;
}

/*
 *  Used to generate select option for default select box where value is same as text
 *  @param
 *  $data as array of key & value pair
 *  @return select options
 */

function generateNormalSelectOptionValueOnly($data) {
	$options = array();
	foreach ($data as $value) {
		$options[] = ['text' => $value, 'value' => $value];
	}
	return $options;
}

//////////////////////////////////////////////////////////////////////////////////// Select helper function ends

/*
 *  Used to round number
 *  @param
 *  $number as numeric value
 *  $decimal_place as integer for round precision
 *  @return number
 */

function formatNumber($number, $decimal_place = 2) {
	return round($number, $decimal_place);
}

////////////////////////////////////////////////////////////////////////////////////// IP helper function starts

/*
 *  Used to get IP address of visitor
 *  @return date
 */

function getRemoteIPAddress() {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		return $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	return array_key_exists('REMOTE_ADDR', $_SERVER) ? $_SERVER['REMOTE_ADDR'] : null;
}

/*
 *  Used to get IP address of visitor
 *  @return IP address
 */

function getClientIp() {
	$ips = getRemoteIPAddress();
	$ips = explode(',', $ips);
	return !empty($ips[0]) ? $ips[0] : \Request::getClientIp();
}

////////////////////////////////////////////////////////////////////////////////////////// IP helper function ends

/*
 *  Used to check mode
 *  @return boolean
 */

function isTestMode() {
	if (env('APP_MODE') == 'test') {
		return true;
	} else {
		return false;
	}
}

/*
 * get Maximum post size of server
 */

function getPostMaxSize() {
	if (is_numeric($postMaxSize = ini_get('post_max_size'))) {
		return (int) $postMaxSize;
	}

	$metric = strtoupper(substr($postMaxSize, -1));
	$postMaxSize = (int) $postMaxSize;

	switch ($metric) {
	case 'K':
		return $postMaxSize * 1024;
	case 'M':
		return $postMaxSize * 1048576;
	case 'G':
		return $postMaxSize * 1073741824;
	default:
		return $postMaxSize;
	}
}

/*
 *  Used to get value-list json
 *  @return array
 */

function getVar($list) {
	$file = resource_path('var/' . $list . '.json');

	return (\File::exists($file)) ? json_decode(file_get_contents($file), true) : [];
}

/*
 *  Used to get seed value-list json
 *  @return array
 */

function getSeedVar($list) {
	$file = resource_path('var/seed/' . $list . '.json');

	return (\File::exists($file)) ? json_decode(file_get_contents($file), true) : [];
}

function isConnected() {
	$connected = @fsockopen("www.google.com", 80);
	if ($connected) {
		fclose($connected);
		return true;
	}

	return false;
}

/*
 *  Used to URL via CURL
 *  @return array
 */

function curlIt($url, $postData = array()) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$response = curl_exec($ch);
	curl_close($ch);
	return json_decode($response, true);
}

/*
 *  Used to get Default Currency
 *  @return array
 */

function getDefaultCurrency($prop = null) {
	$default_currency_key = array_search(config('config.currency'), array_column(getVar('currency'), 'name'));
	$currency = ($default_currency_key !== false) ? getVar('currency')[$default_currency_key] : null;

	if (!$prop) {
		return $currency;
	}

	return ($currency && isset($currency[$prop])) ? $currency[$prop] : null;
}

/*
 *  Used to format amount in given currency
 *  @param
 *  $amount as numeric
 *  $symbol as boolean, 1 for with currency symbol or 0 for without currency symbol
 *  @return string
 */
function currency($amount, $symbol = 0) {
	$currency = getDefaultCurrency();

	if (!$currency) {
		return round($amount, 2);
	}

	$decimal_value = $currency['decimal_place'];

	if (!$symbol) {
		return round($amount, $decimal_value);
	}

	$position = $currency['position'];
	$currency_symbol = $currency['symbol'];

	$amount = round($amount, $decimal_value);

	if ($position === 'suffix') {
		return $amount . '' . $currency_symbol;
	} else {
		return $currency_symbol . '' . $amount;
	}
}

function getEmployeeDesignation($employee, $date = null) {
	$date = ($date) ?: date('Y-m-d');

	if (!$employee) {
		return null;
	}

	if (!$employee->relationLoaded('employeeDesignations')) {
		$employee->load('employeeDesignations');
	}

	return $employee->employeeDesignations->sortByDesc('date_effective')->firstWhere('date_effective', '<=', $date);
}

function getEmployeeDesignationId($employee, $date = null) {
	$designation = getEmployeeDesignation($employee, $date);

	return $designation ? $designation->designation_id : null;
}

function getEmployeeDesignationName($employee, $date = null) {
	$designation = getEmployeeDesignation($employee, $date);

	return $designation ? $designation->Designation->name . ' (' . $designation->Designation->EmployeeCategory->name . ')' : null;
}

function getEmployeeTerm($employee, $date = null) {
	$date = ($date) ?: date('Y-m-d');

	return $employee->EmployeeTerms->sortByDesc('date_of_joining')->filter(function ($term) use ($date) {
		return ($term->date_of_joining <= $date && (!$term->date_of_leaving || $term->date_of_leaving >= $date));
	})->first();
}

function isActiveEmployee($employee, $date = null) {
	return getEmployeeTerm($employee, $date) ? true : false;
}

/*
 *  Used to get children from tree structure
 *  @return array
 */

function getChilds($array, $currentParent = 1, $level = 1, $child = array(), $currLevel = 0, $prevLevel = -1) {
	foreach ($array as $categoryId => $category) {
		if ($currentParent === $category['parent_id']) {
			if ($currLevel > $prevLevel) {
			}
			if ($currLevel === $prevLevel) {
			}
			$child[] = $categoryId;
			if ($currLevel > $prevLevel) {
				$prevLevel = $currLevel;
			}
			$currLevel++;
			if ($level) {
				$child = getChilds($array, $categoryId, $level, $child, $currLevel, $prevLevel);
			}
			$currLevel--;
		}
	}
	if ($currLevel === $prevLevel) {
	}
	return $child;
}

function getparents($id) {

}

/*
 *  Used to get logo
 *  @return string
 */
function getLogo() {
	if (config('config.logo') && \File::exists(config('config.logo'))) {
		return '<img src="' . asset('/' . config('config.logo')) . '" alt="' . config('config.institute_name', config('app.name', 'Satt Loan')) . '">';
	} else {
		return '<img src="' . asset('/asset/logo.png') . '" alt="' . config('config.institute_name', config('app.name', 'Satt Loan')) . '">';
	}
}

/*
 *  Used to get logo
 *  @return string
 */
function getSmLogo() {
	if (config('config.sm_logo') && \File::exists(config('config.sm_logo'))) {
		return '<img src="' . asset('/' . config('config.sm_logo')) . '" alt="' . config('config.institute_name', config('app.name', 'Satt Loan')) . '">';
	} else {
		return '<img src="' . asset('/asset/logo_sm.png') . '" alt="' . config('config.institute_name', config('app.name', 'Satt Loan')) . '">';
	}
}

function numberPadding($number, $length) {
	return str_pad($number, $length, '0', STR_PAD_LEFT);
}

function getSelectedEmployee($employee) {
	return ($employee) ? ['id' => $employee->id, 'name' => $employee->name . ' (' . $employee->code . ')'] : null;
}

function createExcerpt($content, $length = 20, $more = '...') {
	$excerpt = strip_tags(trim($content));
	$words = str_word_count($excerpt, 2);
	if (count($words) > $length) {
		$words = array_slice($words, 0, $length, true);
		end($words);
		// $position = key( $words ) + strlen( current( $words ) );
		$position = key($words);
		$excerpt = substr($excerpt, 0, $position) . $more;
	}
	return $excerpt;
}

function calc($mathString) {
	$mathString = trim($mathString);
	$mathString = preg_replace('[^0-9\+-\*\/\(\) ]', '', $mathString);

	$compute = create_function("", "return (" . $mathString . ");");
	return 0 + $compute();
}

function searchByKey($data, $key, $value) {
	$index = array_search($value, array_column($data, $key));

	return ($index === FALSE) ? [] : $data[$index];
}

function check_https() {
	if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
		return 'https';
	}
	return 'http';
}

function app_url() {
	return check_https() . '://' . $_SERVER['HTTP_HOST'] . '/';
}

function country_name($key) {
	$country = getVar('country');
	return isset($country[$key]) ? $country[$key] : 'Unknown';
}

function in_multi_array($needle, $haystack, $strict = false) {
	foreach ($haystack as $item) {
		if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_multi_array($needle, $item, $strict))) {
			return true;
		}
	}
	return false;
}

function curency() {
	return $currency = [
		'AED' => '&#1583;.&#1573;', // ?
		'AFN' => '&#65;&#102;',
		'ALL' => '&#76;&#101;&#107;',
		'AMD' => '',
		'ANG' => '&#402;',
		'AOA' => '&#75;&#122;', // ?
		'ARS' => '&#36;',
		'AUD' => '&#36;',
		'AWG' => '&#402;',
		'AZN' => '&#1084;&#1072;&#1085;',
		'BAM' => '&#75;&#77;',
		'BBD' => '&#36;',
		'BDT' => '&#2547;', // ?
		'BGN' => '&#1083;&#1074;',
		'BHD' => '.&#1583;.&#1576;', // ?
		'BIF' => '&#70;&#66;&#117;', // ?
		'BMD' => '&#36;',
		'BND' => '&#36;',
		'BOB' => '&#36;&#98;',
		'BRL' => '&#82;&#36;',
		'BSD' => '&#36;',
		'BTN' => '&#78;&#117;&#46;', // ?
		'BWP' => '&#80;',
		'BYR' => '&#112;&#46;',
		'BZD' => '&#66;&#90;&#36;',
		'CAD' => '&#36;',
		'CDF' => '&#70;&#67;',
		'CHF' => '&#67;&#72;&#70;',
		'CLF' => '', // ?
		'CLP' => '&#36;',
		'CNY' => '&#165;',
		'COP' => '&#36;',
		'CRC' => '&#8353;',
		'CUP' => '&#8396;',
		'CVE' => '&#36;', // ?
		'CZK' => '&#75;&#269;',
		'DJF' => '&#70;&#100;&#106;', // ?
		'DKK' => '&#107;&#114;',
		'DOP' => '&#82;&#68;&#36;',
		'DZD' => '&#1583;&#1580;', // ?
		'EGP' => '&#163;',
		'ETB' => '&#66;&#114;',
		'EUR' => '&#8364;',
		'FJD' => '&#36;',
		'FKP' => '&#163;',
		'GBP' => '&#163;',
		'GEL' => '&#4314;', // ?
		'GHS' => '&#162;',
		'GIP' => '&#163;',
		'GMD' => '&#68;', // ?
		'GNF' => '&#70;&#71;', // ?
		'GTQ' => '&#81;',
		'GYD' => '&#36;',
		'HKD' => '&#36;',
		'HNL' => '&#76;',
		'HRK' => '&#107;&#110;',
		'HTG' => '&#71;', // ?
		'HUF' => '&#70;&#116;',
		'IDR' => '&#82;&#112;',
		'ILS' => '&#8362;',
		'INR' => '&#8377;',
		'IQD' => '&#1593;.&#1583;', // ?
		'IRR' => '&#65020;',
		'ISK' => '&#107;&#114;',
		'JEP' => '&#163;',
		'JMD' => '&#74;&#36;',
		'JOD' => '&#74;&#68;', // ?
		'JPY' => '&#165;',
		'KES' => '&#75;&#83;&#104;', // ?
		'KGS' => '&#1083;&#1074;',
		'KHR' => '&#6107;',
		'KMF' => '&#67;&#70;', // ?
		'KPW' => '&#8361;',
		'KRW' => '&#8361;',
		'KWD' => '&#1583;.&#1603;', // ?
		'KYD' => '&#36;',
		'KZT' => '&#1083;&#1074;',
		'LAK' => '&#8365;',
		'LBP' => '&#163;',
		'LKR' => '&#8360;',
		'LRD' => '&#36;',
		'LSL' => '&#76;', // ?
		'LTL' => '&#76;&#116;',
		'LVL' => '&#76;&#115;',
		'LYD' => '&#1604;.&#1583;', // ?
		'MAD' => '&#1583;.&#1605;.', //?
		'MDL' => '&#76;',
		'MGA' => '&#65;&#114;', // ?
		'MKD' => '&#1076;&#1077;&#1085;',
		'MMK' => '&#75;',
		'MNT' => '&#8366;',
		'MOP' => '&#77;&#79;&#80;&#36;', // ?
		'MRO' => '&#85;&#77;', // ?
		'MUR' => '&#8360;', // ?
		'MVR' => '.&#1923;', // ?
		'MWK' => '&#77;&#75;',
		'MXN' => '&#36;',
		'MYR' => '&#82;&#77;',
		'MZN' => '&#77;&#84;',
		'NAD' => '&#36;',
		'NGN' => '&#8358;',
		'NIO' => '&#67;&#36;',
		'NOK' => '&#107;&#114;',
		'NPR' => '&#8360;',
		'NZD' => '&#36;',
		'OMR' => '&#65020;',
		'PAB' => '&#66;&#47;&#46;',
		'PEN' => '&#83;&#47;&#46;',
		'PGK' => '&#75;', // ?
		'PHP' => '&#8369;',
		'PKR' => '&#8360;',
		'PLN' => '&#122;&#322;',
		'PYG' => '&#71;&#115;',
		'QAR' => '&#65020;',
		'RON' => '&#108;&#101;&#105;',
		'RSD' => '&#1044;&#1080;&#1085;&#46;',
		'RUB' => '&#1088;&#1091;&#1073;',
		'RWF' => '&#1585;.&#1587;',
		'SAR' => '&#65020;',
		'SBD' => '&#36;',
		'SCR' => '&#8360;',
		'SDG' => '&#163;', // ?
		'SEK' => '&#107;&#114;',
		'SGD' => '&#36;',
		'SHP' => '&#163;',
		'SLL' => '&#76;&#101;', // ?
		'SOS' => '&#83;',
		'SRD' => '&#36;',
		'STD' => '&#68;&#98;', // ?
		'SVC' => '&#36;',
		'SYP' => '&#163;',
		'SZL' => '&#76;', // ?
		'THB' => '&#3647;',
		'TJS' => '&#84;&#74;&#83;', // ? TJS (guess)
		'TMT' => '&#109;',
		'TND' => '&#1583;.&#1578;',
		'TOP' => '&#84;&#36;',
		'TRY' => '&#8356;', // New Turkey Lira (old symbol used)
		'TTD' => '&#36;',
		'TWD' => '&#78;&#84;&#36;',
		'TZS' => '',
		'UAH' => '&#8372;',
		'UGX' => '&#85;&#83;&#104;',
		'USD' => '&#36;',
		'UYU' => '&#36;&#85;',
		'UZS' => '&#1083;&#1074;',
		'VEF' => '&#66;&#115;',
		'VND' => '&#8363;',
		'VUV' => '&#86;&#84;',
		'WST' => '&#87;&#83;&#36;',
		'XAF' => '&#70;&#67;&#70;&#65;',
		'XCD' => '&#36;',
		'XDR' => '',
		'XOF' => '',
		'XPF' => '&#70;',
		'YER' => '&#65020;',
		'ZAR' => '&#82;',
		'ZMK' => '&#90;&#75;', // ?
		'ZWL' => '&#90;&#36;',
	];
}

function tz_list() {
	$zones_array = array();
	$timestamp = time();
	foreach (timezone_identifiers_list() as $key => $zone) {
		date_default_timezone_set($zone);
		$zones_array[$key]['zone'] = $zone;
		$zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
	}
	return $zones_array;
}

if (!function_exists('get_settings')) {
	function get_settings($name)
	{
		$setting = DB::table('settings')->where('name', $name)->get();
		if (!$setting->isEmpty()) {
			return $setting[0]->value;
		}

		return false;
	}
}

if (!function_exists('upload')) {
	function upload($folder, $image,$type='images')
    {
        $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

        $originalName = str_replace(' ', '-', $originalName);

        $fileName = $originalName . rand(1000, 99999) . '.' . $image->getClientOriginalExtension();

        $image->storeAs($type . '/' . $folder, $fileName, 'public'); // Specify 'public' disk
        $image->storeAs($type . '/' . $folder, $fileName, 'public');

        $productImage = 'storage/'. $type . '/' . $folder . '/' . $fileName;
        return $productImage;
    }
}

if (!function_exists('split_name')) {
	function split_name($name) {
        $data = [];
        foreach ($name as $value) {
            $per = explode('.', $value->name);
            $data[toWord($per[0])][] = $value->name;
        }
        return $data;
    }
}

if (!function_exists('toWord')) {
	function toWord($word) {
        $word = str_replace('_', ' ', $word);
        $word = str_replace('-', ' ', $word);
        $word = ucwords($word);

        return $word;
    }
}

if (!function_exists('toUnderscore')) {
	function toUnderscore($text) {
        $text = str_replace(' ', '_', $text);
        $text = str_replace(' ', '_', $text);
        return $text;
    }
}

function getparent($category) {
	$find = App\Catagory::where('id', $category)->first();

	$parent_id = $find->parent_id;
	if($parent_id != 0) {
		$category = $parent_id;
		getparent($category);

	}

	echo '
	<li class="breadcrumb-item text-info"><a class=""  href="'.$find->catagory_slug.'">'.$find->catagory_name_in_bangla.'</a></li>
	';

}

function xyz($cat_id, $tags) {
	$find = App\Catagory::where('id', $cat_id)->first();
	$tags[] = $find->catagory_name_in_bangla;
	echo '<a href="'.$find->catagory_slug.'"> <span class="badge ml-1 badge-info p-2">'.$find->catagory_name_in_bangla.'</span></a>';
	$parent_id = $find->parent_id;
	if($parent_id != 0) {
		$category = $parent_id;
		xyz($category, $tags);
	}
}


function is_english($str)
{
    if (strlen($str) != strlen(utf8_decode($str))) {
        return false;
    } else {
        return true;
    }
}

function text_shorten($text, $limit = 200){
	$text = $text. " ";
	$text = substr($text, 0, $limit);
	$text = substr($text, 0, strrpos($text, ' '));
	return $text;
}//textShorten

function remove_special_char($string) {
    $string = html_entity_decode($string);
    $string = strip_tags($string);

	$string = htmlspecialchars($string);

	$string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
	$string = str_replace(array('[\', \', ]','(', ')', '{', '}', '[', ']', '|', '?', '-', '_', ',', '~', '`', '/', '\\', '"', "'", ':'), '', $string);
	$string = preg_replace('/\[.*\]/U', '', $string);

    $string = preg_replace('/!|@|#|%|&/', '', $string);

    $string = htmlentities($string, ENT_COMPAT, 'utf-8');
    $string = str_replace('&times;', 'x', $string);
    $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );

    $string = preg_replace('/\s+/u', ' ', trim($string)); // for multiple spaces
    $string = preg_replace('/-+/', ' ', $string); //for multiple -
    return strtolower(trim($string, ' '));
}

function truncate($text, $chars = 80) {

    if(strlen($text) > $chars) {
        $text = $text.' ';
        $text = substr($text, 0, $chars);
        $text = substr($text, 0, strrpos($text ,' '));
        $text = $text;
    }

    return $text;
}

function make_slug($string){

    $string = remove_special_char($string);
    $string = text_shorten($string);
    $string = str_replace(' ', '-', $string);
    return $string;
}
// dd(make_slug('&lt;math xmlns=&quot;http://www.w3.org/1998/Math/MathML&quot;&gt;&lt;mo&gt;&amp;#xA0;&lt;/mo&gt;&lt;mi&gt;p&lt;/mi&gt;&lt;mo&gt;=&lt;/mo&gt;&lt;mi&gt;a&lt;/mi&gt;&lt;mo&gt;&amp;#xD7;&lt;/mo&gt;&lt;mi&gt;b&lt;/mi&gt;&lt;/math&gt; হলে এবং  a ও b  উভয়কে একত্রে ১০% বৃদ্ধি করা হলে  p  এর মান শতকরা কত বৃদ্ধি পাবে? '));
