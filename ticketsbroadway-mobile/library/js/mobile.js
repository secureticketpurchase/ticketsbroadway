jQuery(document).ready(function($){
    
    $('#searchIcon').click(function(){

        var p = $('form.mobile-searchform').find('input')[0];
        p.focus();

    });
    $('.mobile-slider-nav').click(function(){
        
        $('.home-mobile-slider li').toggleClass('hiddenPoster');
    })
    $("#month-selector-mobile").change(sendCalDataMobile);
    $('#venue-selector-mobile').change(sendCalDataMobile);
    $('#add-more-link').click(sendCalDataMobile);
    $('.info-container .buy-tickets').click(showSchedule);
    $('.hideShowSchedule a').click(showSchedule);
    
});

function sendCalDataMobile() {
	var week = "";
    var addMore = false;
	if ( $(this).is("a") ) {
		week = $(this).attr('data-week');
        addMore = true;
	}
	var selectedData = {
		"monthVal"	: $("#month-selector-mobile").val(),
		"venueVal"	: $("#venue-selector-mobile").val(),
		"showID"	: $("#showID").val(),
		"week"		: week,
        "addMore"   : addMore,
        "mobile"    : true
	};
	var toPass = {
		action: "add_mobile_calendar",
		data: selectedData
	};
    console.log(toPass);
    if(week === undefined){
        return;
    }
	$.post( ticket_ajax.ajaxurl, toPass ).done( function(res){
		//$("#events-table").html( res );
        console.log(res);
        if(!addMore){
            $('#showtime-listings').html(res);
        }else{
            res = JSON.parse(res);
            var s = res[0] ? res[0] : "no more";
            var next = s != "no more" ? res[1] : null;
            $('#showtime-listings ul').append(s);

            $('#add-more-link').attr("data-week", next.date);
        }
	} );
};
function showSchedule(e){
    if(e.currentTarget.id == "show-info-link"){
        $('.event-list').hide();
        $('#show-events-link').removeClass('active');
        $('.show-info').show();
        $('#show-info-link').addClass('active');
    }else{
        $('.show-info').hide();
        $('#show-info-link').removeClass('active');
        $('.event-list').show();
        $('#show-events-link').addClass('active');
    }
    
    
}
