<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="contact-form-container">

    <div class="title-row">

        <a href="<?php echo home_url().pll__('/chat/'); ?>?chat_id=list" class="back-to-list<?php echo $renderMessenger->hasUnseenMessages($user_id); ?>">
            <div class="point"></div>
            <div class="back-to-list-inner">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <polygon points="15.293 3.293 6.586 12 15.293 20.707 16.707 19.293 9.414 12 16.707 4.707 15.293 3.293"/>
                </svg>
            </div>
        </a>
        <h3><?php echo pll__('New message'); ?></h3>
        
    </div>

    <div class="conversation-form">

        <form action="" method="post" id="<?php echo $form_id; ?>">

            <input type="hidden" name="contact_form_action" value="submit_conversation">

            <?php if(current_user_can('administrator')): ?>

                <?php include( plugin_dir_path(__FILE__) . '../../partials/contact/filters.php' ); ?>

            <?php else: ?>
                <div class="contact-info contact-data-user">

                    <h3><?php echo pll__('How can we reach you?'); ?></h3>

                    <?php include( plugin_dir_path(__FILE__) . '../../partials/user-contact-info.php' ); ?>

                    <label for="update_contact_info" class="custom-checkbox update-user-info">
                        <?php echo pll__('Update my user account with above information'); ?>
                        <input type="checkbox" class="update_contact_info" id="update_contact_info" name="update_contact_info" value="on" hidden/>
                        <div class="checkmark">
                            <div class="checkmark-slider">
                                <div class="inner"></div>
                            </div>
                        </div>              
                    </label>


                </div>
            <?php endif; ?>

            <input type="hidden" name="last_updated" id="last_updated" value="">



            <div class="main_message">
                <div class="init_subject">
                    <label for="subject"><?php echo pll__('Subject'); ?></label>
                    <input type="text" name="subject" id="subject"> 
                </div>

                <div class="init_message">
                    <label for="message"><?php echo pll__('Message'); ?></label>
                    <!--<textarea name="message" id="message" rows="5" cols="50"></textarea>-->

                    <div class="new-message-inputs">

                        <div class="textarea-container">
                            <textarea name="message" id="autoresizing" rows="5"></textarea>
                        </div>

                        <?php wp_nonce_field('save_conversation', 'conversation_nonce'); ?>

                        <div class="submit-container">
                            <button type="submit" name="contact_form_submit" id="contact_form_submit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                  <polygon points="7.293 4.707 14.586 12 7.293 19.293 8.707 20.707 17.414 12 8.707 3.293 7.293 4.707"/>
                                </svg>
                            </button>
                        </div>

                    </div>

                </div>
                
            </div>

            <?php //wp_nonce_field('save_conversation', 'conversation_nonce'); ?>

            <!--<div class="submit-container">

                <input type="submit" name="contact_form_submit" id="contact_form_submit" value="<?php //echo pll__('Send'); ?>">
                
            </div>-->

        </form>

    </div>


</div>