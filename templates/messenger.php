<?php
/*
Template Name: Wedding Plugin Messenger
*/
?>


<?php 

if (!defined('ABSPATH')) {
    exit;
}


$messenger = new \MNWeddingPlugin\Contact\RenderMessenger();

get_header();
?>

<div class="main-section">

    <div class="section-messenger">

        <?php $messenger->renderMessengerPage(); ?>
        
    </div>

</div>

<?php
get_footer();