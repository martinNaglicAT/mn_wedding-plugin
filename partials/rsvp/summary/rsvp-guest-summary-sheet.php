<?php 

if (!defined('ABSPATH')) {
    exit;
}


$night_before = \MNWeddingPlugin\MetaConfig::hasNightBefore($user_id);
?>



<div class="single-guest-summary">

	<h3><?php echo $guest_meta['name']; ?></h3>

	<div class="summary-block">
		<div class='headline--small'><?php echo pll_e('Attendance'); ?>:</div>

		<?php if($night_before): ?>
			<div class="rsvp-summary-flex">
				<p>

					<span class="label">3.5.2024:</span>

					<?php 
						if($guest_meta[$guest_id.'_night_before'] == 'on'):
							echo pll_e('YES');
						else:
							echo pll_e('NO');
						endif; 
					?>

				</p>

				<?php if($guest_meta[$guest_id.'_night_before'] != 'on'): ?>

				<div class="rsvp-issue issue-icon">
	               	<div class="open-issue">
	                    <div class="issue-icon-circle"><div>!</div></div>
	                </div>
	            </div>

	        	<?php endif; ?>
        	</div>

        	<div class="rsvp-issue rsvp-issue-js">
				<div class="issue-text">
					<p>
						<?php echo pll_e('If this option is not selected your bed will not be reserved for you.'); ?>
					</p>
				</div>
			</div>

		<?php endif; ?>

		<div class="rsvp-summary-flex">

			<p>

				<span class="label">4.5.2024:</span>

				<?php 
					if($guest_meta[$guest_id.'_overnight'] == 'on'):
						echo pll_e('YES');
					else: 
						echo pll_e('NO');
					endif; 
				?>

			</p>

			<?php if($guest_meta[$guest_id.'_overnight'] != 'on'): ?>

			<div class="rsvp-issue issue-icon">
               	<div class="open-issue">
                    <div class="issue-icon-circle"><div>!</div></div>
                </div>
            </div>

        	<?php endif; ?>

		</div>

		<div class="rsvp-issue rsvp-issue-js">
			<div class="issue-text">
				<p>
					<?php echo pll_e('If this option is not selected your bed will not be reserved for you.'); ?>
				</p>
			</div>
		</div>

	</div>


	<div class="summary-block">
		<div class='headline--small'><?php echo pll__('Food Preference'); ?>:</div>

		<p>

			<span class="label"><?php echo pll_e('Vegetarian'); ?>:</span>
			<?php 
				if($guest_meta[$guest_id.'_vegetarian'] == 'on'):
					echo pll_e('YES');
				else:
					echo pll_e('NO');
				endif; 
			?>

		</p>


		<p>
			<span class="label"><?php echo pll_e('Vegan'); ?>:</span>

			<?php 
				if($guest_meta[$guest_id.'_vegan'] == 'on'): 
					echo pll_e('YES');
				else: 
					echo pll_e('NO');
				endif;
			?>
		</p>

		<p>
			<span class="label"><?php echo pll_e('Gluten free'); ?>:</span>

			<?php 
				if($guest_meta[$guest_id.'_gluten'] == 'on'): 
					echo pll_e('YES');
				else: 
					echo pll_e('NO');
				endif; 
			?>
		</p>

		<p>
			<span class="label"><?php echo pll_e('Allergies'); ?>:</span>

			<?php 
				if($guest_meta[$guest_id.'_allergies'] == ''): 
					echo pll_e('NONE');
				else: 
					echo $guest_meta[$guest_id.'_allergies']; 
				endif; 
			?>
		</p>



	</div>


	<div class="summary-block">
		<div class='headline--small'><?php echo pll_e('Other wishes'); ?>:</div>

		<p>
			<span class="label"><?php echo pll_e('Room preference'); ?>:</span>

			<?php 
				if($guest_meta[$guest_id.'_room_preference'] == ''): 
					echo pll_e('NONE');
				else: 
					echo $guest_meta[$guest_id.'_room_preference']; 
				endif; 
			?>
		</p>

		<p>
			<span class="label"><?php echo pll_e('Additional comments'); ?>:</span>

			<?php 
				if($guest_meta[$guest_id.'_comments'] == ''):
					echo pll_e('NONE');
				else:
					echo $guest_meta[$guest_id.'_comments'];
				endif; 
			?>
		</p>



	</div>
	


	
</div>


<?php if($guest_meta[$guest_id.'_plus_one'] == 'on'): ?>

<div class="single-guest-summary">

	<h3>
		<?php 
			echo $plus_one_meta[$guest_id.'_name_plus'];
		?>
	</h3>

	<div class="summary-block">
		<div class='headline--small'><?php echo pll_e('Attendance'); ?>:</div>

		<?php if($night_before): ?>

			<div class="rsvp-summary-flex">

				<p>
					<span class="label">3.5.2024:</span>

					<?php 
						if($plus_one_meta[$guest_id.'_night_before_plus'] == 'on'): 
							echo pll_e('YES');
						else: 
							echo pll_e('NO');
						endif; 
					?>
				</p>

				<?php if($plus_one_meta[$guest_id.'_night_before_plus'] != 'on'): ?>

				<div class="rsvp-issue issue-icon">
	               	<div class="open-issue">
	                    <div class="issue-icon-circle"><div>!</div></div>
	                </div>
	            </div>

	        	<?php endif; ?>

			</div>

			<div class="rsvp-issue rsvp-issue-js">
				<div class="issue-text">
					<p>
						<?php echo pll_e('If this option is not selected your bed will not be reserved for you.'); ?>
					</p>
				</div>
			</div>
		<?php endif; ?>

		<div class="rsvp-summary-flex">

			<p>
				<span class="label">4.5.2024:</span>
				<?php 
					if($plus_one_meta[$guest_id.'_overnight_plus'] == 'on'):
						echo pll_e('YES');
					else: 
						echo pll_e('NO');
					endif; 
				?>
			</p>

			<?php if($plus_one_meta[$guest_id.'_overnight_plus'] != 'on'): ?>

			<div class="rsvp-issue issue-icon">
               	<div class="open-issue">
                    <div class="issue-icon-circle"><div>!</div></div>
                </div>
            </div>

        	<?php endif; ?>

		</div>

		<div class="rsvp-issue rsvp-issue-js">
			<div class="issue-text">
				<p>
					<?php echo pll_e('If this option is not selected your bed will not be reserved for you.'); ?>
				</p>
			</div>
		</div>

	</div>


	<div class="summary-block">
		<div class='headline--small'><?php echo pll__('Food Preference'); ?>:</div>

		<p>
			<span class="label"><?php echo pll__('Vegetarian'); ?>:</span>

			<?php 
				if($plus_one_meta[$guest_id.'_vegetarian_plus'] == 'on'):
					echo pll_e('YES');
				else:
					echo pll_e('NO');
				endif; 
			?>
		</p>

		<p>
			<span class="label"><?php echo pll_e('Vegan'); ?>:</span>
			<?php 
				if($plus_one_meta[$guest_id.'_vegan_plus'] == 'on'): 
					echo pll_e('YES');
				 else: 
					echo pll_e('NO');
				endif; 
			?>
		</p>

		<p>
			<span class="label"><?php echo pll_e('Gluten free'); ?>:</span>

			<?php 
				if($plus_one_meta[$guest_id.'_gluten_plus'] == 'on'):
					echo pll_e('YES');
				else: 
					echo pll_e('NO');
				endif; 
			?>
		</p>

		<p>
			<span class="label"><?php echo pll_e('Allergies'); ?>:</span>

			<?php 
				if($plus_one_meta[$guest_id.'_allergies_plus'] == ''): 
					echo pll_e('NONE');
				else: 
					echo $plus_one_meta[$guest_id.'_allergies_plus'];
				endif; 
			?>
		</p>



	</div>


	<div class="summary-block">
		<div class='headline--small'><?php echo pll_e('Other wishes'); ?>:</div>
		<p>
			<span class="label"><?php echo pll_e('Additional comments'); ?>:</span>

			<?php 
				if($plus_one_meta[$guest_id.'_comments_plus'] == ''): 
					echo pll_e('NONE');
				else: 
					echo $plus_one_meta[$guest_id.'_comments_plus'];
				endif; 
			?>
		</p>



	</div>
	


	
</div>

<?php endif; ?>