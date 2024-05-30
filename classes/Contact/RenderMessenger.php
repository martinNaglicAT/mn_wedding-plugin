<?php
namespace MNWeddingPlugin\Contact;

use WP_Query;
use MNWeddingPlugin\PluginUtils;
use MNWeddingPlugin\MetaConfig;

if (!defined('ABSPATH')) {
    exit;
}

class RenderMessenger{

	private $datesArray = [];


	public function renderMessengerPage(){

		$contact_form = new NewMessageForm();

		$user_id = get_current_user_id();
		$chat_id = get_query_var('chat_id');
		$post = get_post($chat_id);

		$formClass = '';
		if($chat_id === 'new'){
			$formClass = ' new-form';
		}


		echo '<div class="messenger-list'.$this->getMessageListStatus($chat_id).'">';
		$this->renderConversationList();
		echo '</div>';

		echo '<div class="messenger-main'.$this->getSingleMessageStatus($chat_id).$formClass.'">'; 


		if ($chat_id !== '' && $chat_id !== 'new' && $chat_id !== 'list') {

			$post = get_post($chat_id);
            if ($post && $post->post_type === 'conversation') {
                $this->getSingleConversation($user_id, $post);
            } else {
            	echo '<a href="'.home_url().pll__('/chat/').'?chat_id=list" class="back-to-list'.$this->hasUnseenMessages($user_id).'">';
            	echo '<div class="point"></div>';
			    echo '<div class="back-to-list-inner">';
			    echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">';
                echo '<polygon points="15.293 3.293 6.586 12 15.293 20.707 16.707 19.293 9.414 12 16.707 4.707 15.293 3.293"/>';
                echo '</svg>';
			    echo '</div>';
			    echo '</a>';
			    
                echo pll__('No conversations found.');
            }

        } else {

			echo $contact_form->renderContactForm($user_id);

        }

		echo '</div>';

	}

	private function getMessageListStatus($chat_id){
		if($chat_id !== '' && $chat_id !== 'list'){
			$class = ' list-hidden';
		} else {
			$class = '';
		}
		return $class;
	}

	private function getSingleMessageStatus($chat_id){
		if($chat_id !== '' && $chat_id !== 'list'){
			$class = '';
		} else {
			$class = ' chat-hidden';
		}
		return $class;
	}

	public function getSingleConversation($user_id, $post){

		$post_id = $post->ID;
		$post_meta = MetaConfig::getConversationMeta($post_id);
		$user_array = $post_meta['_user_array'];
		$author_id = $post->post_author;
	    $author_info = get_userdata(intval($author_id));

	    $seen_meta = MetaConfig::conversationSeenMeta($post_id, $user_id)['_user_'.$user_id.'_seen'];

	    if($seen_meta !== 'true'){
	    	update_post_meta($post_id, '_user_'.$user_id.'_seen', 'true');
	    }

	    $messages_list = MetaConfig::getUserMessagesList($user_id)['messages'];


	    if ($author_info) { 

	    	if(intval($author_id) !== get_current_user_id()){
	    		$username = $author_info->user_login;
	    		$mine = '';
	    	} else {
	    		$username = pll__('me');
	    		$mine = ' my-message';
	    	}

	    	$post_content = apply_filters('the_content', $post->post_content);
			$time = get_post_time('d.m.Y', false, $post_id, true);

			$title = get_the_title($post);
                if ($title === ''){
                	$title = pll__('No subject');
            }

            $admin = $post_meta['_admin'];

            $email_add = $post_meta['_user_email'];
            $email_guest = $post_meta['_email_guest'];
            $email = $email_add.' - '.$email_guest;

            $country_code = $post_meta['_country_code'];
            $phone_num = $post_meta['_phone_number'];
            $phone_guest = $post_meta['_phone_guest'];
            $phone = $country_code.' '.$phone_num.' - '.$phone_guest;

			include( plugin_dir_path (__FILE__) . '../../partials/contact/single-conversation.php');


	    } else {
	        echo 'Error: Author information not found.';
	    }

	}


	public function renderMessagesMetabox($post) {
	    // Retrieve existing messages
	    $meta = MetaConfig::getConversationMeta($post->ID);
	    $messages = $meta['_conversation_messages'];
	    if (!is_array($messages)) {
	        $messages = [];
	    }

	    echo '<div class="replies">';

	    // Output existing messages
	    foreach ($messages as $message) {

	    	$time = $message['time'];
	    	error_log($time);
	    	$this->daysSeparator($time);

	    	$author_meta = $message['author'];
	    	$author_info = get_userdata(intval($author_meta));
	    	if($author_info){

	    		if(intval($author_meta) !== get_current_user_id()){
		    		$username = $author_info->user_login;
		    		$mine = '';
		    	} else {
		    		$username = pll__('me');
		    		$mine = ' my-message';
		    	}

		    	$time_format = PluginUtils::timeFormatting($time);

		        include( plugin_dir_path (__FILE__) . '../../partials/contact/saved-message.php');

	    	} else {
	    		echo 'Author information not found.';
	    	}
	    }

	    echo '</div>';

	    
	}

	private function daysSeparator($time){
		$messageDate = PluginUtils::dateFormatting($time);
		$currentDate = PluginUtils::dateFormatting(current_time('mysql'));

		$separator = '';

		if($messageDate === $currentDate && (!in_array($messageDate, $this->datesArray))){
			$separator = '<div class="day">'.pll__('Today').'</div>';
			$this->datesArray[] = $messageDate;
		} else if (!in_array($messageDate, $this->datesArray)){
			$separator = '<div class="day">'.$messageDate.'</div>';
			$this->datesArray[] = $messageDate;
		}

		return $separator;
	}



	private function chatTimeFormat($post){
		$post_id = $post->ID;
		$time = get_post_time('H:i', false, $post_id, true);
		return $time;
	}


	public function renderConversationList(){
		global $post;
		$current_user_id = get_current_user_id();

		if(current_user_can('administrator')){
			$admin = true;
			$args = [
				'post_type' => 'conversation',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'orderby' => 'modified',
				'order' => 'DESC',
			];
		} else {
			$admin = false;
			$message_array = MetaConfig::getUserMessagesList($current_user_id)['messages'];
			if (!is_array($message_array) || empty($message_array)) {
		        $message_array = [0]; // No post has the ID 0
		    }
			$args = [
				'post_type' => 'conversation',
				'post__in' => $message_array,
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'orderby' => 'modified',
				'order' => 'DESC',
			];
		}

		$query = new WP_Query($args);

		//new-conversation-mobile

	    echo '<ul class="conversation-list">';

	    //if(!is_page_template('contact.php')){

	    	echo '<li id="new-m">';
	        echo '<a href="'.home_url().pll__('/chat/').'?chat_id=new" class="new-convo">';

	        echo '<div class="message-icon">';
	        echo '<div class="icon-container">';
	        echo '<img src="' . plugin_dir_url(dirname(__FILE__, 2)) . 'assets/icons/plus.svg" alt="Message">';
	        echo '</div>';
	        echo '</div>';

	        echo '<div class="message-info">';
	        echo '<div class="subject full">';
	        echo pll__('New message');               
	        echo '</div>';

	        echo '</div>';

	        echo '</a>';
	        echo '</li>';

	    //}


		if ($query->have_posts()) {

            while ($query->have_posts()) {
                $query->the_post();
                $post_id = $post->ID;
                $title = get_the_title($post);
                if ($title === ''){
                	$title = pll__('No subject');
                }

                $message_seen = MetaConfig::conversationSeenMeta($post_id, $current_user_id)['_user_'.$current_user_id.'_seen'];
                //error_log($message_seen);
                //$current_user_index = strval($current_user_id);
                

                $class_seen = '';
                if($message_seen !== 'true'){
                	$class_seen = ' unread';
                }

                echo '<li id="chat_'.$post_id.'" class="'.$class_seen.'">';
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
	                the_author();
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
        } else {
            echo pll__('No conversations found.');
        }

        echo '</ul>';

        wp_reset_postdata();


	}


	public function hasUnseenMessages($user_id){
		$unseen = get_user_meta($user_id, 'has_unseen_messages', true);
		//error_log(print_r($unseen, true));
		//error_log(gettype($unseen));
		if($unseen !== 'false'){
			return ' unread';
		} else {
			return '';
		}
	}

}