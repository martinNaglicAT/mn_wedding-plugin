<?php
/*
Template Name: Wedding Plugin Contact
*/
?>


<?php 

if (!defined('ABSPATH')) {
    exit;
}

$contact_form = new MNWeddingPlugin\Contact\NewMessageForm();

$user_id = get_current_user_id();

get_header();

?>

<section class="main_section section-wide section-contact">

    <div class="cover_container container-low">
        <img src="<?php echo get_template_directory_uri().'/assets/img/images/envelope.webp'; ?>" alt="cover" data-fallback="<?php echo get_template_directory_uri().'/assets/img/images/envelope.jpg'; ?>">

        <div class="title">
           <h1><?php the_title(); ?></h1> 
        </div>
        <!--<div class="shader"></div>-->
    </div>   

</section>



<div class="rsvp-contact-container">
    
    <div class="contact-text">

        <p>
            <?php echo pll__('Having trouble with your RSVP? Would you like to ask us something? Talk to the Best man and the Maid of honour? Feel free to use the contact form and contact info below.'); ?>
                
        </p>
        
    </div>




    <div class="contact-form">

        <h2><?php echo pll__('Contact form'); ?></h2>

        <p>
            <?php echo pll__('If you need to contact the bride or the groom, you can use the contact form below. If you\'d like to speak to the Maid of honour or the Best man, please see their contact info below this form and contact them directly.'); ?>
        </p>

        
        <?php echo $contact_form->renderContactForm($user_id); ?>
        
        
    </div>




    <div class="contact-persons">

        <div class="contact-info">

            <h2>Mimi Deisenberger</h2>
            <div class="role"><?php echo pll__('Bride'); ?></div>

            <p>
                <span class="label"><?php echo pll__('Phone').':'; ?></span>
                +43 660 7094094
            </p>

            <p>
                <span class="label"><?php echo pll__('Email').':'; ?></span>
                semira.deisenberger@gmail.com
            </p>
            
        </div>

        <div class="contact-info">

            <h2>Martin Naglič</h2>
            <div class="role"><?php echo pll__('Groom'); ?></div>

            <p>
                <span class="label"><?php echo pll__('Phone').':'; ?></span>
                +386 30 273 436
            </p>

            <p>
                <span class="label"><?php echo pll__('Email').':'; ?></span>
                nagli.martin@gmail.com
            </p>
            
        </div>

        <div class="contact-info">

            <h2>Stefanie Schöll</h2>
            <div class="role"><?php echo pll__('Maid of honor'); ?></div>

            <p>
                <span class="label"><?php echo pll__('Phone').':'; ?></span>
                +43 676 4839087
            </p>

            <p>
                <span class="label"><?php echo pll__('Email').':'; ?></span>
                stefanie.schoell@proton.me
            </p>
            
        </div>

        <?php if(0===1): ?>

        <div class="contact-info">

            <h2>Andrej Gregorc</h2>
            <div class="role"><?php echo pll__('Best man'); ?></div>

            <p>
                <span class="label"><?php echo pll__('Phone').':'; ?></span>
                +386 40 208 048
            </p>

            <p>
                <span class="label"><?php echo pll__('Email').':'; ?></span>
                placeholeder.andrej@gmail.com
            </p>

            
            
        </div>

        <?php endif; ?>


        
    </div>


</div>



<?php get_footer(); ?>