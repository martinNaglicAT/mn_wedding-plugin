<?php 
namespace MNWeddingPlugin\Rsvp;

use MNWeddingPlugin\MetaConfig;

if (!defined('ABSPATH')) {
    exit;
}

class Guest{

	private $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

	public function sanitize_and_update_guest_data($user_id, $guest_id, $guest_data) {

        $current_meta = MetaConfig::getGuestMeta($user_id, $guest_id);  
        $night_before = MetaConfig::hasNightBefore($user_id);      
        $check_values = RsvpUtils::checkValues();
        $text_values = RsvpUtils::textValues();
        $count_meta = RsvpUtils::countMetaKeys();


        foreach ($current_meta as $meta_key => $meta_value){
            $stripped_key = str_replace($guest_id, '', $meta_key);
            if(in_array($stripped_key, $check_values)){
                //save checkbox-based values

                if($night_before && $stripped_key === '_night_before'){
                    //save _night_before
                    if(isset( $_POST[$guest_id.'_night_before'] )) {
                        $guest_night_before = sanitize_text_field($_POST[$guest_id.'_night_before']);                        
                            $this->user->updateCountMeta('_night_before');  
                    }  else {
                        $guest_night_before = 'off';
                    }
                    if( $guest_night_before != $current_meta[$guest_id.'_night_before'] ){
                        update_user_meta($user_id, $guest_id.'_night_before', $guest_night_before);
                    }

                } elseif ($stripped_key !== '_plus_one') {
                    //save other checkbox values, except _plus_one - that's in plus one class
                    if(isset( $_POST[$meta_key] )) {
                        $guest_value = sanitize_text_field($_POST[$meta_key]);
                        $value_pretty = str_replace($guest_id, '', $meta_key);
                        if( in_array($value_pretty, $count_meta) ){
                            if( isset($_POST[$guest_id.'_attending']) ){
                                $this->user->updateCountMeta($value_pretty);
                            }
                        }
                    }  else {
                        $guest_value = 'off';
                    } 

                    if($guest_value != $meta_value){
                        update_user_meta($user_id, $meta_key, $guest_value);
                    }

                }

            } elseif (in_array($stripped_key, $text_values)){
                //save text-based values
                $guest_value = sanitize_text_field($_POST[$meta_key]); 
                if($guest_value != $meta_value){
                    update_user_meta($user_id, $meta_key, $guest_value);
                }

            }
        }

        if( !isset($_POST[$guest_id.'_attending']) ){
            $this->user->updateCountMeta('_not_attending');
        }

        $is_child = $guest_data['child'];
        $is_baby = $guest_data['baby'];

        if( isset($_POST[$guest_id.'_attending']) ){
            if( $is_baby ){
                $this->user->updateCountMeta('_babies');
            }
            if( $is_child ){
                $this->user->updateCountMeta('_children');
            }
        }


    }

}


