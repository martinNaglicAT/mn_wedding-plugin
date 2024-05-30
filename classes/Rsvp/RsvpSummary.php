<?php
namespace MNWeddingPlugin\Rsvp;

use MNWeddingPlugin\MetaConfig;

if (!defined('ABSPATH')) {
    exit;
}

class RsvpSummary{

	private $user_id;

    public function __construct($user_id) {
        $this->user_id = $user_id;
    }

	public function display_rsvp_summary($user_id){

	    $current_language = pll_current_language();

	    $user_info = get_userdata($user_id);

	    $current_meta = MetaConfig::getUserMeta($user_id);


	    $count_saved_meta = MetaConfig::getCountSavedMeta($user_id);

	    $guests = get_field('guest_names', 'user_' . $user_id);


	    include( dirname(plugin_dir_path(__FILE__), 2) . '/partials/rsvp/summary/rsvp-summary-sheet.php');


	}

	public function display_attending_guests_data($user_id, $current_language, $guests){

	    $guest_count = 0;

	    $user_language = $current_language;

	    foreach($guests as $guest){

	        $guest_id = 'guest_'.$guest_count;

	        $attending = get_user_meta($user_id, $guest_id.'_attending', true);

	        //echo $attending;

	        if($attending == 'on'){

	            $has_plus_one = false;

	            $guest_saved_meta = MetaConfig::getGuestMeta($user_id, $guest_id);
	            $guest_acf_meta = array(
	                'name' => $guest['name'],
	                'is_child' => $guest['child'],
	                'is_baby' => $guest['baby']
	            );

	            $guest_meta = array_merge($guest_saved_meta, $guest_acf_meta);

	            if($guest_meta[$guest_id.'_plus_one'] == 'on'){
	                $has_plus_one = true;

	                $plus_one_meta = MetaConfig::plusOneMeta($user_id, $guest_id);

	            }



	            include( dirname(plugin_dir_path(__FILE__), 2) . '/partials/rsvp/summary/rsvp-guest-summary-sheet.php');

	        }


	        $guest_count++;
	    }

	}

	public function display_absent_guests_data($user_id, $guests){

	    $guest_count = 0;

	    foreach($guests as $guest){

	        $guest_id = 'guest_'.$guest_count;

	        $attending = get_user_meta($user_id, $guest_id.'_attending', true);

	        if($attending == 'off'){

	            echo '<h3>'.$guest['name'].'</h3>';
	        }

	        $guest_count++;

	    }

	    if(get_user_meta($user_id, '_not_attending', true) == '0'){
	        echo '<h3>'.pll__('All guests are attending.').'</h3>';
	    }

	}

}