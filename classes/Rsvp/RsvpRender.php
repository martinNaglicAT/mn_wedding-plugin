<?php 
namespace MNWeddingPlugin\Rsvp;

use MNWeddingPlugin\MetaConfig;

if (!defined('ABSPATH')) {
    exit;
}

class RsvpRender{

	private $user_id;

    public function __construct($user_id) {
        $this->user_id = $user_id;
    }


	private function checkbox_state($user_id, $field_part, $guest_id) {

		$current_meta = MetaConfig::getCombinedGuestPlusMeta($user_id, $guest_id);

		$field_name = $guest_id.$field_part;

	    if (get_user_meta($user_id, $field_name, true) === "on" ) {
	        $checkbox_state = 'checked';
	    } else {
	        $checkbox_state = '';
	    }

	    return $checkbox_state;
	}



	public function display_custom_rsvp_form($user_id) {

	    $user_info = get_userdata($user_id);


	    $current_meta = MetaConfig::getUserMeta($user_id);

	    $current_language = pll_current_language();

	    $guests = get_field('guest_names', 'user_' . $user_id);


	    include( plugin_dir_path (__FILE__) . '../../partials/rsvp/form/user-rsvp-form.php');


	}

}