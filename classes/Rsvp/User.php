<?php
namespace MNWeddingPlugin\Rsvp;

use MNWeddingPlugin\MetaConfig;
use MNWeddingPlugin\PluginUtils;

if (!defined('ABSPATH')) {
    exit;
}


class User{

	private $countMeta;

    public function __construct() {
        $this->resetCountMeta();
    }

    public function validate_form_submission($user_id, $current_user) {

        if (!isset($_POST['custom_rsvp_nonce'], $_POST['custom_rsvp_submit']) || !wp_verify_nonce($_POST['custom_rsvp_nonce'], 'custom_rsvp_nonce_action')) {
            return false;
        }

        $all_user_ids = get_users(array('fields' => 'ID'));

        if ((!current_user_can('administrator') && $current_user != $user_id) || !in_array($user_id, $all_user_ids)) {
            // Log permission issue or handle error
            return false;
        }

        return true;
    }

    public function sanitize_and_update_user_data($user_id) {

        $current_meta = MetaConfig::getUserMeta($user_id);

        foreach ($current_meta as $meta_key => $meta_value) {

            if($meta_key === 'user_email'){

                $user_email = sanitize_email($_POST['email']);
                if ($user_email != '' && $current_meta['user_email'] != $user_email) {
                    wp_update_user(array('ID' => $user_id, 'user_email' => $user_email));
                }    

            } elseif ($meta_key === 'phone_number'){

                $phone_number = PluginUtils::sanitize_phone_number($_POST['phone']);
                if ($phone_number != '' && $phone_number != $current_meta['phone_number']) {
                    update_user_meta($user_id, 'phone_number', $phone_number);
                }

            } elseif ($meta_key === 'updated_by'){

                    if(current_user_can('administrator')){
                        $updated_by = 'admin';
                    } else {
                        $updated_by = 'user';
                    }

                    if($updated_by != $current_meta['updated_by']){
                        update_user_meta($user_id, 'updated_by', $updated_by);
                    }

            } elseif ($meta_key === 'reviewed' || $meta_key === 'reviewed_date') {

                if($meta_key === 'reviewed'){
                    update_user_meta($user_id, 'reviewed', 'off');
                }

            } else {

                $user_value = sanitize_text_field($_POST[$meta_key]);
                if($user_value != $meta_value){
                    update_user_meta($user_id, $meta_key, $user_value);
                } 

            }


        }

    }


    public function resetCountMeta() {
        $this->countMeta = array(
            '_attending' => 0,
            '_not_attending' => 0,
            '_overnight' => 0,
            '_night_before' => 0,
            '_vegetarian' => 0,
            '_vegan' => 0,
            '_gluten' => 0,
            '_plus_one' => 0,
            '_children' => 0,
            '_babies' => 0
        );
    }

    public function updateCountMeta($key) {
        if (isset($this->countMeta[$key])) {
            $this->countMeta[$key]++;
        }
    }

    public function saveCountMeta($user_id) {
        foreach ($this->countMeta as $key => $value) {
            update_user_meta($user_id, $key, $value);
        }
    }


}