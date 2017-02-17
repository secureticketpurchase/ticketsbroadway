jQuery(document).ready(function(){
	$("#month-selector").change( sendCalData );
	$("#venue-selector").change( sendCalData );
	$("#events-table").on( "click", "#prev-week-btn", sendCalData );
	$("#events-table").on( "click", "#next-week-btn", sendCalData );
	$("#shows-listing-container").on( "click" , "#next-shows-btn", loadNextShows );
	$("#shows-listing-container").on( "click" , "#prev-shows-btn", loadPrevShows );
	$("#next-week").click( function(){
		console.log("a click!");
		var whatever = {
			action:"add_calendar",
			data:"something"
		};
		$.post( ticket_ajax.ajaxurl, whatever ).done( function(res){
			console.log(res)
		} );
	} );
    //$('#menu-main-nav li:first-of-type').hover(revealShowMenu);
    $('#menu-main-nav li:first-of-type').hover(function(){revealMenu("show")});
    $('#menu-main-nav li:nth-of-type(2)').hover(function(){revealMenu("city")});
    //$('#menu-main-nav li:first-of-type').click(revealShowMenu);
    $('#menu-main-nav li:first-of-type').click(function(){revealMenu("show")});
    $('#menu-main-nav li:nth-of-type(2)').click(function(){revealMenu("city")});
    $('#drop-down-shows').mouseleave(function(e){
        $('#drop-down-shows').css('display', "none");
        //$('#drop-down-shows').slideToggle();
    });
    $('#drop-down-cities').mouseleave(function(e){
        $('#drop-down-cities').css('display', "none");
    });
    $('.ul-genre-list ul li').each(function(n,el){
        console.log(el);
        $(el).hover(function(e){
            $('.ul-genre-list ul li a').each(function(n,el){
                $(el).removeClass('active');
            });
            $(el).find('a').addClass('active');                              
            $('.genre-show-list').each(function(n,el){
                $(el).css('display', 'none');
            })
            var genre = e.target.dataset.genre;
            var su_el = $('#genre-show-list-'+genre);
            su_el.toggle();
            su_el.mouseleave(function(){
                //su_el.css('display', 'none');
            })
        },function(e){

        });
    })
    var d_el = $('#drop-down-shows');
    var cityDropdown = $('#drop-down-cities');
    $('body').click(function(e){
            if(d_el.css('display') !== "none"){
                d_el.click(function(e){
                    console.log("clicked inside div");
                    e.stopPropagation();
                })
                console.log("clicked outside div");
                //d_el.slideToggle();
                $('#drop-down-shows').css('display', "none");
            }
            if(cityDropdown.css('display') !== "none"){
                cityDropdown.click(function(e){
                    console.log("clicked inside div");
                    e.stopPropagation();
                })
                console.log("clicked outside div");
                //d_el.slideToggle();
                $('#drop-down-cities').css('display', "none");
            }
        });
});

function loadNextShows() {
	console.log( "load them next shows!" );
	var showData = {
		"offset"	: $("#shows-listing-container #next-shows-offset").val(),
		"postID"	: $("#post-id").val()
	};
	var toPass = {
		action: "display_shows",
		showData: showData
	};
	console.log(showData);
	$.post( ticket_ajax.ajaxurl, toPass ).done( function(res){
		$("#shows-listing-container").html( res );
		console.log(res)
	} );
}

function loadPrevShows() {
	console.log( "load the previous shows!" );
	var showData = {
		"offset"	: $("#shows-listing-container #prev-shows-offset").val(),
		"postID"	: $("#post-id").val()
	};
	var toPass = {
		action: "display_shows",
		showData: showData 
	};
	console.log(showData);
	$.post( ticket_ajax.ajaxurl, toPass ).done( function(res){
		$("#shows-listing-container").html( res );
		console.log(res)
	} );
}

function sendCalData() {
	var week = "";
	if ( $(this).is("a") ) {
		week = $(this).find("input").val();
	}
	var selectedData = {
		"monthVal"	: $("#month-selector").val(),
		"venueVal"	: $("#venue-selector").val(),
		"showID"	: $("#showID").val(),
		"week"		: week
	};
	var toPass = {
		action: "add_calendar",
		data: selectedData
	};
	console.log(toPass);
	$.post( ticket_ajax.ajaxurl, toPass ).done( function(res){
		$("#events-table").html( res );
		console.log(res)
	} );
};
var showMenuTimeout;
var revealTimeout;
function revealGenreShows(e){
    
}

function revealShowMenu(){
    var el = $('#drop-down-shows');
    if(el.css('display') == "none"){
        el.slideToggle();    
        
    }
}

function revealMenu( menu ){
	console.log(menu);
	if ( menu == "show" ) {
		var toShow = $('#drop-down-shows');
		var toHide = $('#drop-down-cities');
	} else if ( menu == "city" ) {
		var toHide = $('#drop-down-shows');
		var toShow = $('#drop-down-cities');
	}
    if(toShow.css('display') == "none"){
        toShow.css('display', "block");
    }
    if(toHide.css('display') != "none"){
    	toHide.css('display', "none");
    }
}

