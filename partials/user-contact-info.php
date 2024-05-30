<?php
namespace MNWeddingPlugin;


if (!defined('ABSPATH')) {
    exit;
}

if(strpos($current_meta['user_email'], '@generic.com') !== false ){
    $current_meta['user_email'] = '';
}

$current_language = pll_current_language();

if($current_language === 'de'){
    $default_country_code = '+43';
} else {
    $default_country_code = '+386';
}

?>

<div class="rsvp-block">

    <div class="email-group rsvp-row">

        <div class="rsvp-column column-8">
            <label for="email"><?php echo pll_e('Email'); ?></label>
            <input type="email" name="email" value="<?php echo esc_attr($current_meta['user_email']); ?>">
        </div>

        <div class="rsvp-column column-4">
            <label for="email_guest"><?php echo pll_e('Guest'); ?></label>
            <select id="email_guest" name="email_guest">
                <?php 
                foreach( $guests as $index => $guest ) {
                    $guest_split_name = explode(' ', $guest['name'], 2);
                    $guest_name = $guest_split_name[0];
                    if($index === 0){
                        $default_guest_1 = $guest_name;
                    }
                    $index++;
                ?>
                    <option value="<?php echo $guest_name; ?>" <?php echo PluginUtils::selected_option($user_id, 'email_guest', $guest_name, $default_guest_1); ?>><?php echo $guest_name; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="email-privacy-notice">
        <div class="notice"><?php echo pll__('By submitting your email you agree that we send you email notifications when you recieve a new message from us.'); ?></div>
        <div class='link'>
            <a href="<?php echo home_url().pll__('/privacy/'); ?>" target="_blank"><?php echo pll__('more info'); ?></a>
        </div>
        
    </div>


    <div class="phone-group rsvp-row">

        <div class= "rsvp-column column-8">

            <div class="rsvp-row phone-row">

                <div class="rsvp-column column-4">

                    <label for="phone"><?php echo pll_e('Phone'); ?></label>

                    <select id="country_code" name="country_code">
                        <option value="+43" <?php echo PluginUtils::selected_option($user_id, 'country_code', '+43', $default_country_code); ?>>+43</option>
                        <option value="+386" <?php echo PluginUtils::selected_option($user_id, 'country_code', '+386', $default_country_code); ?>>+386</option>
                        <option value="+41" <?php echo PluginUtils::selected_option($user_id, 'country_code', '+41', $default_country_code); ?>>+41</option>
                        <option value="+49" <?php echo PluginUtils::selected_option($user_id, 'country_code', '+49', $default_country_code); ?>>+49</option>
                    </select>

                </div>

                <div class="rsvp-column column-8">

                    <input type="text" name="phone" value="<?php echo esc_attr($current_meta['phone_number']); ?>">

                </div>
                
            </div>
            
        </div>


        <div class="rsvp-column column-4">

            <label for="phone_guest"><?php echo pll_e('Guest'); ?></label>
            <select id="phone_guest" name="phone_guest">
                <?php 
                foreach( $guests as $index => $guest) {
                    $guest_split_name = explode(' ', $guest['name'], 2);
                    $guest_name = $guest_split_name[0];
                    if($index === 0){
                        $default_guest_2 = $guest_name;
                    }
                    $index++;
                ?>
                    <option value="<?php echo $guest_name; ?>" <?php echo PluginUtils::selected_option($user_id, 'phone_guest', $guest_name, $default_guest_2); ?>><?php echo $guest_name; ?></option>
                <?php } ?>
            </select>

        </div>

    </div>

</div>