<?php

/** PART 1 & 2 **/

const NUMBER_OF_DAYS = 256;
const DEBUG          = false;

// LOAD DATA
$lanternFish = explode( ",", trim( file_get_contents( __DIR__ . "/data.txt" ) ) );

echo "Initial state:\t\t" . implode( ',', $lanternFish ) . "\n";

$lanternFish = array_count_values( $lanternFish );
$newborns    = [ 0 ];

for ( $i = 0; $i < 7; $i ++ ) {
	if ( ! array_key_exists( $i, $lanternFish ) ) {
		$lanternFish[ $i ] = 0;
	}
}

function ageNewborns( array &$lanternFish, array &$newborns, int $i ) {

	$lanternFish[ $i ] += $newborns[0];
	$newborns[0]       = 0;

	ksort( $newborns );
	foreach ( $newborns as $index => $newbornGroup ) {
		if ( $index === 0 ) {
			continue;
		}
		$newborns[ ( $index - 1 ) ] = $newbornGroup;
	}
}

for ( $i = 0; $i < NUMBER_OF_DAYS; $i ++ ) {

	$index = $i % 7;

	ageNewborns( $lanternFish, $newborns, $index );

	if ( array_key_exists( $index, $lanternFish ) ) {
		$newborns[8] = $lanternFish[ $index ];
	}

	if ( DEBUG ) {
		echo "After " . ( $i + 1 ) . " days:\t\t" . implode( ',', $lanternFish ) . "\n";
	}
}

echo "\n------------------------------\n\n";
echo "Result: " . array_sum( [ array_sum( $lanternFish ), array_sum( $newborns ) ] );
