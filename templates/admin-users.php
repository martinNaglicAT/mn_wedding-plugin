<?php
/*
Template Name: Wedding Plugin Admin Users
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


$adminUsers = new MNWeddingPlugin\Rsvp\AdminUsers(); 


?>


<h1><?php the_title(); ?></h1>



<div class="rsvp-form-container main-section">

    <div class="submitted_users users_list">
        <h2>New submissions</h2>
        <?php echo $adminUsers->getSumbittedUsersSummary(); ?>        
    </div>

    <div class="reviewed_users users_list">        
        <h2>Reviewed submissions</h2>
        <?php echo $adminUsers->getReviewedUsersSummary(); ?>
    </div>

    <div class="unsubmitted_users users_list">
        <h2>Not submitted</h2>
        <?php echo $adminUsers->getNotSubUsersSummary(); ?>        
    </div>
    
</div>







<?php get_footer(); ?>