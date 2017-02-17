<?php

global $post;

// create a query to grab a list of cast members
$memberIDs = get_post_meta( $post->ID, "cast_members", true );

$cast = array();
$crew = array();

$args = array(
		"include"	=> $memberIDs,
		"post_type"	=> "cast"
	);

if ( $memberIDs !== '' ) {
	$members = get_posts( $args );

	foreach ( $members as $member ) {
		$role = get_post_meta( $member->ID, "cast_or_crew", true );

		if ( $role == "Cast" )
			$cast[] = $member;
		else
			$crew[] = $member;
	}
}

/*echo "<pre>";
print_r( $members );
echo "</pre>";*/

/*echo "<pre>";
print_r( $cast );
echo "</pre>";*/
echo "<div class='cast-crew'>";
echo "<div class='cast-list'><h3>Cast</h3>";
$cntr = 0;

while( $cntr < count( $cast ) ) {
	echo "<div class='person'>";
	echo "<span class='character-name'>" . get_post_meta( $cast[$cntr]->ID, "character_name", true ) . "</span>";
	echo "<span class='actor-name'>";
	if ( $cast[$cntr]->post_content !== '' )
		echo "<a href='" . get_permalink($cast[$cntr]->ID) . "'>" . $cast[$cntr]->post_title . "</a>";
	else
		echo $cast[$cntr]->post_title;
	echo "</span>";
	echo "</div>";
	$cntr++;
}
echo "</div>";

echo "<div class='crew-list'><h3>Crew</h3>";
$cntr = 0;

while( $cntr < count( $crew ) ) {
	echo "<div class='person'>";
	echo "<span class='role-name'>" . get_post_meta( $crew[$cntr]->ID, "crew_role", true ) . "</span>";
	echo "<span class='crew-name'>";
	if ( $crew[$cntr]->post_content !== '' )
		echo "<a href='" . get_permalink($crew[$cntr]->ID) . "'>" . $crew[$cntr]->post_title . "</a>";
	else
		echo $crew[$cntr]->post_title;
	echo "</span>";
	echo "</div>";
	$cntr++;
}
echo "</div>";
echo "</div>";
?>