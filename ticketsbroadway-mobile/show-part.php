<div class="single-top">
    <div class="img-container">
        <?php 

        if(has_post_thumbnail()){
            the_post_thumbnail('full');
        }else{ ?>
            <img src="<?php echo get_template_directory_uri(); ?>/library/assets/placeholder.jpg">
        <?php } ?>
    </div>
    <div class="info-container">
        <h1><?php echo $post->post_title; ?></h1>
        <?php $cat = get_the_terms($post->ID, 'genre'); if($cat[0]){ ?>
        <span><?php echo $cat[0]->name; ?></span>
        <?php } ?>
        <a class="buy-tickets" id="buy-tickets-button">Buy Tickets</a><!-- js function .single-top .buy-tickets onclick replace main section with calander stuff-->
    </div>
</div>