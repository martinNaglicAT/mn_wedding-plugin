<?php

if (!defined('ABSPATH')) {
    exit;
}
?>

<?php 
    //functionality test
    /*$meta = \MNWeddingPlugin\MetaConfig::getConversationMeta($post->ID);
    foreach($meta as $meta_key => $meta_value){
        if($meta_key !== '_conversation_messages'){
            echo $meta_value;
        }
    } */
?>

<div id="conversation_<?php echo $post->ID; ?>" class="conversation" data-chat-id="<?php echo $post->ID; ?>">


    <div class="title-row" id="title-row">

        <a href="<?php echo home_url().pll__('/chat/'); ?>?chat_id=list" class="back-to-list<?php echo $this->hasUnseenMessages($user_id); ?>">
            <div class="point"></div>
            <div class="back-to-list-inner">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <polygon points="15.293 3.293 6.586 12 15.293 20.707 16.707 19.293 9.414 12 16.707 4.707 15.293 3.293"/>
                </svg>
            </div>
        </a>

        <h2><?php echo esc_html($title); ?></h2>

        <a href="<?php echo home_url().pll__('/chat/').'?chat_id='.$post->ID; ?>" class="expand-message">
            <div class="expand-message-inner">
                <svg fill="none" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M13.8995 2.10052V4.10052H18.4852L12.707 9.87878L14.1212 11.293L19.8995 5.51471V10.1005H21.8995V2.10052H13.8995Z" fill="currentColor"/><path d="M5.51468 19.8995H10.1005V21.8995H2.10046V13.8995H4.10046V18.4853L9.87872 12.707L11.2929 14.1213L5.51468 19.8995Z" fill="currentColor"/><path d="M9.17154 7.75739L7.75732 9.1716L14.8284 16.2427L16.2426 14.8285L9.17154 7.75739Z" fill="currentColor"/></svg>
            </div>
        </a>

    </div>

    <div class="conversation-messages">

        <?php if(current_user_can('administrator')): ?>

        <div class="contact">
            <?php 
                echo $email; 
                echo '<br>';
                echo $phone;
            ?>
            
        </div>

        <?php endif; ?>

        <?php $this->daysSeparator($time); ?>

        <div class="message message-orig<?php echo $mine; ?>">

            <span class="author"><?php echo esc_html($username); ?></span>

            <?php echo $post_content; ?>

            <div class="datetime"><?php echo esc_attr($this->chatTimeFormat($post)); ?></div>

        </div>

        <?php $this->renderMessagesMetabox($post); ?>


    </div>

    <div class="input-row">
        <?php if($admin !== 'true' || current_user_can('administrator')): ?>
        <form action="" method="post" id="contact-message" data-chat-id=<?php echo intval($post->ID); ?>>

            <input type="hidden" name="new_message_action" value="submit_message">

            <input type="hidden" name="post_id" value="<?php echo esc_attr($post->ID); ?>">

            <?php 
                //new message input
                include( plugin_dir_path (__FILE__) . '../../partials/contact/new-message.php');


                // Nonce field for security
                wp_nonce_field('save_conversation_messages', 'conversation_messages_nonce');
            ?>

        </form>
        <?php else: ?>
            <?php echo pll__('You can not reply to this message.'); ?>
        <?php endif; ?>
    </div>

</div>