<?php
namespace MNWeddingPlugin\Rsvp;

use MNWeddingPlugin\MetaConfig;
use MNWeddingPlugin\PluginUtils;

if (!defined('ABSPATH')) {
    exit;
}


//utility class for manipulating RSVP data within different classes
class RsvpUtils{


    public static function init() {
	    add_action('wp_ajax_mark_as_reviewed', '\\MNWeddingPlugin\\Rsvp\\RsvpUtils::mark_as_reviewed');
	}

	public static function last_updated($user_id, $current_language){

	    $updated = false;

	    $current_meta = MetaConfig::getUserMeta($user_id);

	    $last_updated_data = $current_meta['last_updated'];
	    $updated_by = $current_meta['updated_by'];

	    $date = PluginUtils::dateFormatting($last_updated_data);
	    $time = PluginUtils::timeFormatting($last_updated_data);

	    if($last_updated_data !== '' && $updated_by !== ''){
	        $updated = true;

	        switch ($current_language) {
	        case 'de':
	            $updated_string_first = 'Zuletzt aktualisiert ';
	            if($updated_by === 'user'){
	                $updated_string_second = '';
	            } else {
	                $updated_string_second = 'durch Admin ';
	            }
	            $updated_string_third = 'am '.$date.' um '.$time.'.';

	            break;
	        case 'si':
	            $updated_string_first = 'Zadnjič posodob';
	            if($updated_by === 'user'){
	                $updated_string_second = 'ljeno ';
	            } else {
	                $updated_string_second = 'il Admin ';
	            }
	            $updated_string_third = ' '.$date.' ob '.$time.'.';

	            break;
	        default:
	            // Default language translations or the original values
	            $updated_string_first = "Last updated ";
	            if($updated_by === 'user'){
	                $updated_string_second = '';
	            } else {
	                $updated_string_second = 'by Admin ';
	            }
	            $updated_string_third = 'on '.$date.' at '.$time.'.';
	        }

	        $updated_string = $updated_string_first.$updated_string_second.$updated_string_third;

	    } else {

	        switch ($current_language){
	            case 'de':
	                $updated_string = 'RSVP wurde noch nicht aktualisiert.';
	                break;
	            case 'si':
	                $updated_string = 'RSVP še ni bil posodobljen.';
	                break;
	            default:
	                $updated_string = 'RSVP has not been updated yet.';
	                break;
	        }
	    }

	    return $updated_string;


	}

	public static function last_reviewed($user_id){

		$current_meta = MetaConfig::getUserMeta($user_id);
		$reviewed = $current_meta['reviewed'];
		$review_date = $current_meta['reviewed_date'];

		if($review_date !== ''){
			if($reviewed === 'off'){
				$review_string = 'This post has been updated since last review on '.$review_date.'.';
			} else {
				$review_string = 'Last reviewed on '.$review_date.'.';
			}

		} else {
			$review_string = 'The submission has not yet been reviewed';
		}

		return $review_string;

	}

	public static function checkValues(){
		return array('_attending', '_overnight', '_vegetarian', '_vegan', '_gluten', '_night_before', '_plus_one');
	}

	public static function textValues() {
		return array('_name', '_allergies', '_comments', '_room_preference');
	}

	public static function specialValues() {
		return array('_allergies', '_comments', '_room_preference');
	}

	public static function countMetaKeys(){
		return array(
            '_attending',
            '_plus_one',
            '_not_attending',
            '_overnight',
            '_night_before',
            '_vegetarian',
            '_vegan',
            '_gluten',
            '_children',
            '_babies'
        );
	}


	public static function getTotalCounts(){

		$count_meta = array(
            '_attending' => 0,
            '_plus_one' => 0,
            '_not_attending' => 0,
            '_overnight' => 0,
            '_night_before' => 0,
            '_vegetarian' => 0,
            '_vegan' => 0,
            '_gluten' => 0,
            '_children' => 0,
            '_babies' => 0
        );

		$all_user_ids = get_users(array('fields' => 'ID'));

		foreach ($all_user_ids as $user_id) {
			$saved_count_meta = MetaConfig::getCountSavedMeta($user_id);

			foreach ($saved_count_meta as $meta_key => $meta_value){
				$count_meta[$meta_key] += intval($meta_value);
			}
		}

		return $count_meta;

	}

	public static function countLabels(){
		return array(
			'_attending' => 'Attending',
			'_plus_one' => 'Plus one',
            '_not_attending' => 'Not attending',
            '_overnight' => 'Overnight',
            '_night_before' => 'Night before',
            '_vegetarian' => 'Vegetarian',
            '_vegan' => 'Vegan',
            '_gluten' => 'Gluten',
            '_children' => 'Children',
            '_babies' => 'Babies'
		);
	}


	public static function getAdminHeader() {
        include( plugin_dir_path (__FILE__) . '../../partials/admin-header.php');
    }


	public static function mark_as_reviewed() {
	    check_ajax_referer('mn_wedding_plugin_nonce', 'nonce');

	    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
	    $reviewed = isset($_POST['reviewed']) ? $_POST['reviewed'] : 'off';
	    $reviewed_meta = MetaConfig::getUserMeta($user_id)['reviewed'];

	    if (!current_user_can('edit_user', $user_id)) {
	        wp_send_json_error('You do not have permission to mark this RSVP as reviewed.');
	    }

	    if ($reviewed === 'on' && $reviewed !== $reviewed_meta) {
	    	$current_date = current_time(get_option('date_format'));
	        update_user_meta($user_id, 'reviewed', $reviewed);
	        update_user_meta($user_id, 'reviewed_date', $current_date);
	    } else {
	    	if($reviewed_meta !== 'off'){
	    		update_user_meta($user_id, 'reviewed', 'off');
	    	}
	        
	    }

	    wp_send_json_success('RSVP review status updated.');
	}


    public static function mnwedding_rsvp_admin_page_content() {
	    $action = get_query_var('mnwedding_action');
	    $user_id = get_query_var('mnwedding_user_id');
	    
	    if ($action && $user_id) {
	        if ($action === 'edit') {
	            $rsvp_render = new RsvpRender($user_id);
	            $rsvp_render->display_custom_rsvp_form($user_id);
	        } elseif ($action === 'view') {
	            $rsvp_render = new RsvpSummary($user_id);
	            $rsvp_render->display_rsvp_summary($user_id);
	        }
	    } 
	}


	


}