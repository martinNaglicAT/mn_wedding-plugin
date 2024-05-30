<?php
namespace MNWeddingPlugin\Rsvp;

use MNWeddingPlugin\MetaConfig;

if (!defined('ABSPATH')) {
    exit;
}

class RsvpProcessor{

	public function processRsvpForm() {
        if (isset($_POST['custom_rsvp_submit'])) {


        	//id of the user for which the form is generated
        	$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        	//id of the current user for validation purposes.
            $current_user = get_current_user_id(); 
            $user = new User();
            $user->resetCountMeta();
            $validation = $user->validate_form_submission($user_id, $current_user);
            if($validation !== false){
                $user->sanitize_and_update_user_data($user_id); 
                $count_dooku = 0;
                $guests = MetaConfig::guests($user_id);
                foreach ($guests as $guest_data) {

                    $guest_id = 'guest_'.$count_dooku;
                    $guest = new Guest($user);
                    $guest->sanitize_and_update_guest_data($user_id, $guest_id, $guest_data);

                    $plusOne = MetaConfig::getGuestMeta($user_id, $guest_id);

                    if(isset($_POST[$guest_id.'_plus_one'])){
                        $plusOne = new PlusOne($user);
                        $plusOne->sanitize_and_update_plus_one($user_id, $guest_id);
                    }

                    // Increment the count or do other processing
                    $count_dooku++;
                }

                $user->saveCountMeta($user_id);

                $home_url = home_url();

                $action = isset($_POST['mnwedding_action']) ? sanitize_text_field($_POST['mnwedding_action']) : '';
                $user_id_query = isset($_POST['mnwedding_user_id']) ? sanitize_text_field($_POST['mnwedding_user_id']) : '';

                if(current_user_can('administrator') && $action !== '' && $user_id_query !== ''){
                    $redir_slug = '/admin-single-user/?mnwedding_action=view&mnwedding_user_id='.$user_id_query;
                } else {
                    $redir_slug = pll__('/rsvp-summary/');
                }

                $redirect_url = $home_url . $redir_slug;

                wp_redirect($redirect_url);
                exit;

            }

        }
    }

}