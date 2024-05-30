<?php
namespace MNWeddingPlugin\Contact;

if (!defined('ABSPATH')) {
    exit;
}

class ConversationPostType{
    public function __construct() {
        add_action('init', [$this, 'registerPostType']);
        add_action('add_meta_boxes', [$this, 'addConversationMetaboxes']);
        add_filter('template_redirect', [$this, 'conversation_template']);
    }


    public function conversation_template($template) {
	    if (is_singular('conversation')) {
            global $post; 
            $post_id = $post->ID; 
            $redirect_url = home_url(pll__('/chat/').'?chat_id=' . $post_id); 
            wp_redirect($redirect_url);
            exit;
        }
	}




    public function registerPostType() {
        $args = [
            'public' => false, 
            'publicly_queryable' => true,
            'label'  => __('Conversations', 'mn-wedding-plugin'),
            'show_ui' => false, 
            'show_in_menu' => false, 
            'supports' => ['title', 'editor', 'author'], 
        ];
        register_post_type('conversation', $args);

    }

    public function addConversationMetaboxes() {
	    add_meta_box(
	        'conversation_messages',
	        __('Conversation Messages', 'mn-wedding-plugin'),
	        [new \MNWeddingPlugin\Contact\RenderMessenger(), 'renderMessagesMetabox'],
	        'conversation',
	        'normal',
	        'default'
	    );
	}

	


}