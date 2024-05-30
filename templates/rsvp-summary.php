<?php
/*
Template Name: Wedding Plugin RSVP Summary
*/
?>


<?php 

if (!defined('ABSPATH')) {
    exit;
}


get_header();

$user_id = get_current_user_id();

$summaryRender = new MNWeddingPlugin\Rsvp\RsvpSummary($user_id);

$attention_count = 0;


$updated = get_user_meta($user_id, 'last_updated', true);

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


<?php if($updated !== ''): ?>

<div class="rsvp-help general-info rsvp-form-container">
	<p>
		<?php 
		echo pll__('Thank you for submitting your RSVP.');
		echo '<br><br>';
		echo pll__('Below you can see the summary of your response. Please check if your info is correct and click "Edit RSVP" if you need to make changes.'); 
		echo '<br><br>';
		echo '<strong>'.pll__('You will be able to change the submitted information right until the deadline.').'</strong>';
		?>
	</p>
</div>

<div class="rsvp-summary-issue rsvp-form-container">


    <!--<span class="issue-icon-circle"><span>!</span></span>-->
	<p class="issue-text-p">
		<span class="issue-icon-circle"><span>!</span></span>
		<?php echo pll__('One or more of the guests attending have not selected the "Overnight" option. Please update this option before the deadline if you change your mind. After the deadline we can\'t guarantee there will be a bed still available for you.'); ?>
	</p>
	
</div>

<?php $summaryRender->display_rsvp_summary($user_id); ?>

<?php else: ?>
	<div class="rsvp-help general-info rsvp-form-container">
		<p>
			<?php echo pll_e('You have not submitted your RSVP yet'); ?>
		</p>
	</div>
<?php endif; ?>




<?php get_footer(); ?>