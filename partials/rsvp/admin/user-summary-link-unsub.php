<?php 

if (!defined('ABSPATH')) {
    exit;
}

?>


<div class="single_user">
    <h3><?php echo $username; ?></h3>
    <div class="single_user_links">
        <a href="<?php echo home_url().'/admin-single-user/?mnwedding_action=edit&mnwedding_user_id='.$user_id; ?>">Edit</a>
    </div>

    
</div>