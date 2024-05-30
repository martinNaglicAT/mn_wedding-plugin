<?php
namespace MNWeddingPlugin\Contact;

use MNWeddingPlugin\MetaConfig;

if (!defined('ABSPATH')) {
    exit;
}

class NewMessageForm{


	public function renderContactForm($user_id){

		$current_meta = MetaConfig::getUserMeta($user_id);

		$existing_contact = false;
		$user_email = $current_meta['user_email'];
		$user_phone = $current_meta['phone_number'];

		$guests = MetaConfig::guests($user_id);

		if( strpos($user_email, '@generic.com') === false  || !empty($user_phone) ){
			$existing_contact = true;
		}

		$renderMessenger = new RenderMessenger();

		if(!is_page_template('contact.php')){
			$form_id = 'contact-form';
		} else {
			$form_id = 'contact-form-static';
		}


		include(plugin_dir_path(__FILE__) . '../../partials/contact/new-conversation.php');

	}
}