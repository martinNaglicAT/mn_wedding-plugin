<?php
namespace MNWeddingPlugin\Contact;

use MNWeddingPlugin\PluginUtils;
use MNWeddingPlugin\MetaConfig;
use MNWeddingPlugin\Services\PusherService;

if (!defined('ABSPATH')) {
    exit;
}

class ChatAjaxHandler{
	public function __construct(){

		add_action('wp_ajax_load_conversation_content_ajax', [$this, 'load_conversation_content_ajax']);
		add_action('wp_ajax_load_conversation_form_ajax', [$this, 'load_conversation_form_ajax']);
		add_action('wp_ajax_load_conversation_list_ajax', [$this, 'load_conversation_list_ajax']);
		add_action('wp_ajax_submit_new_message_ajax', [$this, 'submit_new_message_ajax']);
		add_action('wp_ajax_submit_new_conversation_ajax', [$this, 'submit_new_conversation_ajax']);
		add_action('wp_ajax_mark_message_as_read_ajax', [$this, 'mark_message_as_read_ajax']);
		add_action('wp_ajax_delete_conversation_ajax', [$this, 'handleConversationDeleteAjax']);
		add_action('wp_ajax_check_new_messages', [$this, 'check_new_messages']);

		//add_action('init', [$this, 'cleanBase']);

	}

	public function load_conversation_content_ajax() {
		//echo 'Test response'; 
		//wp_die();
		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'my_chat_nonce')) {
		    wp_send_json_error('Nonce verification failed');
		    wp_die();
		}

	    if (!isset($_POST['post_id'])) {
	        wp_send_json_error('Conversation ID not provided');
	        wp_die();
	    }

	    $messenger = new RenderMessenger();
	    $processor = new ProcessConversation();

	    $post_id = intval($_POST['post_id']);
	    $user_id = get_current_user_id();

	    $user_seen = get_post_meta($post_id, '_user_'.$user_id.'_seen', true);

	    if($user_seen === 'false'){
	    	update_post_meta($post_id, '_user_'.$user_id.'_seen', 'true');
	    	$processor->pusherMarkAsRead($post_id, $user_id);
	    }

	    $post = get_post($post_id);
	    $messenger = new RenderMessenger();

	    if ($post && $post->post_type === 'conversation') {
	        ob_start();
	        $messenger->getSingleConversation($user_id, $post);
	        $content = ob_get_clean();
	        echo $content;
	    } else {
	        echo 'No conversation found.';
	    }

	    wp_die(); // Terminate the execution to return the response
	}

	public function load_conversation_form_ajax() {
		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'my_chat_nonce')) {
		    wp_send_json_error('Nonce verification failed');
		    wp_die();
		}

	    if (!isset($_POST['chat_id'])) {
	        wp_send_json_error('Conversation ID not provided');
	        wp_die();
	    }

	    $user_id = get_current_user_id();

	    $form = new NewMessageForm();

        ob_start();
        $form->renderContactForm($user_id);
        $content = ob_get_clean();
        echo $content;


	    wp_die(); 
	}

	public function load_conversation_list_ajax() {
		//echo 'Test response'; 
		//wp_die();
		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'my_chat_nonce')) {
		    wp_send_json_error('Nonce verification failed');
		    wp_die();
		}

	    if (!isset($_POST['chat_id'])) {
	        wp_send_json_error('Conversation ID not provided');
	        wp_die();
	    }

	    $user_id = get_current_user_id();

	    $render = new RenderMessenger();

        ob_start();
        $render->renderConversationList();
        $content = ob_get_clean();
        echo $content;

	    wp_die(); 
	}

	public function submit_new_message_ajax() {
	    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'my_chat_nonce')) {
	        wp_send_json_error('Nonce verification failed');
	        wp_die();
	    }

	    if (!isset($_POST['chat_id']) || !isset($_POST['message']) || $_POST['message'] == '') {
	        wp_send_json_error('Required data not provided');
	        wp_die();
	    }

	    $post_id = intval($_POST['chat_id']);
	    $user_id = get_current_user_id();
	    $message_content = sanitize_textarea_field($_POST['message']);

	    $saveHandler = new ProcessConversation();
	    $saveHandler->saveMessageLogic($post_id, $message_content, 'ajax');

	    $conversation_meta = MetaConfig::getConversationMeta($post_id);
	    $conversation_seen = MetaConfig::conversationSeenMeta($post_id, $user_id);

	    $user_array = $conversation_meta['_user_array'];

	    $saveHandler->updateSeenMeta($user_id, $post_id, $user_array);   

	    $response_array = [];

	    $message_html = $this->generate_message_html($message_content);
	    $response_array[] = $message_html;

	    ob_start();
	    $this->generate_message_list($post_id);
	    $list_html = ob_get_clean();
	    $response_array[] = $list_html;

	    //error_log(print_r($response_array, true));

	    echo json_encode($response_array);
	    wp_die();
	}


	private function generate_message_html($message_content) {

		$username = pll__('me');
		$mine = ' my-message';
		$time = current_time('mysql');
		$message['content'] = $message_content;
    	

    	$time_format = PluginUtils::timeFormatting($time);

	    ob_start();
	    include(plugin_dir_path(dirname(__FILE__, 2)) . 'partials/contact/saved-message.php'); 
	    return ob_get_clean();
	}

	private function generate_message_list($post_id){
		$user_id = get_current_user_id();
		$admin = current_user_can('administrator');
		$title = get_the_title($post_id);

		echo '<li id="chat_'.$post_id.'" class="">';
        echo '<a href="' . home_url().pll__('/chat/') . '?chat_id='.$post_id.'" class="single-convo" data-chat-id='.$post_id.'>';

        echo '<div class="message-icon">';
        echo '<div class="icon-container">';
        echo '<div class="point"></div>';
        echo '<img src="' . plugin_dir_url(dirname(__FILE__, 2)) . 'assets/icons/message.svg" alt="Message">';
        echo '</div>';
        echo '</div>';

        echo '<div class="message-info">';

        $full = ' full';

        if($admin === true){
        	$full = '';

            echo '<div class="message-author">';
            echo 'mimiandmartin';
            echo '</div>';
    	}

        echo '<div class="subject'.$full.'">';
        echo $title;                
        echo '</div>';

        echo '</div>';

        echo '</a>';

        if($admin === true){
        	echo '<button class="delete-conversation" data-conversation-id="' . $post_id . '">Delete</button>';
        }

        echo '</li>';


	}

	public function submit_new_conversation_ajax(){

		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'my_chat_nonce')) {
	        wp_send_json_error('Nonce verification failed');
	        wp_die();
	    }

	    if ( !isset($_POST['message']) || $_POST['message'] == '' ) {
	        wp_send_json_error('Required data not provided');
	        wp_die();
	    }

	   	$subject = sanitize_text_field($_POST['subject']);
        $message = sanitize_textarea_field($_POST['message']);
        $user_id = get_current_user_id();

        $saveHandler = new ProcessConversation();

        $user_array = $saveHandler->returnUserArray();

	    $post_id = $saveHandler->conversationSaveLogic($subject, $message, $user_id, $user_array, 'ajax');	   

	    $response_array = [];
	   	$post = get_post($post_id);
	    $messenger = new RenderMessenger();

	    if ($post && $post->post_type === 'conversation') {
	        ob_start();
	        $messenger->getSingleConversation($user_id, $post);
	        $content = ob_get_clean();
	        $message_html = $content;
	    } else {
	        $message_html = 'No conversation found.';
	    }
	    $response_array[] = $message_html;

	    ob_start();
	    $this->generate_message_list($post_id);
	    $list_html = ob_get_clean();
	    $response_array[] = $list_html;

	    //error_log(print_r($response_array, true));

	    echo json_encode($response_array);
	    wp_die();

	}



	public function mark_message_as_read_ajax() {
		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'my_chat_nonce')) {
	        wp_send_json_error('Nonce verification failed');
	        wp_die();
	    }

	    if (!isset($_POST['chat_id'])) {
	        wp_send_json_error('Required data not provided');
	        wp_die();
	    }

	    $user_id = get_current_user_id();
	    $post_id = intval($_POST['chat_id']);

	    $seen_meta = MetaConfig::conversationSeenMeta($post_id, $user_id);

	    if($seen_meta['_user_'.$user_id.'_seen'] === 'false'){
	    	update_post_meta($post_id, '_user_'.$user_id.'_seen', 'true');
	    	$saveHandler = new ProcessConversation();
	    	$saveHandler->pusherMarkAsRead($post_id, $user_id);
	    	echo 'success';
	    }
	    wp_die();

	}



	public function handleConversationDeleteAjax() {
	    check_ajax_referer('my_chat_nonce', 'nonce');

	    $post_id = isset($_POST['conversation_id']) ? intval($_POST['conversation_id']) : 0;

	    if(current_user_can('delete_post', $post_id)) {
	    	$user_array = MetaConfig::getConversationMeta($post_id)['_user_array'];
	    	if(!is_array($user_array)){

	    	} else {
	    		foreach($user_array as $user_id){

		    		$message_list = MetaConfig::getUserMessagesList($user_id)['messages'];
		    		$key = array_search($post_id, $message_list);

		    		if ($key !== false) {
					    // Remove the element by key
					    unset($message_list[$key]);
					    
					    // Reindex array
					    $message_list = array_values($message_list);
					}

					update_user_meta($user_id, 'messages', $message_list);

	    		}
	    	}
	    	
	        wp_delete_post($post_id, true); 
	        echo 'success';
	    } else {
	        echo 'failure';
	    }

	    wp_die(); 		
	}


	public function check_new_messages() {
		header('Content-Type: application/json');

		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'my_chat_nonce')) {
	        wp_send_json_error('Nonce verification failed');
	        wp_die();
	    }


		$user_id = get_current_user_id();


		$message_array = MetaConfig::getUserMessagesList($user_id)['messages'];
		//error_log(print_r($message_array, true));
		//error_log(gettype($message_array));

		$unread_array = [];

		if($message_array != ''){

			$unseen = 'false';

			foreach($message_array as $message){
				$seen = MetaConfig::conversationSeenMeta($message, $user_id)['_user_'.$user_id.'_seen'];
				if($seen === 'false'){
					if($unseen !== 'true'){
						$unseen = 'true';
					}
					$last_modified_datetime = get_the_modified_date('Y-m-d H:i:s', $message);
					$unread_array[$last_modified_datetime] = $message;
				}
			}
			//error_log($user_id);
			//error_log(print_r($unread_array, true));

			update_user_meta($user_id, 'has_unseen_messages', $unseen);

			if($unseen !== 'false'){

				krsort($unread_array);


				$latest_message_id = reset($unread_array);
				//error_log(PluginUtils::dateFormatting(key($unread_array)));
				//error_log(PluginUtils::dateFormatting(current_time('mysql')));

				if( PluginUtils::dateFormatting(key($unread_array)) === PluginUtils::dateFormatting(current_time('mysql')) ){
					$latest_message_date = PluginUtils::timeFormatting(key($unread_array));
				} else {
					$latest_message_date = PluginUtils::dateFormatting(key($unread_array)).' - '.PluginUtils::timeFormatting(key($unread_array));
				}

				$replies = get_post_meta($latest_message_id, '_conversation_messages', true);

				if($replies === ''){
					$post = get_post($latest_message_id);
					$latest_content = apply_filters('the_content', $post->post_content);

					$latest_author_id = $post->post_author;
					$latest_author_info = get_userdata(intval($latest_author_id));
					$latest_username = $latest_author_info->user_login;

				} else {
					$latest_message_meta = end($replies);
					$latest_content = $latest_message_meta['content'];
					$latest_author_id = $latest_message_meta['author'];

					$latest_author_info = get_userdata(intval($latest_author_id));
					$latest_username = $latest_author_info->user_login;
				}


				
				echo json_encode([
					'status' => 'unread',
					'chat_id'=> $latest_message_id,
					'date'=> $latest_message_date,
					'content'=> $latest_content,
					'author'=> $latest_username,
					'author_id' => $latest_author_id,
				]);
			} else {
				echo json_encode([
					'status' => 'none',
				]);
			}

			wp_die();

		} else {
			echo json_encode([
				'status' => 'none',
			]);
			wp_die();
		} 		

	}

	public function cleanBase(){

		$args = array(
		    'fields' => 'ID' // Only get the user IDs
		);
		$user_ids = get_users($args);
		foreach($user_ids as $user_id){
			update_user_meta($user_id, 'messages', '');
		}



	}

}