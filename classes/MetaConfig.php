<?php 
namespace MNWeddingPlugin;

if (!defined('ABSPATH')) {
    exit;
}

//configuration class for retrieving data from the database
class MetaConfig{


	//config for total statistics count
	public static function getCountSavedMeta($user_id){
		return array(
			'_attending' => get_user_meta($user_id, '_attending', true),
			'_plus_one' => get_user_meta($user_id, '_plus_one', true),
            '_not_attending' => get_user_meta($user_id, '_not_attending', true),
            '_overnight' => get_user_meta($user_id, '_overnight', true),
            '_night_before' => get_user_meta($user_id, '_night_before', true),
            '_vegetarian' => get_user_meta($user_id, '_vegetarian', true),
            '_vegan' => get_user_meta($user_id, '_vegan', true),
            '_gluten' => get_user_meta($user_id, '_gluten', true),
            '_children' => get_user_meta($user_id, '_children', true),
            '_babies' => get_user_meta($user_id, '_babies', true)
		);
	}

	
	//config for user data
	public static function getUserMeta($user_id){
		return array(
			'user_email' => get_userdata($user_id)->user_email,
            'email_guest' => get_user_meta($user_id, 'email_guest', true),
            'country_code' => get_user_meta($user_id, 'country_code', true),
            'phone_number' => get_user_meta($user_id, 'phone_number', true),
            'phone_guest' => get_user_meta($user_id, 'phone_guest', true),
            'last_updated' => get_user_meta($user_id, 'last_updated', true),
            'updated_by' => get_user_meta($user_id, 'updated_by', true),
            'reviewed' => get_user_meta($user_id, 'reviewed', true),
            'reviewed_date' => get_user_meta($user_id, 'reviewed_date', true)
        );
	}

	public static function getUserMessagesList($user_id){
		return array(
			'messages' => get_user_meta($user_id, 'messages', true)
		);
	}


	//config for guest data
	public static function getGuestMeta($user_id, $guest_id){

		return array(
			$guest_id.'_attending' => get_user_meta($user_id, $guest_id.'_attending', true),
			$guest_id.'_plus_one' => get_user_meta($user_id, $guest_id.'_plus_one', true),
	        $guest_id.'_overnight' => get_user_meta($user_id, $guest_id.'_overnight', true),
	        $guest_id.'_vegetarian' => get_user_meta($user_id, $guest_id.'_vegetarian', true),
	        $guest_id.'_vegan' => get_user_meta($user_id, $guest_id.'_vegan', true),
	        $guest_id.'_gluten' => get_user_meta($user_id, $guest_id.'_gluten', true),
	        $guest_id.'_allergies' => get_user_meta($user_id, $guest_id.'_allergies', true),
	        $guest_id.'_room_preference' => get_user_meta($user_id, $guest_id.'_room_preference', true),
	        $guest_id.'_comments' => get_user_meta($user_id, $guest_id.'_comments', true),
	        $guest_id.'_night_before' => get_user_meta($user_id, $guest_id.'_night_before', true)
		);

	}

	public static function hasNightBefore($user_id){
		return get_field('night_before', 'user_' . strval($user_id));
	}

	public static function guests($user_id){
		return get_field('guest_names', 'user_' . $user_id);
	}



	//Config for plus one
    public static function plusOneMeta($user_id, $guest_id){
    	return array(
    		$guest_id.'_overnight_plus' => get_user_meta($user_id, $guest_id.'_overnight_plus', true),
            $guest_id.'_vegetarian_plus' => get_user_meta($user_id, $guest_id.'_vegetarian_plus', true),
            $guest_id.'_vegan_plus' => get_user_meta($user_id, $guest_id.'_vegan_plus', true),
            $guest_id.'_gluten_plus' => get_user_meta($user_id, $guest_id.'_gluten_plus', true),
            $guest_id.'_name_plus' => get_user_meta($user_id, $guest_id.'_name_plus', true),
            $guest_id.'_allergies_plus' => get_user_meta($user_id, $guest_id.'_allergies_plus', true),
            $guest_id.'_comments_plus' => get_user_meta($user_id, $guest_id.'_comments_plus', true),
            $guest_id.'_night_before_plus' => get_user_meta($user_id, $guest_id.'_night_before_plus', true)
        	);
    }



    //config for combined guest and plus one data:
    public static function getCombinedGuestPlusMeta($user_id, $guest_id) {
        $guestMeta = self::getGuestMeta($user_id, $guest_id);
        $plusOneMeta = self::plusOneMeta($user_id, $guest_id);

        return array_merge($guestMeta, $plusOneMeta);
    }



    public static function getConversationMeta($post_id){
    	return array(
    		'_user_email' => get_post_meta($post_id, '_user_email', true),
            '_email_guest' => get_post_meta($post_id, '_email_guest', true),
            '_country_code' => get_post_meta($post_id, '_country_code', true),
            '_phone_number' => get_post_meta($post_id, '_phone_number', true),
            '_phone_guest' => get_post_meta($post_id, '_phone_guest', true),
            '_user_array' => get_post_meta($post_id, '_user_array', true),
            '_admin' => get_post_meta($post_id, '_admin', true),
            '_conversation_messages' => get_post_meta($post_id, '_conversation_messages', true)
    	);
    }

    public static function conversationSeenMeta($post_id, $user_id){
    	return array(
    		'_user_'.$user_id.'_seen' => get_post_meta($post_id, '_user_'.$user_id.'_seen', true)
    	);
    }
    



}