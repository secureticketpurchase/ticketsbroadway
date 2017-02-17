<form role="search" method="get" id="searchform" class="searchform" action="<?php echo home_url( '/' ); ?>event-results/">
    <div>
        <input type="search" id="tosearch" name="tosearch" value="" placeholder="Event, date, location or venue" />

        <button type="submit" id="searchsubmit" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/search.png" /></button>
    </div>
</form>