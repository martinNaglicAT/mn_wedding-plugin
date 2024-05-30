<?php 
namespace MNWeddingPlugin;

use MNWeddingPlugin\Rsvp\RsvpProcessor;
use MNWeddingPlugin\Rsvp\RsvpUtils;

if (!defined('ABSPATH')) {
    exit;
}

class PluginInit{

    private $plugin_version = '1.13';


    public function __construct(){


        $this->initHooks();


        add_action('wp_enqueue_scripts', array($this, 'enqueueAssets'));  


        add_action('template_redirect', [$this, 'redirectNewChat']);

        add_filter('query_vars', ['MNWeddingPlugin\PluginUtils', 'register_query_vars']);

        add_filter('theme_page_templates', array($this, 'mnwedding_add_custom_page_templates'));

        add_filter('template_include', array($this, 'mnwedding_change_page_template'), 99);

        RsvpUtils::init();

    }

    private function initHooks() {
        // Initialize RsvpProcessor for form submissions
        $rsvpProcessor = new RsvpProcessor();
        add_action('init', array($rsvpProcessor, 'processRsvpForm'));


    }

    private function customTemplatesArray(){
        return array(
            'admin-single-user.php' => __('Wedding Plugin Admin Single User', 'text-domain'),
            'admin-front.php' => __('Wedding Plugin Admin Splash', 'text-domain'),
            'admin-users.php' => __('Wedding Plugin Admin Users', 'text-domain'),
            'rsvp.php' => __('Wedding Plugin RSVP', 'text-domain'),
            'rsvp-summary.php' => __('Wedding Plugin RSVP Summary', 'text-domain'),
            'contact.php' => __('Wedding Plugin Contact', 'text-domain'),
            'messenger.php' => __('Wedding Plugin Messenger', 'text-domain'),
        );
    }

    public function mnwedding_add_custom_page_templates($templates) {
        $custom_templates = $this->customTemplatesArray();
        foreach($custom_templates as $meta_key => $meta_value){
           $templates[$meta_key] = $meta_value; 
        }
        return $templates;
    }

    public function mnwedding_change_page_template($template) {
        $post = get_post();
        $page_template_slug = get_post_meta($post->ID, '_wp_page_template', true);
        $custom_templates = $this->customTemplatesArray();

        foreach($custom_templates as $meta_key => $meta_value){
            if ($meta_key === $page_template_slug) {
                $template = plugin_dir_path(__FILE__) . '../templates/'.$meta_key;
            }
        }

        return $template;
    }


    public function redirectNewChat() {
        // Check if we are on the specific chat page without the 'chat_id' query parameter
        if (is_page_template('messenger.php') && !isset($_GET['chat_id'])) {
            // Build the URL to redirect to by appending the 'chat_id=new' query parameter
            $redirect_url = home_url() . pll__('/chat/') . '?chat_id=new';
            
            // Execute the redirect
            wp_redirect($redirect_url);
            exit;
        }
    }



    public function enqueueAssets() {
        if(!is_page_template('template-splash.php') && is_user_logged_in()){

            wp_enqueue_script('pusher', 'https://js.pusher.com/7.0/pusher.min.js', [], $this->plugin_version, true);

            wp_enqueue_script('mn_wedding_plugin_script', plugin_dir_url(__FILE__) . '../assets/js/scripts.js', array('jquery'), $this->plugin_version, true);

            wp_enqueue_script('contact', plugin_dir_url(__FILE__) . '../assets/js/contact.js', array('jquery'), $this->plugin_version, true);

            wp_enqueue_style('mn_wedding_plugin_style', plugin_dir_url(__FILE__) . '../assets/css/style.css', null, $this->plugin_version, 'all');

            wp_localize_script('mn_wedding_plugin_script', 'mnWeddingPluginAjax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('mn_wedding_plugin_nonce')
            ));

            wp_localize_script('contact', 'myChatAjax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('my_chat_nonce'),
                'home_url' => home_url(),
                'user_id' => get_current_user_id(),
            ));

        } else {
            wp_enqueue_style('mn_wedding_plugin_style', plugin_dir_url(__FILE__) . '../assets/css/style.css', null, $this->plugin_version, 'all');
        }
    }



}