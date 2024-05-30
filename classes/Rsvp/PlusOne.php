<?php 
namespace MNWeddingPlugin\Rsvp;

use MNWeddingPlugin\MetaConfig;

if (!defined('ABSPATH')) {
    exit;
}



class PlusOne{

    private $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function sanitize_and_update_plus_one($user_id, $guest_id){

        if(isset( $_POST[$guest_id.'_plus_one'] )) {
            $guest_plus_one = sanitize_text_field($_POST[$guest_id.'_plus_one']);
            $this->user->updateCountMeta('_plus_one');
            $this->user->updateCountMeta('_attending');
        } else {
            $guest_plus_one = 'off';
        }

        $plus_one_array = MetaConfig::getGuestMeta($user_id, $guest_id);
        $plus_one_value = $plus_one_array[$guest_id.'_plus_one'];
        if( $guest_plus_one != $plus_one_value ){
            update_user_meta($user_id, $guest_id.'_plus_one', $guest_plus_one);
        }


        $plus_one_meta = MetaConfig::plusOneMeta($user_id, $guest_id);
        $night_before = MetaConfig::hasNightBefore($user_id);
        $check_values = RsvpUtils::checkValues();
        $text_values = RsvpUtils::textValues();
        $count_meta = RsvpUtils::countMetaKeys();

        foreach ($plus_one_meta as $meta_key => $meta_value){
            $stripped_key_aux = str_replace($guest_id, '', $meta_key);
            $stripped_key = str_replace('_plus', '', $stripped_key_aux);
            if(in_array($stripped_key, $check_values)){
                //save checkbox-based values

                if($night_before && $stripped_key === '_night_before'){
                    //save _night_before
                    if(isset( $_POST[$guest_id.'_night_before_plus'] )) {
                        $guest_night_before = sanitize_text_field($_POST[$guest_id.'_night_before_plus']);
                        $this->user->updateCountMeta('_night_before');  
                    }  else {
                        $guest_night_before = 'off';
                    }
                    if( $guest_night_before != $plus_one_meta[$guest_id.'_night_before_plus'] ){
                        update_user_meta($user_id, $guest_id.'_night_before_plus', $guest_night_before);
                    }

                } else {
                    //save other checkbox values
                    if(isset( $_POST[$meta_key] )) {
                        $plus_one_value = sanitize_text_field($_POST[$meta_key]);
                        $value_pretty1 = str_replace($guest_id, '', $meta_key);
                        $value_pretty = str_replace('_plus', '', $value_pretty1);
                        if( in_array($value_pretty, $count_meta) ){
                            $this->user->updateCountMeta($value_pretty);
                        }
                    }  else {
                        $plus_one_value = 'off';
                    } 

                    if($plus_one_value != $meta_value){
                        update_user_meta($user_id, $meta_key, $plus_one_value);
                    }

                }

            } elseif (in_array($stripped_key, $text_values)){
                //save text-based values
                $plus_one_value = sanitize_text_field($_POST[$meta_key]); 

                if($plus_one_value != $meta_value){
                    update_user_meta($user_id, $meta_key, $plus_one_value);
                }

            }
        }


    }


}