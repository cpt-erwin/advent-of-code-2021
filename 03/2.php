<?php

/** PART 2 **/

/**
 * Filters array of binary numbers by specific bit value.
 *
 * @param array $data
 * @param int $position
 * @param int $filter
 *
 * @return array
 */
function filter( array $data, int $position, int $filter ): array {
	$filteredData = [];
	foreach ( $data as $binaryNumber ) {
		if ( ( (int) str_split( $binaryNumber )[ $position ] ) == $filter ) {
			$filteredData[] = trim( $binaryNumber );
		}
	}

	return $filteredData;
}

/**
 * Returns an array with keys representing the bit position and value composed of array with indexes representing
 * the bit value, and it's value representing the number of times this value was present on the specified position.
 *
 * @param array $data
 *
 * @return array
 */
function getBitValues( array $data ): array {
	$bitValues = [];
	foreach ( $data as $binaryNumber ) {
		// Removes \n char at the end of a string,
		$binaryNumber = trim( $binaryNumber );

		foreach ( str_split( $binaryNumber ) as $position => $bit ) {
			if ( ! array_key_exists( $position, $bitValues ) ) {
				$bitValues[ $position ] = [
					0 => 0,
					1 => 0
				];
			}
			$bitValues[ $position ][ $bit ] += 1;
		}
	}

	return $bitValues;
}

/**
 * Filters binary collection by the MCB / LCB on selected position till only one entry left;
 *
 * @param string $id
 * @param array $data
 * @param bool $useMostCommonBit
 *
 * @return string
 */
function decode( string $id, array $data, bool $useMostCommonBit = true ): string {
	$position = 0;
	while ( sizeof( $data ) != 1 ) {
		$bitValues = getBitValues( $data );

		$mcb = $bitValues[ $position ][1] >= $bitValues[ $position ][0];
		$lcb = ! $mcb;

		$data = filter(
			$data,
			$position,
			(int) $useMostCommonBit ? $mcb : $lcb
		);

		echo "$id -> #$position -> " . (int) $mcb . " [{$bitValues[ $position ][1]} | {$bitValues[ $position ][0]}]\n";
		var_dump( $data );

		$position ++;
	}

	return (string) $data[0];
}

/** @var String[] $data */
$data = explode( "\n", file_get_contents( __DIR__ . "/data.txt" ) );

// Removes the whitespace at the end of a data file from collection.
array_pop( $data );

$oxygen = decode( 'O2', $data );
echo "\n------------------------\n\n";
$carbonDioxide = decode( 'CO2', $data, false );
echo "\n------------------------\n\n";
echo "Oxygen generator rating: " . $oxygen . " -> " . bindec( $oxygen ) . " \n";
echo "CO2 scrubber rating: " . $carbonDioxide . " -> " . bindec( $carbonDioxide ) . "\n";
echo "\n------------------------\n\n";
echo "Life support rating: " . ( bindec( $oxygen ) * bindec( $carbonDioxide ) ) . "\n\n";



