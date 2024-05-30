<?php
/*
Template Name: Wedding Plugin RSVP
*/
?>

<?php 

if (!defined('ABSPATH')) {
    exit;
}


get_header();

$user_id = get_current_user_id();


$formRender = new MNWeddingPlugin\Rsvp\RsvpRender($user_id);

?>


<section class="main_section section-wide section-rsvp">

    <div class="cover_container container-low">
        <img src="<?php echo get_template_directory_uri().'/assets/img/images/letters.webp'; ?>" alt="cover" data-fallback="<?php echo get_template_directory_uri().'/assets/img/images/letters.jpg'; ?>">

        <div class="title">
           <h1><?php the_title(); ?></h1> 
        </div>
        <div class="shader"></div>
    </div>   

</section>


<div class="rsvp-help general-info rsvp-form-container">
	<p>
	<?php 
	echo pll__('Below you can tell us if you are attending our wedding and anything else we need to plan for. If you are having trouble with the form, you can click on the info icons for some tips or you can ');
	echo ' ';
	echo '<a href="'.home_url().pll__('/contact/').'" target="_blank"><U>'.pll__('contact us').'</u></a>'; 
	echo ' ';
	echo pll__(' directly. Please make sure to include information for every guest that is attending.');
	echo '<br><br>';
	echo pll__('We kindly recommend you check the "I\'d like to stay overnight" option and go home safely the next morning');
	echo '<br><br>';
	echo pll__('Please pay special attention to the "Food preferences" section, especially if you have specific allergies we need to know about.');
	echo '<br><br>';
	echo '<strong>'.pll__('You will be able to change the submitted information right until the deadline, so don\'t worry if you make a mistake.').'</strong>';
	?>
	</p>

</div>




<?php $formRender->display_custom_rsvp_form($user_id); ?>




<?php get_footer(); ?>