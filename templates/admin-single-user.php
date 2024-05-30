<?php
/*
Template Name: Wedding Plugin Admin Single User
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


$user_id = get_query_var('mnwedding_user_id');
$action = get_query_var('mnwedding_action');
$user_info = get_userdata($user_id);
$username = $user_info->user_login;

?>


<h1><?php echo get_the_title().' - '.$username; ?></h1>


<?php \MNWeddingPlugin\Rsvp\RsvpUtils::mnwedding_rsvp_admin_page_content(); ?>


<?php get_footer(); ?>