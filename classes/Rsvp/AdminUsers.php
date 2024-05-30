<?php
namespace MNWeddingPlugin\Rsvp;

use MNWeddingPlugin\MetaConfig;


if (!defined('ABSPATH')) {
    exit;
}

class AdminUsers{

	public function getSumbittedUsersSummary(){

		$all_user_ids = get_users(array('fields' => 'ID'));
		$users_with_meta = [];

		foreach($all_user_ids as $user_id){

			$current_meta = MetaConfig::getUserMeta($user_id);

			if($current_meta['last_updated'] != '' && $current_meta['reviewed'] !== 'on'){

				$user_info = get_userdata($user_id);
				$username = $user_info->user_login;
				$count_saved_meta = \MNWeddingPlugin\MetaConfig::getCountSavedMeta($user_id);

				$users_with_meta[] = [
					'user_id' => $user_id,
	                'username' => $username,
	                'count_saved_meta' => $count_saved_meta,
	                'last_updated' => $current_meta['last_updated']
				];

			}

		}

		// Sort by 'last_updated' descending
	    usort($users_with_meta, function ($a, $b) {
	        return strtotime($b['last_updated']) - strtotime($a['last_updated']);
	    });

	    // Iterate through sorted entries
	    foreach ($users_with_meta as $user_data) {
	        $last_updated = RsvpUtils::last_updated($user_data['user_id'], 'en');
	        $username = $user_data['username'];
			$user_id = $user_data['user_id'];
			$night_before = MetaConfig::hasNightBefore($user_id);
			$count_saved_meta = $user_data['count_saved_meta'];
			$attention = $this->getSubmittedUsersAttention($user_id);

	        include(plugin_dir_path(__FILE__) . '../../partials/rsvp/admin/user-summary-info.php');
	    }
	}


	public function getReviewedUsersSummary(){

		$all_user_ids = get_users(array('fields' => 'ID'));
		$users_with_meta = [];

		foreach($all_user_ids as $user_id){

			$current_meta = MetaConfig::getUserMeta($user_id);

			if($current_meta['last_updated'] != '' && $current_meta['reviewed'] === 'on'){

				$user_info = get_userdata($user_id);
				$username = $user_info->user_login;
				$count_saved_meta = \MNWeddingPlugin\MetaConfig::getCountSavedMeta($user_id);

				$users_with_meta[] = [
					'user_id' => $user_id,
	                'username' => $username,
	                'last_updated' => $current_meta['last_updated']
				];

			}

		}

		// Sort by 'last_updated' descending
	    usort($users_with_meta, function ($a, $b) {
	        return strtotime($b['last_updated']) - strtotime($a['last_updated']);
	    });

	    // Iterate through sorted entries
	    foreach ($users_with_meta as $user_data) {
	        $last_updated = RsvpUtils::last_updated($user_data['user_id'], 'en');
	        $username = $user_data['username'];
			$user_id = $user_data['user_id'];
			$attention = $this->getSubmittedUsersAttention($user_id);

	        include(plugin_dir_path(__FILE__) . '../../partials/rsvp/admin/user-summary-link.php');
	    }
	}

	public function getNotSubUsersSummary(){

		$all_user_ids = get_users(array('fields' => 'ID'));

		foreach($all_user_ids as $user_id){

			$current_meta = MetaConfig::getUserMeta($user_id);
			$user_info = get_userdata($user_id);
			$username = $user_info->user_login;

			if($current_meta['last_updated'] === ''){

				$username = $username;
				$user_id = $user_id;

	        include(plugin_dir_path(__FILE__) . '../../partials/rsvp/admin/user-summary-link-unsub.php');

			}

		}

	}


	//returns a notice if any of the users guests have submitted allergies, room preferences or additional comments
	private function getSubmittedUsersAttention($user_id){

		$guests = MetaConfig::guests($user_id);
		$guest_count = 0;

		$user_allergies = false;
		$user_room = false;
		$user_comments = false;

		$user_notice = [];

		foreach($guests as $guest_data){

			$guest_id = 'guest_'.$guest_count;
			$guest_meta = MetaConfig::getCombinedGuestPlusMeta($user_id, $guest_id);

			if( ($guest_meta[$guest_id.'_allergies'] !== '' || $guest_meta[$guest_id.'_allergies_plus'] !== '') && $user_allergies === false ){
				$user_allergies = true;
				$user_notice[] = 'Allergies';
			}

			if( $guest_meta[$guest_id.'_room_preference'] !== '' && $user_room === false ){
				$user_room = true;
				$user_notice[] = 'Room preference';
			}

			if( ($guest_meta[$guest_id.'_comments'] !== '' || $guest_meta[$guest_id.'_comments_plus'] !== '') && $user_comments === false ){
				$user_comments = true;
				$user_notice[] = 'Comments';
			}

			if($user_allergies === true && $user_room === true && $user_comments === true){
				break;
			}

			$guest_count++;
		}


		if( count($user_notice) > 0 ){

			$user_notice_icon = '<span class="issue-icon-circle"><span>!</span></span>';

			$user_notice_string = implode(', ', $user_notice);

			$user_notice_combined = $user_notice_icon.$user_notice_string;

			return $user_notice_combined;

		} else {

			return '';

		}

	}



}

