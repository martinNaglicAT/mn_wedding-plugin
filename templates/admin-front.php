<?php
/*
Template Name: Wedding Plugin Admin Splash
*/
?>


<?php 

if (!defined('ABSPATH')) {
    exit;
}

if(!current_user_can('administrator')){
    wp_redirect(home_url());
    exit;
}


\MNWeddingPlugin\Rsvp\RsvpUtils::getAdminHeader();


$adminOverview = new \MNWeddingPlugin\AdminOverview();


?>


<h1><?php the_title(); ?></h1>


<div class="rsvp-form-container main-section">
    <div class="users_list">

        <div class="total-user-summary">
            <h2>All users summary</h2>
            

            <div class="rsvp-user-summary">
                <div class="guest-numbers-section">
                    <?php $adminOverview->getTotalNumbersOverview(); ?>
                </div>
                
            </div>
        </div>

        <div class="user-comments-summary">
            <h2>Text comments summary</h2>

            <div class="rsvp-user-summary">

                <?php $adminOverview->renderGuestIssues(); ?>
                
            </div>
        </div>

    </div>

</div>








<?php get_footer(); ?>