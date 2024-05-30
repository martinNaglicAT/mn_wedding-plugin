<?php
namespace MNWeddingPlugin\Contact;

if (!defined('ABSPATH')) {
    exit;
}

class QuickMessenger{

	public function __construct(){
			add_action('wp_footer', [$this, 'messengerIcon']);
			add_action('wp_footer', [$this, 'messengerColumn']);
	}

	public function messengerIcon(){
		if(!is_page_template("messenger.php") && !is_page_template("template-splash.php") && is_user_logged_in() ){

			echo '<a href="'.home_url().pll__('/chat/').'" class="chat-icon-link hide">';
			echo '<div class="chat-icon">';
			echo '</div>';
			echo '</a>';

		}
	}

	public function messengerColumn(){
		if(!is_page_template("messenger.php") && !is_page_template("template-splash.php") && is_user_logged_in() ){

			$renderMessenger = new RenderMessenger();


			echo '<div class="messenger-column">';

			$renderMessenger->renderMessengerPage();
			echo '</div>';

		}
	}

}