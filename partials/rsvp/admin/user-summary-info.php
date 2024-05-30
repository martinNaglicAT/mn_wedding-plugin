<?php 

if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="single_user">
	<h3><?php echo $username; ?></h3>
	<div class="single_user_links">
		<a href="<?php echo home_url().'/admin-single-user/?mnwedding_action=view&mnwedding_user_id='.$user_id; ?>">View</a>
		<a href="<?php echo home_url().'/admin-single-user/?mnwedding_action=edit&mnwedding_user_id='.$user_id; ?>">Edit</a>
	</div>

	<div class="single_user_summary">

		<div class="rsvp-user-stats">

			<div class='guest-numbers-section'>

				<div class="guest-numbers-block">
					<div class="label">
						<?php echo pll__('Attending: '); ?>
					</div>
					<div class="number">
						<?php echo $count_saved_meta['_attending']; ?>
					</div>			
				</div>

				<?php if($count_saved_meta['_attending'] != '0'): ?>


					<?php if($count_saved_meta['_overnight'] != '0'): ?>
						<div class="guest-numbers-block">
							<div class="label">
								<?php echo pll__('Overnight: '); ?>
							</div>
							<div class="number">
								<?php echo $count_saved_meta['_overnight']; ?>
							</div>			
						</div>
					<?php endif; ?>


					<?php if($night_before && $count_saved_meta['_night_before'] != '0'): ?>
						<div class="guest-numbers-block">
							<div class="label">
								<?php echo pll__('Night before: '); ?>
							</div>
							<div class="number">
								<?php echo $count_saved_meta['_night_before']; ?>
							</div>			
						</div>

					<?php endif; ?>
					
					<?php if($count_saved_meta['_vegetarian'] != '0'): ?>
						<div class="guest-numbers-block">
							<div class="label">
								<?php echo pll__('Vegetarian: '); ?>
							</div>
							<div class="number">
								<?php echo $count_saved_meta['_vegetarian']; ?>
							</div>			
						</div>
					<?php endif; ?>

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

					<?php if($count_saved_meta['_gluten'] != '0'): ?>
						<div class="guest-numbers-block">
							<div class="label">
								<?php echo pll__('Gluten free: '); ?>
							</div>
							<div class="number">
								<?php echo $count_saved_meta['_gluten']; ?>
							</div>			
						</div>
					<?php endif; ?>

					<?php if( $count_saved_meta['_children'] != '0' && $count_saved_meta['_children'] != ''): ?>

						<div class="guest-numbers-block">
							<div class="label">
								Children:
							</div>
							<div class="number">
								<?php echo $count_saved_meta['_children']; ?>
							</div>			
						</div>

					<?php endif; ?>
					

					<?php if( $count_saved_meta['_babies'] != '0' && $count_saved_meta['_babies'] != ''): ?>

						<div class="guest-numbers-block">
							<div class="label">
								Babies:
							</div>
							<div class="number">
								<?php echo $count_saved_meta['_babies']; ?>
							</div>			
						</div>

					<?php endif; ?>

					<div class="guest-notice">
						<?php echo $attention; ?>
					</div>

				<?php endif; ?>

				<div class="last_updated"><?php echo $last_updated; ?></div>


			</div>


		</div>


	</div>


</div>
