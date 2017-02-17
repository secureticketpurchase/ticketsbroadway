<?php
/*

*/
/*if ( explode('.', $_SERVER['HTTP_HOST'])[0] == "dev" ) {
	DEFINE('WSDL', 'http://tnwebservices-test.ticketnetwork.com/TNWebService/v3.1/WSDL/tnwebservicestringinputs.xml');
} else {
	DEFINE('WSDL', 'http://tnwebservices.ticketnetwork.com/TNWebService/v3.2/WSDL/tnwebservicestringinputs.xml');
}*/
DEFINE('WSDL', 'http://tnwebservices.ticketnetwork.com/TNWebService/v3.2/WSDL/tnwebservicestringinputs.xml');

DEFINE('WEB_CONF_ID', 20732); // make sure you change this to your config id
DEFINE('HIGH_INVENTORY_PERFORMERS_LENGTH', 5);
DEFINE('HIGH_SALES_PERFORMERS_LENGTH', 5);

DEFINE('DEFAULT_COL_SORT', 'Date');

DEFINE('TICKET_PAGINATION', ''); // empty string for no pagination, else u can use this to separate ticket group 
																//results into pages


// Define parent category array, then serialize and store as a constant
$parentCats = serialize (
	array(
		1 	=>	"Sports",
		2 	=>	"Concerts",
		3 	=>	"Theater",
		4 	=>	"Other"
	)
);
DEFINE( 'PARENT_CATS', $parentCats );

// Define child category array, then serialize and store as a constant
$childCats = serialize (
	array (
		21 	=>	"Jazz and Blues",
		22 	=>	"Alternative",
		23 	=>	"Country and Folk",
		24 	=>	"Comedy",
		27 	=>	"Tennis",
		32 	=>	"Off Broadway",
		33 	=>	"Other Child",
		34 	=>	"Las Vegas Shows",
		35 	=>	"Las Vegas",
		36 	=>	"Rap and Hip Hop",
		37 	=>	"Other Child 2",
		38 	=>	"Musicals and Plays",
		39 	=>	"Wrestling",
		41 	=>	"Other Child 3",
		43 	=>	"Christian Religious",
		45 	=>	"Rythm and Blues and Soul",
		46 	=>	"Bluegrass",
		47 	=>	"Volleyball",
		48 	=>	"New Age and Spiritual",
		49 	=>	"Classical",
		50 	=>	"Boxing",
		52 	=>	"Skating",
		53 	=>	"Rodeo",
		55 	=>	"Children and Family Shows",
		57 	=>	"World",
		58 	=>	"Fairs and Festivals",
		59 	=>	"Circus",
		60 	=>	"Ballet",
		61 	=>	"Hard Rock and Metal",
		62 	=>	"Pop and Rock",
		63 	=>	"Baseball",
		65 	=>	"Football",
		66 	=>	"Basketball",
		67 	=>	"Golf",
		68 	=>	"Hockey",
		69 	=>	"Racing",
		70 	=>	"Broadway",
		71 	=>	"Soccer",
		72 	=>	"Magic Shows",
		73 	=>	"Latin",
		74 	=>	"Other Child 4",
		75 	=>	"Opera",
		76 	=>	"Lacrosse",
		77 	=>	"Rugby"
	)
);
DEFINE( 'CHILD_CATS', $childCats );

// define grandchild category array, then serialize and store as a constant
$grandchildCats = serialize (
	array (
		16 	=>	"MLB Pro",
		17 	=>	"College",
		18 	=>	"PGA Pro",
		19 	=>	"NHL Pro",
		20 	=>	"Auto",
		21 	=>	"Motorcycle",
		22 	=>	"MLS Pro",
		24 	=>	"USPTA Pro",
		25 	=>	"Empty Grandchild",
		26 	=>	"WWE Pro",
		27 	=>	"Minors AAA",
		28 	=>	"World Cup",
		29 	=>	"Other Grandchild",
		30 	=>	"NBA Pro",
		31 	=>	"WNBA Pro",
		32 	=>	"NFL Pro",
		33 	=>	"Ice Figure Skating",
		34 	=>	"Ice Show",
		35 	=>	"Horse",
		36 	=>	"CFL",
		37 	=>	"Frontier League",
		38 	=>	"NLL",
		39 	=>	"IHL",
		40 	=>	"AHL",
		41 	=>	"ECHL"
	)
);
DEFINE( 'GRANDCHILD_CATS', $grandchildCats );

// Category ID Numbers
//parent category
DEFINE('SPORTS', 1);
DEFINE('CONCERTS', 2);
DEFINE('THEATER', 3);
DEFINE('OTHER', 4);

//child category
DEFINE('JAZZ_AND_BLUES', 21);
DEFINE('ALTERNATIVE', 22);
DEFINE('COUNTRY_AND_FOLK', 23);
DEFINE('COMEDY', 24);
DEFINE('TENNIS', 27);
DEFINE('OFF_BROADWAY', 32);
DEFINE('OTHER_CHILD1', 33);
DEFINE('LAS_VEGAS_SHOWS', 34);
DEFINE('LAS_VEGAS', 35);
DEFINE('RAP_AND_HIP_HOP', 36);
DEFINE('OTHER_CHILD2', 37);
DEFINE('MUSICALS_AND_PLAYS', 38);
DEFINE('WRESTLING', 39);
DEFINE('OTHER_CHILD3', 41);
DEFINE('CHRISTIAN_RELIGIOUS', 43);
DEFINE('RYTHM_AND_BLUES_AND_SOUL', 45);
DEFINE('BLUEGRASS', 46);
DEFINE('VOLLEYBALL', 47);
DEFINE('NEW_AGE_AND_SPIRITUAL', 48);
DEFINE('CLASSICAL', 49);
DEFINE('BOXING', 50);
DEFINE('SKATING', 52);
DEFINE('RODEO', 53);
DEFINE('CHILDREN_AND_FAMILY_SHOWS', 55);
DEFINE('WORLD', 57);
DEFINE('FAIRS_AND_FESTIVALS', 58);
DEFINE('CIRCUS', 59);
DEFINE('BALLET', 60);
DEFINE('HARD_ROCK_AND_METAL', 61);
DEFINE('POP_AND_ROCK', 62);
DEFINE('BASEBALL', 63);
DEFINE('FOOTBALL', 65);
DEFINE('BASKETBALL', 66);
DEFINE('GOLF', 67);
DEFINE('HOCKEY', 68);
DEFINE('RACING', 69);
DEFINE('BROADWAY', 70);
DEFINE('SOCCER', 71);
DEFINE('MAGIC_SHOWS', 72);
DEFINE('LATIN', 73);
DEFINE('OTHER_CHILD4', 74);
DEFINE('OPERA', 75);
DEFINE('LACROSSE', 76);
DEFINE('RUGBY', 77);

//grandchild category
DEFINE('MLB_PRO_GRANDCHILD', 16);
DEFINE('COLLEGE_GRANDCHILD', 17);
DEFINE('PGA_PRO_GRANDCHILD', 18);
DEFINE('NHL_PRO_GRANDCHILD', 19);
DEFINE('AUTO_GRANDCHILD', 20);
DEFINE('MOTORCYCLE_GRANDCHILD', 21);
DEFINE('MLS_PRO_GRANDCHILD', 22);
DEFINE('USPTA_PRO_GRANDCHILD', 24);
DEFINE('OTHER_GRANDCHILD1', 25);
DEFINE('WWE_PRO_GRANDCHILD', 26);
DEFINE('MINORS_AAA_GRANDCHILD', 27);
DEFINE('WORLD_CUP_GRANDCHILD', 28);
DEFINE('OTHER_GRANDCHILD2', 29);
DEFINE('NBA_PRO_GRANDCHILD', 30);
DEFINE('WNBA_PRO_GRANDCHILD', 31);
DEFINE('NFL_PRO_GRANDCHILD', 32);
DEFINE('ICE_FIGURE_SKATING_GRANDCHILD', 33);
DEFINE('ICE_SHOW_GRANDCHILD', 34);
DEFINE('HORSE_GRANDCHILD', 35);
DEFINE('CFL_GRANDCHILD', 36);
DEFINE('FRONTIER_LEAGUE_GRANDCHILD', 37);
DEFINE('NLL_GRANDCHILD', 38);
DEFINE('IHL_GRANDCHILD', 39);
DEFINE('AHL_GRANDCHILD', 40);
DEFINE('ECHL_GRANDCHILD', 41);
		
		