<?php
/*
 Template Name: Event Search Results page
 *
 * This is your custom page template. You can create as many of these as you need.
 * Simply name is "page-whatever.php" and in add the "Template Name" title at the
 * top, the same way it is here.
 *
 * When you create your page, you can just select the template and viola, you have
 * a custom page template to call your very own. Your mother would be so proud.
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/
get_header();
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/date-fns/1.27.1/date_fns.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/library/js/jquery-ui.min.js"></script>

			<div id="content" class="event-result">

				<div id="inner-content" class="wrap cf">

					<div class="sidebar events-search d-1of7 t-1of3">
						<div class="collapse-button">Refine Results</div>
						<script>
						// function to hide or display filter-holder on click
						$(function() {
							$(".collapse-button").click(function(){ $("#filter-holder").slideToggle(700); });
						});
						</script>
						<div id="filter-holder"></div>
					</div>

					<div class="body-content d-6of7 t-2of3">

						<?php $search = get_query_var( 'tosearch', 'ca' ); ?>

						<h1>Search Results for: <?php echo $search; ?></h1>

						<script type="text/javascript">var result = "";</script>

						<?php

						// Grab an array of categories from the {prefix}_categories table, spit out a javascript array
						global $wpdb;
						$table = $wpdb->prefix . "categories";
						$catArray = $wpdb->get_results( "SELECT id, name FROM " . $table, OBJECT_K );
						foreach( $catArray as $cat ) {
							$catArray[$cat->id]->name = ucfirst( strtolower($cat->name) );
						}

						// first, search for the searched term in the DB, either add or increment
						$searched_terms_table = $wpdb->prefix . "searched_terms";
						$term_query = "SELECT id FROM $searched_terms_table WHERE term = '" . $search . "'";
						$term_db_id = $wpdb->get_col($term_query);

						if ( isset($term_db_id[0]) ) {
							$query = "UPDATE $searched_terms_table SET num_searched = num_searched+1 WHERE id = $term_db_id[0]";
							$success = $wpdb->query($query);
						} else {
							$add_term_array = array( 'term' => $search );
							$wpdb->insert(
								$searched_terms_table,
								$add_term_array
							);
						}

						// try to grab a cached result...if there is one, set the JS var "result" equal to it
						$cached_table = $wpdb->prefix . "cached_results";
						$cache_query = "SELECT result FROM $cached_table WHERE term = '" . $search . "'";
						$cache_res = $wpdb->get_col($cache_query);

						//printDat( $cache_res );

						if( isset($cache_res[0]) ) {
							echo "<script>result = " . $cache_res[0] . ";</script>";
						}

						echo "<script>var catArray = " . json_encode( $catArray ) . ";</script>";
						echo "<script>var tosearch = '" . $search . "';</script>";

						//getEventResults();

						?>

						<script type="text/javascript">

							// initialize filters array
							var filters = {
								Days: [],
								Categories: [],
								Shows: [],
								Months: [],
								Venues: [],
								Times: [],
								Cities: [],
								Dates: [],
								Ranges: []
							};

							var offset = 0;
							var numResults = 25;
							var toAdd = 25; // how many results get added per click?


							// function for populating the filter arrays
							// takes the initial "result" array at first, later instead takes a filtered array
							function populateFilters( theArray ) {
								//reset filters
								filters = {
									Days: [],
									Categories: [],
									Shows: [],
									Months: [],
									Venues: [],
									Times: [],
									Cities: [],
									Dates: [],
									Ranges: []
								};

								theArray.forEach( function(item) {

									//day array first
									var day = dateFns.format(item.Date, "dddd");
									if( $.inArray(day, filters.Days) === -1 ) filters.Days.push(day);

									// grab any categories in result set
									var index = item.ChildCategoryID;
									
									cat = { id: index, name: catArray[index].name };
									var testCat = filters.Categories.filter(function ( obj ) {
									   return obj.id === item.ChildCategoryID;
									})[0];
									if( testCat === undefined ) filters.Categories.push(cat);

									// grab any shows/performers
									if( $.inArray(item.Name, filters.Shows) === -1 ) filters.Shows.push(item.Name);

									// grab any venues
									if ( $.inArray(item.Venue, filters.Venues) === -1 ) filters.Venues.push(item.Venue);

									// grab any cities
									if ( $.inArray(item.City, filters.Cities) === -1 ) filters.Cities.push(item.City);

									// grab months events are happening in
									var month = dateFns.format(item.Date, "MMMM");
									if( $.inArray(month, filters.Months) === -1 ) filters.Months.push(month);

									// populate "time" array (assume "night" > 6PM)
									var time = dateFns.format(item.Date, "H") >= 18 ? "Night" : "Day";
									if ( $.inArray(time, filters.Times) === -1 ) filters.Times.push(time);

									var today = new Date();
									var dateDifference = dateFns.differenceInCalendarDays(item.Date, today);
									if ( dateDifference <= 3 )
										if ( $.inArray("Next 3 Days", filters.Dates) === -1 ) filters.Dates.push("Next 3 Days");

									if ( dateDifference <= 7 )
										if ( $.inArray("Next 7 Days", filters.Dates) === -1 ) filters.Dates.push("Next 7 Days");

									if ( dateDifference <= 30 )
										if ( $.inArray("Next 30 Days", filters.Dates) === -1 ) filters.Dates.push("Next 30 Days");
								});

								
								filters.Ranges.push({beginDate:"", endDate:""});

							}

							function hideFilters() {
								// Javascript for adding "show more" button and functionality
								$('.filter-ul').each(function(index){
								    var max = 2;
								    if ($(this).find('li.filter-item').length > max+1) {

								        $(this).find('li.filter-item:gt('+max+')').hide();

								        //try creating the .sub_accordian element using jQuery
								        var showMore = $("<li></li>");
								        showMore.attr("class", "sub_accordian");
								        showMore.html("<span class='show_more'>(see more)</span>");
								        // attach the function that toggles visibility and switches its content
							        	showMore.click( function(){
								            $(this).siblings('.filter-item:gt('+max+')').toggle();
								            //if ( $('.show_more').length ) {
								            //console.log( $(this).find("span:first").attr('class') );
								            if ( $(this).find('span:first').attr('class') == 'show_more') {
								                $(this).html('<span class="show_less">(see less)</span>');
								            } else {
								                $(this).html('<span class="show_more">(see more)</span>');
								            };
								        });
								        $(this).append(showMore);
								    };
								});
							}

							// function for applying the filters to the result set, returns true or false (to be used with the JS "filter" method)
							function filterResults( item ) {
								var day = dateFns.format(item.Date, "dddd");
								var month = dateFns.format(item.Date, "MMMM");
								var time = dateFns.format(item.Date, "H") >= 18 ? "Night" : "Day";


								if( $.inArray(day, filters.Days) == -1 ) return false;
								if( $.inArray(item.Name, filters.Shows) == -1 ) return false;
								if( $.inArray(month, filters.Months) == -1 ) return false;
								if( $.inArray(time, filters.Times) == -1 ) return false;
								if( $.inArray(item.Venue, filters.Venues) == -1 ) return false;
								if( $.inArray(item.City, filters.Cities) == -1 ) return false;

								var dateRes = filterDates(item);

								if( dateRes === false ) {
									return false;
								}

								var cat = filters.Categories.filter(function ( obj ) {
								   return obj.id === item.ChildCategoryID;
								})[0];
								if( cat === undefined ) return false;

								console.log("Ranges is ", filters.Ranges[0].beginDate);

								if ( filters.Ranges[0].beginDate != "" && filters.Ranges[0].endDate != "" ) {
									return dateFns.isWithinRange(item.Date, filters.Ranges[0].beginDate, filters.Ranges[0].endDate);
								}

								
								return true;
							}

							function filterDates( item ) {
								if( filters.Dates.length > 1) {
									return null;
								}
								var today = new Date();
								var dateDifference = dateFns.differenceInCalendarDays(item.Date, today);

								if( $.inArray("Next 30 Days", filters.Dates) != -1 && dateDifference > 30 ) {
									return false;
								}else if( $.inArray("Next 7 Days", filters.Dates) != -1 && dateDifference > 7 ) {
									return false;
								}else if( $.inArray("Next 3 Days", filters.Dates) != -1 && dateDifference > 3 ) {
									return false;
								}
							}

							function addPickerListeners() {
								jQuery( "#beginDatePicker" ).datepicker({
									onClose: doDateRange
								});
								jQuery( "#endDatePicker" ).datepicker({
									onClose: doDateRange
								});
							}

							function doPagination(res) {
								console.log("Paginating!", res);
								console.log("result length is " + res.length)
								if( res.length > numResults ) {
									// variable to hold the button we'll be adding
									var addResults = $("<div>Show More</div>");
									addResults.attr("data-offset",offset);
									addResults.click( function() {
										numResults += toAdd;
										$("#stache-holder").html(template(
											{
												theResult:res,
												theOffset:offset
											}
										));
										doPagination(res);
									});
									$('#pagination-holder').html(addResults);
								} else {
									$("#pagination-holder").html('');
								}
							}

							function doDateRange() {
								console.log("Doing date range");
								var beginVal = jQuery( "#beginDatePicker" ).val();
								var endVal = jQuery( "#endDatePicker" ).val();
								if( beginVal != "" && endVal != "" ) {
									filters.Ranges[0].beginDate = beginVal;
									filters.Ranges[0].endDate = endVal;

									var filteredResults = result.filter(filterResults);
									populateFilters(filteredResults);

									$("#stache-holder").html(template(
											{theResult:filteredResults, theOffset:offset}
										));
									$("#filter-holder").html(filterTemplate( {filters:filters} ) );
									hideFilters();
									doPagination(filteredResults);
									
									// register begin and end date pickers
									$( addPickerListeners() );
								}
							}

							function loadEventResults() {
								var toPass = {
									action: "get_event_results",
									data: {
										tosearch: tosearch
									}
								}
								console.log(result);
								if ( result != "" ) {
									$("#stache-holder").html(template( 
											{theResult:result, theOffset:offset}
										));
									//result = res;
									populateFilters( result );

									doPagination(result);

									if ( filters.Ranges.length > 0 ) {
										// register begin and end date pickers
										$( addPickerListeners() );
										//$( doPagination(result) );
									}
								}
								$.post( ticket_ajax.ajaxurl, toPass ).done( function(res){

									//console.log(res);
									if ( result == "" ) {
										console.log( "result is ",res);
										result = res;
										//result = res;
										$("#stache-holder").html(template( 
												{theResult:result, theOffset:offset}
											));
										populateFilters( result );

										$("#filter-holder").append(filterTemplate( {filters:filters} ) );

										hideFilters();
										doPagination(result);

										if ( filters.Ranges.length > 0 ) {
											// register begin and end date pickers
											$( addPickerListeners() );
											//$( doPagination(result) );
										}
									} else {
										result = res;
										populateFilters( result );
										$("#filter-holder").append(filterTemplate( {filters:filters} ) );

										hideFilters();
										doPagination(result);

										if ( filters.Ranges.length > 0 ) {
											// register begin and end date pickers
											$( addPickerListeners() );
											//$( doPagination(result) );
										}
									}
								});

							};

							jQuery(document).ready(function() {
								// initial population of the filters to be manipulated
								loadEventResults();
							});

						</script>

						<div id="stache-holder">
							<div class="sk-folding-cube">
								<div class="sk-cube1 sk-cube"></div>
								<div class="sk-cube2 sk-cube"></div>
								<div class="sk-cube4 sk-cube"></div>
								<div class="sk-cube3 sk-cube"></div>
							</div>
						</div>
						<div id="pagination-holder"></div>

						<script type="text/javascript">
							// pagination helper
							Handlebars.registerHelper( "numResults", function( arrEvents, offset ) {
								offset = offset === undefined ? 0 : offset;
								return arrEvents.length == 0 ? null : arrEvents.slice(offset, offset + numResults);
								return arrEvents;
							} );

							Handlebars.registerHelper( "formatDate", function( rawDate ) {
								var date = new Date(rawDate);
								
								var formattedDay = dateFns.format( rawDate, "dddd" );
								var formattedDate = dateFns.format( rawDate, "MMMM D, YYYY" );
								var formattedTime = dateFns.format( rawDate, "h:mm a" );

								// build out and return the display
								return new Handlebars.SafeString(
									//day + "<br />" + month + " " + monthDay + ", " + year + "<br />" + hour + ":" + minute + " " + period
									"<div>" + formattedDay + "</div><div>" + formattedDate + "</div><div>" + formattedTime + "</div>"
								);
							});
							
							Handlebars.registerHelper( "buildTicketURL", function( ticketID ) {
								var getURL = window.location;
								//var baseURL = getURL.protocol + "//" + getURL.host + "/" + getURL.pathname.split('/')[1];
								var baseURL = "<?php echo site_url(); ?>";

								var ticketURL = baseURL + "/tickets/?eventID=" + ticketID;

								return ticketURL;
							});

							Handlebars.registerHelper( "buildList", function( filterName, theFilter ) {
								var html = "";
								if(filterName == "Categories") {
									html += "<li class='filter-item' data-value='" + theFilter.id + "' data-name='" + filterName + "' onclick='applyFilters(this)'>" + theFilter.name + "</li>";
								} else if (filterName == "Dates") {
									html += "<li data-value='" + theFilter + "' data-name='" + filterName + "' onclick='applyFilters(this)'>" + theFilter + "</li>";
								} else if(filterName == "Ranges"){
									console.log("theFilter is " + theFilter );
									html += "<input type='text' id='beginDatePicker' placeholder='from' /><br />";
									html += "<input type='text' id='endDatePicker' placeholder='to' />";
								} else {
									html += '<li class="filter-item" data-value="' + theFilter + '" data-name="' + filterName + '" onclick="applyFilters(this)">' + theFilter + '</li>';
								}
								return new Handlebars.SafeString( html );
							});
						</script>

						<script id="entry-template" type="text/x-handlebars-template">
							<table id="events-results-table">
								<tr id="header">
									<td id="header-date">Date & Time</td>
									<td class="event-column">Event</td>
									<td></td>
								</tr>
								{{#each (numResults theResult 0)}}

								<tr class="event-entry" >
									<td class="date">{{formatDate Date}}</td>
									<td class="event-location">
										<div class="show-title">{{Name}}</div>
										<div>{{Venue}} - {{City}}, {{StateProvince}}</div>
									</td>
									<td class="link"><a href="{{buildTicketURL ID}}" class="buy-tickets">Buy Tickets</a></td>
								</tr>
								{{/each}}
							</table>

						</script>

						<script id="filter-template" type="text/x-handlebars-template">
							
							{{#each filters as |filter name|}}
								<ul id={{name}} class="filter-ul">
									<li data-value='all' data-name='{{name}}' onclick='applyFilters(this)'>All {{name}}</li>
								{{#each filter}}
									{{buildList name this}}
								{{/each}}
								</ul>
							{{/each}}

						</script>

						<script type="text/javascript">
							var source   = $("#entry-template").html();
							var template = window.template = Handlebars.compile(source);

							var filterSource = $("#filter-template").html();
							var filterTemplate = window.filterTemplate = Handlebars.compile(filterSource);


							$("#stache-holder").append(template(
											{
												theResult:result,
												theOffset:offset
											}
										));
							doPagination(result);
							if ( filters.Ranges.length > 0 ) {
								// register begin and end date pickers
								$( addPickerListeners() );
								//$( doPagination(result) );
							}

							
						</script>

						<script type="text/javascript">
							//let's start applying some filters!
							function applyFilters(e) {
								
								var name = $(e).data("name");
								var data = $(e).data("value");
								var filteredResults;
								numResults = toAdd;

								if( data == "all") {
									populateFilters(result);
									filteredResults = result.filter(filterResults);
									console.log("all filtered results is",filteredResults);
								} else {
									//select filter array to change
									switch(name) {
										case "Days":
											filters.Days = [];
											filters.Days.push(data);
											break;
										case "Categories":
											var cat = {id: data, name: name};
											filters.Categories = [];
											filters.Categories.push(cat);
											break;
										case "Shows":
											filters.Shows = [];
											filters.Shows.push(data);
											break;
										case "Months":
											filters.Months = [];
											filters.Months.push(data);
											break;
										case "Venues":
											filters.Venues = [];
											filters.Venues.push(data);
											break;
										case "Times":
											filters.Times = [];
											filters.Times.push(data);
											break;
										case "Cities":
											filters.Cities = [];
											filters.Cities.push(data);
											break;
										case "Dates":
											filters.Dates = [];
											filters.Dates.push(data);
											console.log( "filtered Dates is " , filters.Dates );
									}

									filteredResults = result.filter(filterResults);
									populateFilters(filteredResults);

								}

								$("#stache-holder").html(template( 
										{theResult:filteredResults, theOffset:offset}
									));
								$("#filter-holder").html(filterTemplate( {filters:filters} ) );
								hideFilters();
								doPagination(filteredResults)
								if ( filters.Ranges.length > 0 ) {
									// register begin and end date pickers
									$( addPickerListeners() );
								}
							}

						</script>

					</div>

				</div>
			</div>

<?php
//get_sidebar();
get_footer();
