<?php 

if (!defined('ABSPATH')) {
    exit;
}

$night_before = \MNWeddingPlugin\MetaConfig::hasNightBefore($user_id);
?>


<div class="rsvp-user-sheet main-section">
	<div class="rsvp-sheet-container">

	    <div class="last_updated_callback">
	        <?php echo \MNWeddingPlugin\Rsvp\RsvpUtils::last_updated($user_id, $current_language); ?>    
	    </div>

	    <?php if(current_user_can('administrator')): ?>

		    <div class="last_updated_callback">
		    	<?php echo \MNWeddingPlugin\Rsvp\RsvpUtils::last_reviewed($user_id); ?>
		    </div>

		<?php endif; ?>


		<div class="rsvp-user-stats">
			<h2><?php echo pll__('Numbers'); ?></h2>


			<div class='guest-numbers-section'>

				<h3><?php echo pll_e('Attendance and accomodation'); ?></h3>

				<div class="guest-numbers-block">
					<div class="label">
						<?php echo pll__('Attending: '); ?>
					</div>
					<div class="number">
						<?php echo $count_saved_meta['_attending']; ?>
					</div>			
				</div>

				<?php if( $count_saved_meta['_plus_one'] != '0' && $count_saved_meta['_plus_one'] != ''): ?>

					<div class="guest-numbers-block indent">
						<div class="label">
							<?php echo '('.pll__('Plus one: '); ?>
						</div>
						<div class="number">
							<?php echo $count_saved_meta['_plus_one'].')'; ?>
						</div>			
					</div>

				<?php endif; ?>

				<div class="guest-numbers-block">
					<div class="label">
						<?php echo pll__('Not attending: '); ?>
					</div>
					<div class="number">
						<?php echo $count_saved_meta['_not_attending']; ?>
					</div>			
				</div>

				<div class="guest-numbers-block">
					<div class="label">
						<?php echo pll__('Overnight: '); ?>
					</div>
					<div class="number">
						<?php echo $count_saved_meta['_overnight']; ?>
					</div>			
				</div>


				<?php if($night_before): ?>
					<div class="guest-numbers-block">
						<div class="label">
							<?php echo pll__('Night before: '); ?>
						</div>
						<div class="number">
							<?php echo $count_saved_meta['_night_before']; ?>
						</div>			
					</div>

				<?php endif; ?>
				
			</div>


			<div class='guest-numbers-section'>

				<h3><?php echo pll__('Food Preference'); ?></h3>

				<div class="guest-numbers-block">
					<div class="label">
						<?php echo pll__('Vegetarian: '); ?>
					</div>
					<div class="number">
						<?php echo $count_saved_meta['_vegetarian']; ?>
					</div>			
				</div>

				<?php if( $count_saved_meta['_vegan'] != '0' && $count_saved_meta['_vegan'] != ''): ?>

					<div class="guest-numbers-block indent">
						<div class="label">
							<?php echo '('.pll__('Vegan: '); ?>
						</div>
						<div class="number">
							<?php echo $count_saved_meta['_vegan'].')'; ?>
						</div>			
					</div>

				<?php endif; ?>

				<div class="guest-numbers-block">
					<div class="label">
						<?php echo pll__('Gluten free: '); ?>
					</div>
					<div class="number">
						<?php echo $count_saved_meta['_gluten']; ?>
					</div>			
				</div>
				
			</div>


		</div>

		<?php if( strpos($current_meta['user_email'], '@generic.com') == false || $current_meta['phone_number'] != '' ): ?>

		<div class="rsvp-contact-sheet">
			<h2><?php echo pll__('Contact info'); ?></h2>

			<div class="contact-sheet-inner">

			<?php if( strpos($current_meta['user_email'], '@generic.com') == false ): ?>

				<h3><?php echo pll__('Email'); ?></h3>
				<p class="email">
					<?php echo esc_attr($current_meta['user_email']).' - '.$current_meta['email_guest']; ?>
				</p>

			<?php endif; ?>


			<?php if( $current_meta['phone_number'] != '' ): ?>

				<h3><?php echo pll__('Phone'); ?></h3>
				<p class="phone">
					<?php echo $current_meta['country_code'].' '.$current_meta['phone_number'].' - '.$current_meta['phone_guest']; ?>				
				</p>

			<?php endif; ?>

			</div>

		</div>

		<?php endif; ?>




		<div class="rsvp_guest-sheet popup-margin">

			<h2><?php echo pll_e('Guests attending: '); ?></h2>

			<?php $this->display_attending_guests_data($user_id, $current_language, $guests); ?>


			<h2><?php echo pll__('Guests not attending: '); ?></h2>

			<?php $this->display_absent_guests_data($user_id, $guests); ?>

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


</div>

<div class="summary-popup-container visible">

    <div class="popup-icon">
        <a href="#" class="close-popup">
            <div class="close-popup-inner"></div>
        </a>
    </div>

    <div class="edit-rsvp-container">
    	<?php 
    		$nonce = wp_create_nonce('export_user_spreadsheet_nonce');
    		$action = get_query_var('mnwedding_action');
	    	$user_id_query = get_query_var('mnwedding_user_id');
	    	if($action && $user_id_query):
    	?>

    		<a href="<?php echo home_url().'/admin-single-user/?mnwedding_action=edit&mnwedding_user_id='.$user_id_query; ?>"><?php echo pll__('Edit RSVP'); ?></a>

    	<?php else: ?>

    		<a href="<?php echo home_url(); ?><?php echo pll__('/rsvp/') ?>"><?php echo pll__('Edit RSVP'); ?></a>
    	<?php endif; ?>
    </div>


</div>