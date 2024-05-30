<?php 
/*
Plugin Name: MN Wedding Plugin
Description: Custom RSVP functionality, Contact Messenger System and Admin section
Version: 1.03
Author: Martin Naglič
*/

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';


new MNWeddingPlugin\PluginInit();

new MNWeddingPlugin\Contact\ConversationPostType();

new MNWeddingPlugin\Contact\ProcessConversation();

new MNWeddingPlugin\Contact\ChatAjaxHandler();

new MNWeddingPlugin\Contact\QuickMessenger();

