<?php


function get_filter_form($options){ 
    
    ?>
    <form role="search" action="" method="post" id="searchform-find-a-show">
        <?php
        // grab and/or initialize $_POST variables
        if ( isset($_POST['search_paged']) )
            $search_paged = $_POST['search_paged'];
        else
            $search_paged = 1;

        if ( isset($_POST['search_tosearch']) )
            $toSearch = $_POST['search_tosearch'];
        else
            $toSearch = "";

        if ( isset($_POST['search_genre']) )
            $genre = $_POST['search_genre'];
        else
            $genre = "";

        if ( isset($_POST['search_city']) )
            $cityID = $_POST['search_city'];
        else
            $cityID = "";

        if ( isset($_POST['search_month']) )
            $month = $_POST['search_month']; // NOTE: allow for filtering over multiple months
        else
            $month = "";

        global $theme_city;
        if ( $theme_city != "" ) {
            $cityID = $theme_city;
        }

        ?>
        <input type="hidden" name="search_post_type" value="shows" /> <!-- // hidden 'products' value -->
        <input type="hidden" name="search_tosearch" value="<?php echo $toSearch; ?>" />
        <input type="hidden" name="search_paged" value="<?php echo $search_paged; ?>"/>
        <div class="genre-filter">
            <input type="hidden" name="search_genre" value="<?php echo $genre; ?>" />
            <?php if(isset($options['genre']['label']) && $options['genre']['label'] !== ""){ ?>
            <h4>Filter by Genre</h4>
            <?php } ?>
            <?php
            // Alright, let's try fetching a list of genres that have "include_filter" set to true (or 1)
            $genreArgs = array(
                "taxonomy"		=>	"genre",
                "fields"		=>	"all",
                "meta_query"	=>	array(
                    array(
                        "key"		=> "include_filter",
                        "value"		=>	true,
                        "compare"	=>	"="
                    )
                )
            );
            $terms = get_terms( $genreArgs );
            ?>
            <?php
            if ( isset($options['genre']['style']) && $options['genre']['style'] == "list" ) { ?>
                <ul id="genre-filter">
                    <?php foreach( $terms as $term ) { ?>
                    <li id="<?php echo $term->slug; ?>" <?php if( $genre == $term->slug ){ echo "class='active'"; }?>><?php echo $term->name; ?></li>
                    <?php } ?>
                </ul>
            <?php } else { ?>
            <select id="genre-filter" <?php echo (isset($options['genre']['multi'])) ? "size='" . count($terms)+1 . "' multiple" : ""; ?>>
                <option value="">All Genres</option>
                <?php foreach( $terms as $term ) { ?>
                <option value="<?php echo $term->slug; ?>" <?php if($genre == $term->slug){ echo "selected='selected'"; }?>><?php echo $term->name; ?></option>
                <?php } ?>
            </select>
            <?php } ?>
        </div>
        <div class="month-filter">
            <input type="hidden" name="search_month" value="<?php echo $month; ?>" />
            <?php if(isset($options['month']['label']) && $options['month']['label'] !== ""){ ?>
            <h4>Filter by Month</h4>
            <?php }
            if ( isset($options['month']['style']) && $options['month']['style'] == "list" ) { ?>
                <ul id="month-filter">
                    <li id="" <?php if($month == ""){ echo "class='active'"; }?> >All Months</li>
                    <?php
                    for( $m=1; $m <= 12; $m++ ) {
                        $monthName = date( 'F', mktime( 0, 0, 0, $m, 1, date('Y') ) );?>
                    <li id="<?php echo $m; ?>" <?php if($month == $m){ echo "class='active'"; }?> ><?php echo $monthName; ?></li>
                   <?php } ?>
                </ul>
            <?php } else { ?>
            <select class="month-filter" id="month-filter" <?php echo (isset($options['month']['multi'])) ? "size='13' multiple" : ""; ?>  >
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="" <?php if($month === ""){ echo "selected='selected'";} ?>>All Months</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="0" <?php if($month === 0){ echo "selected='selected'";} ?>>January</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="1" <?php if($month == 1){ echo "selected='selected'";} ?>>February</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="2" <?php if($month == 2){ echo "selected='selected'";} ?>>March</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="3" <?php if($month == 3){ echo "selected='selected'";} ?>>April</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="4" <?php if($month == 4){ echo "selected='selected'";} ?>>May</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="5" <?php if($month == 5){ echo "selected='selected'";} ?>>June</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="6" <?php if($month == 6){ echo "selected='selected'";} ?>>July</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="7" <?php if($month == 7){ echo "selected='selected'";} ?>>August</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="8" <?php if($month == 8){ echo "selected='selected'";} ?>>September</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="9" <?php if($month == 9){ echo "selected='selected'";} ?>>October</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="10" <?php if($month == 10){ echo "selected='selected'";} ?>>November</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="11" <?php if($month == 11){ echo "selected='selected'";} ?>>December</option>
            </select>
                            <script type="text/javascript">
                $('.month-selector').val(new Date().getMonth()+1);
            </script>
            <?php } ?>
        </div>
        <div class="city-filter">
            <input type="hidden" name="search_city" value="<?php echo $cityID; ?>" />
            <?php if(isset($options['city']['label']) && $options['city']['label'] !== ""){ ?>
            <h4>Filter by City</h4>
            
            <?php }
            // let get a list of all cities in the DB, build a selector for each one
            $cities = get_posts( array( "post_type" => "city", "posts_per_page" => -1 ) );
            ?>
            <select id="city-filter" <?php echo (isset($options['city']['multi'])) ? "multiple" : ""; ?>>
                <option value="">All Cities</option>
                <?php foreach( $cities as $city ) { ?>
                <option value="<?php echo $city->ID; ?>" <?php if($cityID == $city->ID) echo "selected='selected'"; ?>><?php echo $city->post_title; ?></option>
                <?php } ?>
            </select>
        </div>
    </form>
<script>
    $('form#searchform-find-a-show select').change(adjustForm);
    $('form#searchform-find-a-show ul li').click(adjustForm);
    $(document).ready(function(){
        $('ul.page-numbers a').click(function(e){
            adjustForm(e);
        });
    });
    var timeOutvar;
    function adjustForm(e){
        
        clearTimeout(timeOutvar);

        console.log(e);
        if( e.type == "click" && e.currentTarget.className == "page-numbers search-page-paged"){
            var val = e.currentTarget.dataset.page;
            var field = "paged"
        }else if ( e.type == "change" ) {
            var val = e.target.value;
            var field = e.target.id.split('-')[0];
        } else {
            var val = e.target.id;
            var field = e.target.parentNode.id.split('-')[0];
        }
        
        $('form#searchform-find-a-show input[name="search_'+field+'"]').val(val);
        
        
        timeOutvar = setTimeout(function(){
            console.log("submitting");
            $('#searchform-find-a-show').submit();
        },2000)
    }
</script>
        <?php
}

?>