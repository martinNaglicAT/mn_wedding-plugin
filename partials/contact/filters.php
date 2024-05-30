<?php 
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="filters">
    <h3>Filters</h3>

    <label for="test_user" class="custom-checkbox">
        Test
        <input type="checkbox" class="test_user" id="test_user" name="test_user" value="on" hidden/>
        <div class="checkmark">
            <div class="checkmark-slider">
                <div class="inner"></div>
            </div>
        </div>              
    </label>

    <label for="language"><h4>Language</h4></label>
    <select id="language" name="language">
            <option value="en">en</option>
            <option value="de">de</option>
            <option value="si">si</option>
    </select>

    <label for="not_submit" class="custom-checkbox">
        Not submitted
        <input type="checkbox" class="not_submit" id="not_submit" name="not_submit" value="on" hidden/>
        <div class="checkmark">
            <div class="checkmark-slider">
                <div class="inner"></div>
            </div>
        </div>              
    </label>

    <label for="yes_submit" class="custom-checkbox">
        Submitted
        <input type="checkbox" class="yes_submit" id="yes_submit" name="yes_submit" value="on" hidden/>
        <div class="checkmark">
            <div class="checkmark-slider">
                <div class="inner"></div>
            </div>
        </div>              
    </label>

    <label for="specific" class="custom-checkbox">
        Specific user
        <input type="checkbox" class="specific" id="specific" name="specific" value="on" hidden/>
        <div class="checkmark">
            <div class="checkmark-slider">
                <div class="inner"></div>
            </div>
        </div>              
    </label>

    <select id="individual" name="individual">
        <?php 

        $args = array(
		);

		$users = get_users($args);
		$user_details = array();

        foreach( $users as $user ) {
        ?>
            <option value="<?php echo $user->ID; ?>"><?php echo $user->user_login; ?></option>
        <?php } ?>
    </select>

</div>