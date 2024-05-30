<?php 

if (!defined('ABSPATH')) {
    exit;
}


$user_language = get_field('frontend_language', 'user_' . strval($user_id));
$guest_id = 'guest_'. $guest_count;
$night_before = \MNWeddingPlugin\MetaConfig::hasNightBefore($user_id);

?>

<div class="guest-form form-plus-one">

	<div class="guest_name rsvp-block">

		<!--<div class="required-field-error"><?php //echo pll_e('Please write down the name of your plus one.'); ?></div>-->
		<input type="text" class="name-plus-one" name="<?php echo $guest_id; ?>_name_plus" value="<?php echo get_user_meta($user_id, $guest_id.'_name_plus', true); ?>" placeholder="<?php echo pll_e('Type your +1s name here'); ?>">
		<div class="required-field" id="error-<?php echo $guest_id; ?>"><?php echo pll_e('(Required field)'); ?></div>

	</div>

	<div class="hidden-plus-one">

		<div>

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

			

					<div class="input-container">

						<label for="<?php echo $guest_id; ?>_overnight_plus" class="custom-checkbox">
							<?php echo pll_e('Overnight'); ?>
							<input type="checkbox" id="<?php echo $guest_id; ?>_overnight_plus" name="<?php echo $guest_id; ?>_overnight_plus" value="on" hidden <?php echo $this->checkbox_state($user_id, '_overnight_plus', $guest_id); ?>/>
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

					

					<?php if($user_language === 'de' || in_array($user_id, array(1, 2, 3, 29))): ?>

						<div class="input-container">

							<label for="<?php echo $guest_id; ?>_night_before_plus" class="custom-checkbox">
								<?php echo pll_e('Night before'); ?>
								<input type="checkbox" id="<?php echo $guest_id; ?>_night_before_plus" name="<?php echo $guest_id; ?>_night_before_plus" value="on" hidden <?php echo $this->checkbox_state($user_id, '_night_before_plus', $guest_id); ?>/>
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

					<label for="<?php echo $guest_id; ?>_vegetarian_plus" class="custom-checkbox">
						<?php echo pll_e('Vegetarian'); ?>
						<input type="checkbox" class="hook-vegetarian" id="<?php echo $guest_id; ?>_vegetarian_plus" name="<?php echo $guest_id; ?>_vegetarian_plus" value="on" hidden <?php echo $this->checkbox_state($user_id, '_vegetarian_plus', $guest_id); ?>/>
						<div class="checkmark">
							<div class="checkmark-slider">
								<div class="inner"></div>
							</div>
						</div>				
					</label>

				</div>

				<div class="input-container">

					<label for="<?php echo $guest_id; ?>_vegan_plus" class="custom-checkbox">
						<?php echo pll_e('Vegan'); ?>
						<input type="checkbox" class="hook-vegan" id="<?php echo $guest_id; ?>_vegan_plus" name="<?php echo $guest_id; ?>_vegan_plus" value="on" hidden <?php echo $this->checkbox_state($user_id, '_vegan_plus', $guest_id); ?>/>
						<div class="checkmark">
							<div class="checkmark-slider">
								<div class="inner"></div>
							</div>
						</div>				
					</label>

				</div>

				<div class="input-container">

					<label for="<?php echo $guest_id; ?>_gluten_plus" class="custom-checkbox">
						<?php echo pll_e('Gluten-Free'); ?>
						<input type="checkbox" id="<?php echo $guest_id; ?>_gluten_plus" name="<?php echo $guest_id; ?>_gluten_plus" value="on" hidden <?php echo $this->checkbox_state($user_id, '_gluten_plus', $guest_id); ?>/>
						<div class="checkmark">
							<div class="checkmark-slider">
								<div class="inner"></div>
							</div>
						</div>				
					</label>

				</div>

				<div class="input-container cont-textarea">

					<label for="<?php echo $guest_id; ?>_allergies_plus"><?php echo pll_e('Allergies'); ?></label>
					<textarea name="<?php echo $guest_id; ?>_allergies_plus" id="<?php echo $guest_id; ?>_allergies_plus" rows="5" cols="50"><?php echo get_user_meta($user_id, $guest_id.'_allergies_plus', true); ?></textarea>

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
			                    echo pll_e('In "Additional comments" you can write anything else you would like us to know before the wedding.');
			                    echo '<br><br>';
			                    echo pll_e('Room preference for the +1 will be treated as same as the main guest.');
			                    ?>
			                </p>

			            </div>
			        </div>

				</div>

				<div class="input-container cont-textarea">

					<label for="<?php echo $guest_id; ?>_comments_plus"><?php echo pll_e('Additional comments'); ?></label>
					<textarea name="<?php echo $guest_id; ?>_comments_plus" id="<?php echo $guest_id; ?>_comments_plus" rows="5" cols="50"><?php echo get_user_meta($user_id, $guest_id.'_commentss_plus', true); ?></textarea>

				</div>

				
			</div>

		</div>

	</div>

	
	


</div>