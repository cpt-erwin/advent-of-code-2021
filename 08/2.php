<?php

/** PART 2 **/

// LOAD DATA
$data = explode( "\n", trim( file_get_contents( __DIR__ . "/data.txt" ) ) );

const DISPLAY = [
	'a' => null,  //   aaaa
	'b' => null,  //  b    c
	'c' => null,  //  b    c
	'd' => null,  //   dddd
	'e' => null,  //  e    f
	'f' => null,  //  e    f
	'g' => null,  //   gggg
];

const DECODE = [
	'abcefg'  => 0,
	'cf'      => 1,
	'acdeg'   => 2,
	'acdfg'   => 3,
	'bcdf'    => 4,
	'abdfg'   => 5,
	'abdefg'  => 6,
	'acf'     => 7,
	'abcdefg' => 8,
	'abcdfg'  => 9,
];

$collection = [];
foreach ( $data as $i => $line ) {
	foreach ( explode( " | ", trim( $line ) ) as $j => $segments ) {
		$collection[ $i ][ $j ] = explode( " ", $segments );
	}
}

function getNumbers( array $data, int $length ): array {
	return array_filter( $data, function ( $e ) use ( $length ) {
		return strlen( $e ) == $length;
	} );
}

function getUniqueNumbers( array $data ): array {
	return [
		1 => current( getNumbers( $data, 2 ) ),
		4 => current( getNumbers( $data, 4 ) ),
		7 => current( getNumbers( $data, 3 ) ),
		8 => current( getNumbers( $data, 7 ) )
	];
}

function getSegments( array $data ): array {
	$display = DISPLAY;

	$unique = getUniqueNumbers( $data );

	echo "<pre>";
	var_dump( $unique );
	echo "</pre>";

	$a = array_diff(
		str_split( $unique[7] ),
		str_split( $unique[1] )
	);

	$display['a'] = $a[ array_key_first( $a ) ];

	// TODO: Implement rest of the logic to get all the segments

	return $display;
}

function decodeOutput( array $decodedSegments, array $output ): ?int {
	$result = [];
	foreach ( $output as $number ) {
		$decodedNumber = '';
		foreach ( str_split( $number ) as $segment ) {
			$decodedNumber .= array_flip( $decodedSegments )[ $segment ];
		}

		$decodedNumber = str_split( $decodedNumber );
		sort( $decodedNumber );
		$decodedNumber = implode( "", $decodedNumber );

		// echo "$number -> $decodedNumber<br>";

		$result[] = DECODE[ $decodedNumber ];
	}

	return (int) implode( "", $result );
}

$result = [];
foreach ( $collection as $line ) {
	$batch           = array_merge( $line[0], $line[1] );
	$decodedSegments = getSegments( $batch );

	$output = decodeOutput( $decodedSegments, $line[1] );

	$result[] = $output;
}

echo "<pre>";
var_dump( $result );
echo "</pre>";

echo "<pre>";
var_dump( array_sum( $result ) );
echo "</pre>";



