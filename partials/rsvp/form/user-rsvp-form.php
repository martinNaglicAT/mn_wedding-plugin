<?php


if (!defined('ABSPATH')) {
    exit;
}

$action = isset($_GET['mnwedding_action']) ? esc_attr($_GET['mnwedding_action']) : '';
$user_id_query = isset($_GET['mnwedding_user_id']) ? esc_attr($_GET['mnwedding_user_id']) : '';


?>


<!--HTML FORM-->
<div class="rsvp_user_form main-section">

    <div class="rsvp-form-container">

        <form action="" method="post" id="rsvp_form_id">

            <input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr($user_id); ?>">

            <div class="last_updated_callback">
 
                <?php echo MNWeddingPlugin\Rsvp\RsvpUtils::last_updated($user_id, $current_language); ?>  
          
            </div>

            <input type="hidden" name="mnwedding_action" value="<?php echo esc_attr($action); ?>">
            <input type="hidden" name="mnwedding_user_id" value="<?php echo esc_attr($user_id_query); ?>">

            <?php if(current_user_can('administrator')): ?>

                <div class="last_updated_callback">
                    <?php echo MNWeddingPlugin\Rsvp\RsvpUtils::last_reviewed($user_id); ?>
                </div>

            <?php endif; ?>

            <div class="contact-info">

                <div class="help" id="help-contact">

                    <div class="rsvp-title-row">
                        <h2><?php echo pll_e('Contact info'); ?></h2>

                        <div class="rsvp-help help-icon">
                            <a href="#" class="open-help">
                                <div class="help-icon-circle"><div>i</div></div>
                            </a>
                            <a href="#" class="close-help">
                                <div class="close-help-inner"></div>
                            </a>
                        </div>
                    </div>

                    <div class="rsvp-help">
                        <div class="help-text">
                            <p>
                                <?php 
                                echo pll_e('We will only use this information to keep you updated, and maybe send you a reminder to double-check your RSVP before the deadline. If you would rather not share your contact info with us, feel free to leave these fields empty.'); 
                                echo '<br><br>';
                                echo pll_e('In the email section, type in your email in the first input. In the second input, select the guest that the email belongs to.');
                                echo '<br><br>';
                                echo pll_e('In the phone section, please select your country code, type in your phone number, and select the guest that the phone number belongs to..');
                                ?>
                            </p>
                        </div>
                    </div>
                    
                </div>

                <?php include( dirname(plugin_dir_path(__FILE__), 2) . '/user-contact-info.php' ); ?>

            </div>

            <div class="guest_info">

                <div class="help" id="help-guests">

                    <div class="rsvp-title-row">

                        <h2><?php echo pll_e('Guests'); ?></h2>
                        <div class="rsvp-help help-icon">
                            <a href="#" class="open-help">
                                <div class="help-icon-circle"><div>i</div></div>
                            </a>
                            <a href="#" class="close-help">
                                <div class="close-help-inner"></div>
                            </a>
                        </div>  

                    </div>

                    

                    <div class="rsvp-help">
                        
                        <div class="help-text">
                            <p>
                                <?php echo pll_e('Below you can enter RSVP information for each of the invited guests. Please check "Attending" for each guest that is coming to our wedding and a form with further inputs will appear just below.'); ?>
                            </p>

                        </div>
                    </div>

                </div>

                <?php 

                $guest_count = 0;

                foreach($guests as $guest){

                    include( dirname(plugin_dir_path(__FILE__), 2) . '/rsvp/form/guest-input-form.php' );

                    $guest_count++;
                }

                ?>
                
            </div>

            <input type="hidden" name="last_updated" id="last_updated" value="">

            <input type="hidden" name="updated_by" id="updated_by" value="<?php echo esc_attr(get_current_user_id()); ?>">




            <?php wp_nonce_field('custom_rsvp_nonce_action', 'custom_rsvp_nonce'); ?>

            <div class="horizontal-divider-end-form"></div>

            <div class="submit-container">

                <input type="submit" name="custom_rsvp_submit" id="custom_rsvp_submit" value="<?php echo pll__('Submit RSVP'); ?>">
                
            </div>

            <div class="error-m-container">

                <div class="popup-icon">
                    <a href="#" class="close-popup">
                        <div class="close-popup-inner"></div>
                    </a>
                </div>

                <p>
                    <?php echo pll_e('Please write the names of your +1 guests before submitting the form.'); ?>
                </p>

                <div class="errors-list">
                    
                </div>
            </div>

        </form>


        <?php if(current_user_can('administrator')): ?>

            <div class="review-container">
                <label class="custom-checkbox">
                    Mark as Reviewed
                    <input type="checkbox" class="mark-as-reviewed" data-user-id="<?php echo $user_id; ?>" value='on' hidden <?php echo ($current_meta['reviewed'] === 'on' ? 'checked' : ''); ?>/>
                    <div class="checkmark">
                        <div class="checkmark-slider">
                            <div class="inner"></div>
                        </div>
                    </div>              
                </label>
            </div>

        <?php endif; ?>

    </div>

</div>







