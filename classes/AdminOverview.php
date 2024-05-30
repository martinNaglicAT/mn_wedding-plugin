<?php
namespace MNWeddingPlugin;

use MNWeddingPlugin\MetaConfig;
use MNWeddingPlugin\Rsvp\RsvpUtils;

if (!defined('ABSPATH')) {
    exit;
}

class AdminOverview{

	public function getTotalNumbersOverview(){

		$totalCounts = RsvpUtils::getTotalCounts();
		$countLabels = RsvpUtils::countLabels();

		foreach ($totalCounts as $meta_key => $meta_value) {
			$indent = false;
			$countLabel = $countLabels[$meta_key];
			if($meta_key === '_vegan' || $meta_key === '_plus_one' || $meta_key === '_not_attending'){
				$indent = true;
			}

			?>

				<div class="guest-numbers-block<?php if($indent === true): ?> indent<?php endif; ?>">
					<div class="label">
						<?php echo $countLabel.':'; ?>
					</div>
					<div class="number">
						<?php echo $meta_value; ?>
					</div>			
				</div>

			<?php

			
		}

	}


    public function renderGuestIssues(){

		$overviewArray = $this->getSpecialRequests();

		foreach($overviewArray as $user_key => $guest_data){
			$user_info = get_userdata(intval($user_key));
			$username = $user_info->user_login;

			echo '<div class="text-input-summary">';
			echo '<h3>'.$username.'</h3>';


			echo '<div class="single_user_links">';
			echo '<a href="'.home_url().'/admin-single-user/?mnwedding_action=view&mnwedding_user_id='.$user_key.'">View</a>';
			echo '<a href="'.home_url().'/admin-single-user/?mnwedding_action=edit&mnwedding_user_id='.$user_key.'">Edit</a>';
			echo '</div>';


			echo '<div class="text-summary-inner">';

			foreach($guest_data as $guest_key => $guest_value){
				$index = $guest_value['guest_index'];
				$guest_id = 'guest_'.$index;
				$guest = MetaConfig::guests($user_key)[$index];
				$plusOneMeta = MetaConfig::plusOneMeta($user_key, $guest_id);
				$guest_name = $guest['name'];
				$plus_name = $plusOneMeta[$guest_id.'_name_plus'];

				if( count($guest_value['guest']) > 0){
					echo '<div class="single-summary-container">';
					echo '<h4>'.$guest_name.'</h4>';
					echo '<div class="single-summary">';
					foreach($guest_value['guest'] as $iss_key => $iss_value){
						echo '<p>';
						echo '<span class="label">'.$iss_key.'</span>';
						echo $iss_value;
						echo '</p>';
					}
					echo '</div>';
					echo '</div>';
				}

				if( count($guest_value['plus_one']) > 0){
					echo '<div class="single-summary-container">';
					echo '<h4>'.$plus_name.'</h4>';
					echo '<div class="single-summary">';
					foreach($guest_value['plus_one'] as $iss_key => $iss_value){
						echo '<p>';
						echo '<span class="label">'.$iss_key.'</span>';
						echo $iss_value;
						echo '</p>';
					}
					echo '</div>';
					echo '</div>';
					
				}
			}
			echo '</div>';
			echo '</div>';

		}

	}


	private function getSpecialRequests() {
        $usersWithIssues = [];
        $all_user_ids = get_users(['fields' => 'ID']);

        foreach ($all_user_ids as $user_id) {
            $current_meta = MetaConfig::getUserMeta($user_id);

            if ($current_meta['last_updated'] != '' && $current_meta['reviewed'] !== 'on') {

                $guestData = [];
                $guests = MetaConfig::guests($user_id);
                //var_dump($guests);

                foreach ($guests as $guest => $value) {
                    $guest_id = 'guest_' . $guest;
                    $guestMeta = MetaConfig::getGuestMeta($user_id, $guest_id);
                    $plusOneMeta = MetaConfig::plusOneMeta($user_id, $guest_id);
                    $guestIssues = $this->checkForIssues($guestMeta, $guest_id);
                    $plusOneIssues = $this->checkForIssuesP($plusOneMeta, $guest_id);

                    if (!empty($guestIssues) || !empty($plusOneIssues)) {
                        $guestData[] = [
                        	'guest_index' => $guest,
                            'guest' => $guestIssues,
                            'plus_one' => $plusOneIssues,
                        ];
                    }

                }

                if (!empty($guestData)) {
                    $usersWithIssues[$user_id] = $guestData;
                }
            }
        }

        return $usersWithIssues;
    }

	private function checkForIssues($meta, $guest_id) {
	    $issues = [];
	    if ($meta[$guest_id.'_allergies'] !== '' || $meta[$guest_id.'_room_preference']!=='' || $meta[$guest_id.'_comments'] !== '') {
	        if ($meta[$guest_id.'_allergies'] !== '') {
	        	$issues['Allergies: '] = $meta[$guest_id.'_allergies'];
	        }
	        if ($meta[$guest_id.'_room_preference'] !== '') {
	        	$issues['Room preference: '] = $meta[$guest_id.'_room_preference'];
	        }
	        if ($meta[$guest_id.'_comments'] !== '') {
	        	$issues['Comments: '] = $meta[$guest_id.'_comments'];
	        }
	    }
	    return $issues;
	}

	private function checkForIssuesP($meta, $guest_id) {
	    $issues = [];
	    if ($meta[$guest_id.'_allergies_plus'] !== '' || $meta[$guest_id.'_comments_plus'] !== '') {
	        if ($meta[$guest_id.'_allergies_plus'] !== '') {
	        	$issues['Allergies: '] = $meta[$guest_id.'_allergies_plus'];
	        }
	        if ($meta[$guest_id.'_comments_plus'] !== '') {
	        	$issues['Comments: '] = $meta[$guest_id.'_comments_plus'];
	        }
	    }
	    return $issues;
	}



}