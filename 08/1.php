<?php

/** PART 1 **/

// LOAD DATA
$data = explode( "\n", trim( file_get_contents( __DIR__ . "/data.txt" ) ) );

$collection = [];
foreach ( $data as $i => $line ) {
	foreach ( explode( " | ", trim( $line ) ) as $j => $segments ) {
		$collection[ $i ][ $j ] = explode( " ", $segments );
	}
}

echo "<pre>";
var_dump( $collection );
echo "</pre>";

$decode = [
	2 => 1,
	3 => 7,
	4 => 4,
	7 => 8
];

$result = [];
foreach ($collection as $batch) {
	foreach ($batch[1] as $output) {
		if (array_key_exists(strlen($output), $decode)) {
			if (! array_key_exists($decode[strlen($output)], $result)) {
				$result[$decode[strlen($output)]] = 0;
			}
			$result[$decode[strlen($output)]]++;
		}
	}
}

echo "<pre>";
var_dump( $result );
echo "</pre>";

echo "<pre>";
var_dump( array_sum($result) );
echo "</pre>";
