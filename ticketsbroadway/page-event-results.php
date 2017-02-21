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
						<div id="filter-holder"></div>
					</div>

					<div class="body-content d-6of7 t-2of3">

						<?php $search = get_query_var( 'tosearch', 'ca' ); ?>

						<h1>Search Results for: <?php echo $search; ?></h1>

						<script type="text/javascript">var result;</script>

						<?php

						// Grab an array of categories from the {prefix}_categories table, spit out a javascript array
						global $wpdb;
						$table = $wpdb->prefix . "categories";
						$catArray = $wpdb->get_results( "SELECT id, name FROM " . $table, OBJECT_K );
						foreach( $catArray as $cat ) {
							$catArray[$cat->id]->name = ucfirst( strtolower($cat->name) );
						}

						echo "<script>var catArray = " . json_encode( $catArray ) . ";</script>";

						getEventResults();

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

							// create array to hold original filters, to be used when resetting a single filter
							var defaultFilters = {
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
								$("#pagination-holder").html(function() {
									var numPages = Math.floor(res.length / 3);
									console.log(numPages);

									/*if ( numPages > 1 ) {

									}*/
								});
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
									
									// register begin and end date pickers
									$( addPickerListeners() );
									$( doPagination(filteredResults) );
								}
							}

							jQuery(document).ready(function() {
								// initial population of the filters to be manipulated
								populateFilters( result );

								// create array to hold original filters, to be used when resetting a single filter
								var defaultFilters = {
									Days: filters.Days,
									Categories: filters.Categories,
									Shows: filters.Shows,
									Months: filters.Months,
									Venues: filters.Venues,
									Times: filters.Times,
									Cities: filters.Cities,
									Dates: filters.Dates,
									Ranges: filters.Ranges
								};

								$("#filter-holder").append(filterTemplate( {filters:filters} ) );
							});

						</script>

						<div id="stache-holder"></div>
						<div id="pagination-holder"></div>

						<script type="text/javascript">
							// pagination helper
							Handlebars.registerHelper( "numResults", function( arrEvents, offset ) {
								offset = offset === undefined ? 0 : offset;
								return arrEvents.length == 0 ? null : arrEvents.slice(offset, offset + 25);
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
									formattedDay + "<br />" + formattedDate + "<br />" + formattedTime
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
								console.log(filterName);
								if(filterName == "Categories") {
									html += "<li data-value='" + theFilter.id + "' data-name='" + filterName + "' onclick='applyFilters(this)'>" + theFilter.name + "</li>";
								} else if(filterName == "Ranges"){
									console.log("theFilter is " + theFilter );
									html += "<input type='text' id='beginDatePicker' placeholder='from' /><br />";
									html += "<input type='text' id='endDatePicker' placeholder='to' />";
								} else {
									html += "<li data-value='" + theFilter + "' data-name='" + filterName + "' onclick='applyFilters(this)'>" + theFilter + "</li>";
								}
								return new Handlebars.SafeString( html );
							});
						</script>

						<script id="entry-template" type="text/x-handlebars-template">
							<table id="events-results-table">
								<tr id="header">
									<td>Date & Time</td>
									<td>Event</td>
									<td>Location</td>
									<td></td>
								</tr>
								{{#each (numResults theResult 0)}}

								<tr class="event-entry" >
									<td class="date">{{formatDate Date}}</td>
									<td class="show-title">{{Name}}</td>
									<td class="location">
										<span>{{Venue}}</span> <br />
										{{City}}, {{StateProvince}}
									</td>
									<td class="link"><a href="{{buildTicketURL ID}}" class="buy-tickets">Buy Tickets</a></td>
								</tr>
								{{/each}}
							</table>

						</script>

						<script id="filter-template" type="text/x-handlebars-template">
							
							{{#each filters as |filter name|}}
								<ul id={{name}}>
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
							if ( filters.Ranges.length > 0 ) {
								// register begin and end date pickers
								$( addPickerListeners() );
								$( doPagination(result) );
							}

							
						</script>

						<script type="text/javascript">
							//let's start applying some filters!
							function applyFilters(e) {
								
								var name = $(e).data("name");
								var data = $(e).data("value");
								var filteredResults;

								if( data == "all") {
									populateFilters(result);
									filteredResults = result.filter(filterResults);
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

									var filteredResults = result.filter(filterResults);
									populateFilters(filteredResults);

								}

								$("#stache-holder").html(template( 
										{theResult:filteredResults, theOffset:offset}
									));
								$("#filter-holder").html(filterTemplate( {filters:filters} ) );
								if ( filters.Ranges.length > 0 ) {
									// register begin and end date pickers
									$( addPickerListeners() );
									$( doPagination(filteredResults) );
								}
							}

						</script>

					</div>

				</div>
			</div>

<?php
//get_sidebar();
get_footer();
