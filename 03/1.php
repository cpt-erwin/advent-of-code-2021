<?php

/** PART 1 **/

/**
 * Creates complement to a provided binary number
 *
 * @param string $binaryNumber
 *
 * @return string
 */
function complement( string $binaryNumber ): string {
	$complement = '';
	foreach ( str_split( $binaryNumber ) as $bit ) {
		$complement .= (int) ! ( (bool) $bit );
	}

	return $complement;
}

/** @var String[] $data */
$data = explode( "\n", file_get_contents( __DIR__ . "/data.txt" ) );

// Removes the whitespace at the end of a data file from collection.
array_pop( $data );

$bitValues = [];
foreach ( $data as $binaryNumber ) {

	// Removes \n char at the end of a string,
	$binaryNumber = trim( $binaryNumber );

	foreach ( str_split( $binaryNumber ) as $position => $bit ) {
		echo $bit;
		if ( ! array_key_exists( $position, $bitValues ) ) {
			$bitValues[ $position ] = 0;
		}
		$bitValues[ $position ] += intval( $bit );
	}
	echo "\n";
}

echo "------------------------\n";

$result = '';
foreach ( $bitValues as $position => $bitValue ) {
	echo $position . "\t" . $bitValue . "\n";
	$result .= (int) ( $bitValue > ( sizeof( $data ) - $bitValue ) );
}

echo "------------------------\n";

echo 'Data count: ' . sizeof( $data ) . "\n";
echo 'Gamma rate: ' . $result . ' -> ' . bindec( $result ) . "\n";
echo 'Epsilon rate: ' . complement( $result ) . ' -> ' . bindec( complement( $result ) ) . "\n";
echo 'Power consumption: ' . ( bindec( $result ) * bindec( complement( $result ) ) );
