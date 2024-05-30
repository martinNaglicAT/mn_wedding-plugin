<?php
namespace MNWeddingPlugin;

use DateTime;


if (!defined('ABSPATH')) {
    exit;
}

class PluginUtils{
	public static function dateFormatting($date_string) {
		// Check if the date string is valid
	    if (!$date_string || $date_string === '0' || !strtotime($date_string)) {
	        // Log or handle the invalid date string
	        error_log("Invalid date string encountered: " . $date_string);
	        // Return a default value or indicate an error
	        return "Invalid date";
	    }

	    try {
	        $datetime = new DateTime($date_string);
	        $year = $datetime->format('Y');
	        $month = $datetime->format('m');
	        $day = $datetime->format('d');
	        return $day.'.'.$month.'.'.$year;
	    } catch (Exception $e) {
	        // Log the exception message
	        error_log("Error parsing date string: " . $e->getMessage());
	        // Return a default value or indicate an error
	        return "Error formatting date";
	    }
	    /*$datetime = new DateTime($date_string);

	    $year = $datetime->format('Y');
	    $month = $datetime->format('m');
	    $day = $datetime->format('d');

	    return $day.'.'.$month.'.'.$year;*/
	}


	public static function timeFormatting($date_string) {
	    // Check if the date string is valid
	    if (!$date_string || $date_string === '0' || !strtotime($date_string)) {
	        // Log or handle the invalid date string
	        error_log("Invalid date string encountered: " . $date_string);
	        // Return a default value or indicate an error
	        return "Invalid time";
	    }

	    try {
	        $datetime = new DateTime($date_string);
	        $time = $datetime->format('H:i');
	        return $time;
	    } catch (Exception $e) {
	        // Log the exception message
	        error_log("Error parsing date string: " . $e->getMessage());
	        // Return a default value or indicate an error
	        return "Error formatting time";
	    }
	}

	public static function register_query_vars($vars) {
            $vars[] = 'mnwedding_action'; 
            $vars[] = 'mnwedding_user_id';
            $vars[] = 'chat_id';
            return $vars;
    }

    public static function selected_option($user_id, $field_name, $option_value, $default_value) {

		$current_meta = MetaConfig::getUsermeta($user_id);

    
	    if ($current_meta[$field_name] != null) {
	        $current_value = $current_meta[$field_name];
	    } else {
	        // If not set, use the default value
	        $current_value = $default_value;
	    }

	    // Return 'selected' if the current value matches the option value
	    return $current_value == $option_value ? 'selected' : '';
	}

	public static function sanitize_phone_number($phone) {
        return preg_replace("/[^0-9\- ]/", "", $phone);
    }
}