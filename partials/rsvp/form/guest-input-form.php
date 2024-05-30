<?php 

if (!defined('ABSPATH')) {
    exit;
}


$has_plus_one = $guest['plus_one'];
$is_child = $guest['child'];
$is_baby = $guest['baby'];
$user_language = get_field('frontend_language', 'user_' . strval($user_id));
$guest_id = 'guest_'. $guest_count;
$night_before = \MNWeddingPlugin\MetaConfig::hasNightBefore($user_id);

?>

<div class="guest-form">

	<div class="guest_name rsvp-block">

		<h2 class="<?php echo $guest_id; ?>_name"><?php echo esc_attr($guest['name']); ?></h2>

		<div class="input-container">

			<label for="<?php echo $guest_id; ?>_attending" class="custom-checkbox">
				<?php echo pll_e('Attending'); ?>
				<input type="checkbox" id="<?php echo $guest_id; ?>_attending" name="<?php echo $guest_id; ?>_attending" class="unlock_guest_form" value="on" hidden <?php echo $this->checkbox_state($user_id, '_attending', $guest_id); ?>/>
				<div class="checkmark">
					<div class="checkmark-slider">
						<div class="upper">
							<div class="eye"></div>
							<div class="eye"></div>				
						</div>
						<div class="lower">
							
						</div>
					</div>
				</div>				
			</label>

		</div>

	</div>

	<div class="rsvp-hidden">

		<div class="rsvp-hidden-inner">

			<div class="attendance rsvp-block">

				<div class="help">

					<div class="rsvp-title-row">
						
						<h3><?php echo pll_e('Attendance'); ?></h3>
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
				                    if($has_plus_one){
				                    	echo pll_e('Select "+1" if you would like to bring someone with you. That will show an additional guest form for your +1. This option is only available to selected guests.');
				                    	echo '<br><br>';
				                    } 
				                    echo pll_e('Select "I\'d like to stay overnight" if you intend to sleep at Hotel Rozka at the night of our wedding. Your stay is on us and we kindly recommend you sleep at the hotel and go home safely the next morning after the breakfast.');
				                    echo '<br><br>';
				                    if($night_before){
				                    		echo pll_e('Select "I\'d like to come the night before" if you are coming from far away and intend to arrive to Hotel Rozka the night before our wedding.');
				                    	echo '<br><br>';
				                    }
			                    ?>
			                </p>

			            </div>
			        </div>
					
				</div>

				<div class="input-block">

			

					<?php if($has_plus_one): ?>

						<div class="input-container">

							<label for="<?php echo $guest_id; ?>_plus_one" class="custom-checkbox">
								<?php echo pll_e('+1'); ?>
								<input type="checkbox" class="unlock_plus_one" id="<?php echo $guest_id; ?>_plus_one" name="<?php echo $guest_id; ?>_plus_one" value="on" hidden <?php echo $this->checkbox_state($user_id, '_plus_one', $guest_id); ?>/>
								<div class="checkmark">
									<div class="checkmark-slider">
										<div class="inner"></div>
									</div>
								</div>				
							</label>

							<div class="plus-one-feedback rsvp-help">
								<?php echo pll__("The form for your plus one is available right below this guest."); ?>
							</div>

						</div>

					<?php endif; ?>

					<div class="input-container">

						<label for="<?php echo $guest_id; ?>_overnight" class="custom-checkbox">
							<?php echo pll_e('Overnight'); ?>
							<input type="checkbox" id="<?php echo $guest_id; ?>_overnight" name="<?php echo $guest_id; ?>_overnight" value="on" hidden <?php echo $this->checkbox_state($user_id, '_overnight', $guest_id); ?>/>
							<div class="checkmark">
								<div class="checkmark-slider">
									<div class="upper">
										<div class="eye"></div>
										<div class="eye"></div>				
									</div>
									<div class="lower">
										
									</div>
								</div>
							</div>				
						</label>

					</div>

					

					<?php if($night_before): ?>

						<div class="input-container">

							<label for="<?php echo $guest_id; ?>_night_before" class="custom-checkbox">
								<?php echo pll_e('Night before'); ?>
								<input type="checkbox" id="<?php echo $guest_id; ?>_night_before" name="<?php echo $guest_id; ?>_night_before" value="on" hidden <?php echo $this->checkbox_state($user_id, '_night_before', $guest_id); ?>/>
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

			<div class="food_preferences rsvp-block">

				<div class="help">
					
					<div class="rsvp-title-row">
						
						<h3><?php echo pll_e('Food Preference'); ?></h3>

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
			                    <?php echo pll_e('Here you can select your food preferences for the wedding dinner. Please mark the corresponding fields if you would like to eat vegetarian, vegan or gluten-free food. We kindly ask you to take special attention to the gluten-free option if you need it, and let us know about any allergies we need to know about in the Allergies field.'); ?>
			                </p>

			            </div>
			        </div>

				</div>

				
				<div class="input-container">

					<label for="<?php echo $guest_id; ?>_vegetarian" class="custom-checkbox">
						<?php echo pll_e('Vegetarian'); ?>
						<input type="checkbox" class="hook-vegetarian" id="<?php echo $guest_id; ?>_vegetarian" name="<?php echo $guest_id; ?>_vegetarian" value="on" hidden <?php echo $this->checkbox_state($user_id, '_vegetarian', $guest_id); ?>/>
						<div class="checkmark">
							<div class="checkmark-slider">
								<div class="inner"></div>
							</div>
						</div>				
					</label>

				</div>

				<div class="input-container">

					<label for="<?php echo $guest_id; ?>_vegan" class="custom-checkbox">
						<?php echo pll_e('Vegan'); ?>
						<input type="checkbox" class="hook-vegan" id="<?php echo $guest_id; ?>_vegan" name="<?php echo $guest_id; ?>_vegan" value="on" hidden <?php echo $this->checkbox_state($user_id, '_vegan', $guest_id); ?>/>
						<div class="checkmark">
							<div class="checkmark-slider">
								<div class="inner"></div>
							</div>
						</div>				
					</label>

				</div>

				<div class="input-container">

					<label for="<?php echo $guest_id; ?>_gluten" class="custom-checkbox">
						<?php echo pll_e('Gluten-Free'); ?>
						<input type="checkbox" id="<?php echo $guest_id; ?>_gluten" name="<?php echo $guest_id; ?>_gluten" value="on" hidden <?php echo $this->checkbox_state($user_id, '_gluten', $guest_id); ?>/>
						<div class="checkmark">
							<div class="checkmark-slider">
								<div class="inner"></div>
							</div>
						</div>				
					</label>

				</div>

				<div class="input-container cont-textarea">

					<label for="<?php echo $guest_id; ?>_allergies"><?php echo pll_e('Allergies'); ?></label>
					<textarea name="<?php echo $guest_id; ?>_allergies" id="<?php echo $guest_id; ?>_allergies" rows="5" cols="50"><?php echo get_user_meta($user_id, $guest_id.'_allergies', true); ?></textarea>

				</div>

				
			</div>

			<div class="comments rsvp-block">

				<div class="help">

					<div class="rsvp-title-row">

						<h3><?php echo pll_e('Other wishes'); ?></h3>

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
			                    echo pll__('Due to the large number of guests not everybody will be able to have their own room. In the "Room preference" field you can let us know if you have any special wishes on who you would like to share your room with and we will do our best to make sure everybody is happy with their accomodation. Guests from same families will already be sorted together.'); 
			                    echo '<br><br>';
			                    echo pll__('In "Additional comments" you can write anything else you would like us to know before the wedding.');
			                    ?>
			                </p>

			            </div>
			        </div>

				</div>

				<div class="input-container cont-textarea">

					<label for="<?php echo $guest_id; ?>_room_preference"><?php echo pll_e('Room preference'); ?></label>
					<textarea name="<?php echo $guest_id; ?>_room_preference" id="<?php echo $guest_id; ?>_room_preference" rows="5" cols="50"><?php echo get_user_meta($user_id, $guest_id.'_room_preference', true); ?></textarea>

				</div>

				<div class="input-container cont-textarea">

					<label for="<?php echo $guest_id; ?>_comments"><?php echo pll_e('Additional comments'); ?></label>
					<textarea name="<?php echo $guest_id; ?>_comments" id="<?php echo $guest_id; ?>_comments" rows="5" cols="50"><?php echo get_user_meta($user_id, $guest_id.'_comments', true); ?></textarea>

				</div>

				
			</div>


		</div>



	</div>

	
	


</div>


<?php 

if($has_plus_one):

	include( plugin_dir_path(__FILE__) . '/plus-one-form.php' );

endif; 

?>




