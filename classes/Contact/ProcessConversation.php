<?php 
namespace MNWeddingPlugin\Contact;

use MNWeddingPlugin\PluginUtils;
use MNWeddingPlugin\MetaConfig;
use MNWeddingPlugin\Rsvp\User;
use MNWeddingPlugin\Services\PusherService;


if (!defined('ABSPATH')) {
    exit;
}

class ProcessConversation{
	public function __construct(){
		add_action('init', [$this, 'handleFormSubmission']);
	}


	public function handleFormSubmission() {

	    // Handle new conversation creation
	    if (isset($_POST['contact_form_action']) && $_POST['contact_form_action'] == 'submit_conversation') {
	        $this->mnwedding_handle_conversation_submission();
	    }
	    
	    // Handle adding new message to a conversation
	    if (isset($_POST['new_message_action']) && $_POST['new_message_action'] == 'submit_message') {
	        $post_id = $_POST['post_id']; // Ensure you have a hidden input in your form for 'post_id'
	        $this->saveConversationMessages($post_id);
	    }
	}

	private function mnwedding_handle_conversation_submission() {
	    // Check if the form is submitted
	    if (!isset($_POST['contact_form_action']) && !($_POST['contact_form_action'] == 'submit_conversation')) {
	    	return;
	    } 

	    if(!wp_verify_nonce($_POST['conversation_nonce'], 'save_conversation')){
	    	return;
	    }

	    $subject = sanitize_text_field($_POST['subject']);
        $message = sanitize_textarea_field($_POST['message']);
        $user_id = get_current_user_id();

        $user_array = $this->returnUserArray;


        $this->conversationSaveLogic($subject, $message, $user_id, $user_array, '');
	    
	}

	public function returnUserArray(){
		$user_id = get_current_user_id();
		$user_array = [];

        if(!current_user_can('administrator')){
	    	$user_array[] = strval($user_id);
	    	$user_array[] = '1';
        } else {
        	if(isset($_POST['specific'])){
        		$user_id = strval($_POST['individual']);
        		$user_array[] = strval($user_id);
        		$user_array[] = '1';
        	} else {
        		$user_array[] = '1';
        		$args = array(
				    'fields' => 'ID' // Only get the user IDs
				);
				$user_ids = get_users($args);
				$language = sanitize_text_field($_POST['language']);
				$language_array = [];
				foreach($user_ids as $first_id){
					if( get_field('frontend_language', 'user_'.$first_id) === $language){
						$language_array[] = $first_id;
					}
				}


				$key = array_search(1, $language_array);
				if($key !== false){
					 // Remove the element by key
				    unset($language_array[$key]);
				    
				    // Reindex array
				    $language_array = array_values($language_array);
				}


				$second_array = [];
				if( !isset($_POST['yes_submit']) && !isset($_POST['not_submit'])){
					$second_array = $language_array;
				} elseif (!isset($_POST['yes_submit'])){
					foreach($language_array as $second_id){
						$last_updated = MetaConfig::getUserMeta($second_id)['last_updated'];
						if($last_updated !== ''){
							$second_array[] = $second_id;
						}
					}
				} else {
					foreach($language_array as $second_id){
						$last_updated = MetaConfig::getUserMeta($second_id)['last_updated'];
						if($last_updated === ''){
							$second_array[] = $second_id;
						}
					}
				}

				if(!isset($_POST['test_user'])){
					$user_array = $second_array;
				} else {
					foreach($second_array as $final_id){
						if( the_field('test_user', 'user_'.$final_id) === true){
							$user_array[] = $final_id;
						}
					}
				}

				$user_array[] = '1';
				
        	}
        }

        //error_log(print_r($user_array, true));

        return $user_array;
	}

	public function conversationSaveLogic($subject, $message, $user_id, $user_array, $channel){
		//error_log(print_r($user_array, true));

        // Prepare post data
        $post_data = array(
            'post_title'    => wp_strip_all_tags($subject),
            'post_content'  => $message,
            'post_status'   => 'publish', 
            'post_author'   => $user_id,
            'post_type'     => 'conversation', 
        );

        // Insert the post into the database
        $post_id = wp_insert_post($post_data);

        $post_meta = MetaConfig::getConversationMeta($post_id);

        if(current_user_can('administrator')){

        	update_post_meta($post_id, '_admin', 'true');

        } else {
        	foreach ($post_meta as $meta_key => $meta_value){
	        	if($meta_key !== '_conversation_messages'){

	        		if($meta_key === '_user_email' && isset($_POST['email'])){

	        			$user_email = sanitize_email($_POST['email']);
		                if ($user_email != '') {
		                	update_post_meta($post_id, '_user_email', $user_email);
		                }    

		            } elseif ($meta_key === '_phone_number'){
		            	$phone_number = PluginUtils::sanitize_phone_number($_POST['phone']);
		                if ($phone_number != '') {
		                    update_post_meta($post_id, '_phone_number', $phone_number);
		                }

		            } elseif ($meta_key === '_user_array'){
		            	if(!empty($user_array)){
		            		update_post_meta($post_id, '_user_array', $user_array);
		            		//error_log(print_r($user_array, true));
		            	}

		            } elseif ($meta_key === '_admin'){

		            		update_post_meta($post_id, '_admin', 'false');

		            } else {
		            	$meta_pretty = substr($meta_key, 1);
		            	$user_value = sanitize_text_field($_POST[$meta_pretty]);
		                update_post_meta($post_id, $meta_key, $user_value);
		            }

	        	}
	        }
        }


       	$this->updateSeenMeta($user_id, $post_id, $user_array);


       	if(!current_user_can('administrator')){
       		if(isset( $_POST['update_contact_info'] )) {
	            $user = new User();
	            $user->sanitize_and_update_user_data($user_id);            
	        } 

       	}

  

        $pusherService = new PusherService();
		$pusher = $pusherService->getPusher();

		$author_info = get_userdata(intval($user_id));
		$username = $author_info->user_login;
		$time = PluginUtils::timeFormatting(current_time('mysql'));

		$post = get_post($post_id);

		foreach($user_array as $reciever_id){
			if($reciever_id != $user_id){
			$pusher->trigger('channel_'.$reciever_id, 'new-conversation-reciever', ['message' => $message, 'subject' => $subject, 'time' => $time, 'author' => $username, 'chat_id' => $post_id]);
			$this->send_user_notification_email($reciever_id, $user_id, $message, $user_email);	

			}
		}
   

        // Handle errors
        if (is_wp_error($post_id)) {
            echo $post_id->get_error_message();
        } else {
        	if($channel !== 'ajax'){
        		$redirect_url = get_permalink($post_id);
	            wp_redirect($redirect_url); 
	            exit;	
        	} else {
        		return $post_id;
        	}
        }
	}

	public function updateSeenMeta($user_id, $post_id, $user_array){

		foreach($user_array as $reciever_id){
	    	if(strval($user_id) !== strval($reciever_id)){
	    		update_post_meta($post_id, '_user_'.$reciever_id.'_seen', 'false');
	    	} else {
	    		update_post_meta($post_id, '_user_'.$reciever_id.'_seen', 'true');
	    		$this->pusherMarkAsRead($post_id, $reciever_id);
	    	}

	    	$updatedMessages = [];
			$savedMessages = MetaConfig::getUserMessagesList($reciever_id)['messages'];
			if($savedMessages !== false && is_array($savedMessages)){
				foreach($savedMessages as $message){
					$updatedMessages[] = $message;
				}
				
				$key = array_search($post_id, $savedMessages);
				if ($key === false) {
					$updatedMessages[] = $post_id;
				}
			} else {
				$updatedMessages[] = $post_id;
			}

	    	update_user_meta($reciever_id, 'messages', $updatedMessages);
	    }

	}

	public function pusherMarkAsRead($post_id, $user_id){
		$pusherService = new PusherService();
		$pusher = $pusherService->getPusher();

		$pusher->trigger('channel_'.$user_id, 'mark-as-read', ['chat_id' => $post_id]);		
	}

	public function saveConversationMessages($post_id) {
	    if (!isset($_POST['new_message_action']) || !wp_verify_nonce($_POST['conversation_messages_nonce'], 'save_conversation_messages')) {
	        return;
	    }

	    // Prevent auto-saving
	    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
	        return;
	    }

		$post = get_post($post_id);
	    $current_user_id = get_current_user_id();

	    // Check if the current user is the author of the post or an administrator
	    if (!($post->post_author == $current_user_id || current_user_can('administrator'))) {
	        //error_log('User does not have permissions');
	        return;
	    }

	    // Save or update messages
	    $message_content = sanitize_text_field($_POST['conversation_new_message']);

	    $this->saveMessageLogic($post_id, $message_content, '');
	}


	public function saveMessageLogic($post_id, $message_content, $channel){

	    $post = get_post($post_id);
	    $current_user_id = get_current_user_id();

	    // Check if the current user is the author of the post or an administrator
	    if (!($post->post_author == $current_user_id || current_user_can('administrator'))) {
	        //error_log('User does not have permissions');
	        return;
	    }

	    // Save or update messages
	    $newMessage = $message_content;
	    if (!empty($newMessage)) {
	    	$chat_meta = MetaConfig::getConversationMeta($post_id);

	        $messages = $chat_meta['_conversation_messages'];
	        if (!is_array($messages)) {
	            $messages = [];
	        }

	        $messages[] = ['content' => $newMessage, 'time' => current_time('mysql'), 'author' => $current_user_id ];
	        update_post_meta($post_id, '_conversation_messages', $messages);

	        $user_array = $chat_meta['_user_array'];

	        $this->updateSeenMeta($current_user_id, $post_id, $user_array);

	        // Manually update the post to refresh last_modified date
	        wp_update_post([
	            'ID'            => $post_id,
	            'post_modified' => current_time('mysql', 0),
	            'post_modified_gmt' => current_time('mysql', 1),
	        ]);


	        $pusherService = new PusherService();
			$pusher = $pusherService->getPusher();

			$author_info = get_userdata(intval($current_user_id));
			$username = $author_info->user_login;
			$time = PluginUtils::timeFormatting(current_time('mysql'));

			$user_email = get_post_meta($post_id, '_user_email', true);


			foreach($user_array as $reciever_id){
				if($reciever_id != $current_user_id){
					$pusher->trigger('channel_'.$reciever_id, 'new-message-reciever', ['message' => $newMessage, 'time' => $time, 'author' => $username, 'chat_id' => $post_id]);

					$this->send_user_notification_email($reciever_id, $current_user_id, $newMessage, $user_email);
				}
			}

			if($channel !== 'ajax'){
				$redirect_url = get_permalink($post_id);
	            wp_redirect($redirect_url); 
	            exit;
			}

		}
	}


	private function send_user_notification_email($reciever_id, $sender_id, $content, $user_email) {
	    $user_info = get_userdata($reciever_id);
	    $sender_info = get_userdata($sender_id);
	    if(current_user_can('administrator') && $user_email !== $user_info->user_email){
	    	$to = $user_email;
	    } else {
	    	$to = $user_info->user_email;
	    }

	    if(strpos($to, '@generic.com')  === false){

	    	$language = get_field('frontend_language', 'user_' . $reciever_id);
	    	$sender_info = get_userdata($sender_id);
	    	$sender_name = $sender_info->user_login;

	    	if($language === 'de'){
	    		$subject = 'Neue Nachricht auf mimiandmartin.com erhalten';
		    	$message = "Sie haben eine neue Nachricht von {$sender_name} auf mimiandmartin.com erhalten:<br><br>{$content}<br><br>Bitte besuchen Sie mimiandmartin.com und melden Sie sich an, um die Nachricht anzuzeigen und darauf zu antworten.";
	    	} elseif ($language === 'si'){
	    		$subject = 'Novo sporočilo na mimiandmartin.com';
		    	$message = "Oseba {$sender_name} ti je poslala novo sporočilo na mimiandmartin.com:<br><br>{$content}<br><br>Za ogled in odgovor na sporočilo obišči in se prijavi na mimiandmartin.com.";

	    	} else {
	    		$subject = 'New message recieved on mimiandmartin.com';
		    	$message = "You have recieved a new message from {$sender_name} on mimiandmartin.com:<br><br>{$content}<br><br>Please visit mimiandmartin.com and log in to view and reply to the message.";
	    	}

		    $headers = array('Content-Type: text/html; charset=UTF-8');
		    wp_mail($to, $subject, $message, $headers);

	    }
	    
	}


}