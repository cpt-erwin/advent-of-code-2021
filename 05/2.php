<?php

/** PART 2 **/

// DEBUG
const PRINT_TABLE = false;

// POINTS
const A = 0;
const B = 1;

// COORDINATES
const X = 0;
const Y = 1;

// LOAD DATA
$data = explode( "\n", file_get_contents( __DIR__ . "/data.txt" ) );

// Removes the whitespace at the end of a data file from collection.
array_pop( $data );

// MAP SIZE
$size = [
	X => 0,
	Y => 0
];

// FORMAT DATA
foreach ( $data as &$points ) {
	$points = explode( " -> ", $points );
	foreach ( $points as &$point ) {
		$point = explode( ",", $point );
		foreach ( $point as &$coordinate ) {
			$coordinate = (int) trim( $coordinate );
		}
		$size[ X ] = $point[ X ] > $size[ X ] ? $point[ X ] : $size[ X ];
		$size[ Y ] = $point[ Y ] > $size[ Y ] ? $point[ Y ] : $size[ Y ];
	}
}

// INIT MAP
$map = [];
for ( $y = 0; $y <= $size[ X ]; $y ++ ) {
	$map[ $y ] = [];
	for ( $j = 0; $j <= $size[ Y ]; $j ++ ) {
		$map[ $y ][ $j ] = 0;
	}
}

function isLineVertical( array $points ): bool {
	return $points[ A ][ Y ] === $points[ B ][ Y ];
}

function isLineHorizontal( array $points ): bool {
	return $points[ A ][ X ] === $points[ B ][ X ];
}

function isLineDiagonal( array $points ): bool {
	$x = $points[ A ][ X ] - $points[ B ][ X ];
	$y = $points[ A ][ Y ] - $points[ B ][ Y ];

	return abs( $x ) === abs( $y );
}

function isLineStraight( array $points ): bool {
	return isLineHorizontal( $points ) || isLineVertical( $points ) || isLineDiagonal( $points );
}

function drawLine( array $points, array &$map ) {

	echo "<pre>";
	var_dump( "[{$points[ A ][ X ]}, {$points[ A ][ Y ]}] -> [{$points[ B ][ X ]}, {$points[ B ][ Y ]}]" );
	echo "</pre>";

	if ( isLineHorizontal( $points ) ) {
		$start = min( $points[ A ][ Y ], $points[ B ][ Y ] );
		$end   = max( $points[ A ][ Y ], $points[ B ][ Y ] );

		for ( $i = $start; $i <= $end; $i ++ ) {
			$map[ $i ][ $points[ A ][ X ] ] ++;
		}
	}

	if ( isLineVertical( $points ) ) {
		$start = min( $points[ A ][ X ], $points[ B ][ X ] );
		$end   = max( $points[ A ][ X ], $points[ B ][ X ] );

		for ( $i = $start; $i <= $end; $i ++ ) {
			$map[ $points[ A ][ Y ] ][ $i ] ++;
		}
	}

	if ( isLineDiagonal( $points ) ) {
		$dir = [
			X => $points[ A ][ X ] <=> $points[ B ][ X ],
			Y => $points[ A ][ Y ] <=> $points[ B ][ Y ]
		];

		$currentPoint = $points[ A ];
		$map[ $currentPoint[ Y ] ][ $currentPoint[ X ] ] ++;
		do {
			$currentPoint[ X ] -= $dir[ X ];
			$currentPoint[ Y ] -= $dir[ Y ];

			$map[ $currentPoint[ Y ] ][ $currentPoint[ X ] ] ++;
		} while ( $currentPoint[ X ] !== $points[ B ][ X ] && $currentPoint[ Y ] !== $points[ B ][ Y ] );
	}
}

// MARK LINES
foreach ( $data as &$points ) {
	if ( isLineStraight( $points ) ) {
		drawLine( $points, $map );
	}
}

// PRINT MAP & FIND CROSSOVERS
$result = 0;

if ( PRINT_TABLE ) {
	echo "<table>";
	foreach ( $map as $row ) {
		echo "<tr>";
		foreach ( $row as $col ) {
			// FIND CROSSOVERS
			if ( $col >= 2 ) {
				$result ++;
			}
			echo "<td>" . ( $col == 0 ? '.' : $col ) . "</td>";
		}
		echo "<tr>";
	}
	echo "</table>";
} else {
	foreach ( $map as $row ) {
		$values = array_count_values( $row );
		unset( $values[0], $values[1] );
		$result += array_sum( $values );
	}
}

echo "<br>";
echo "<br>";
echo "-----------------------";
echo "<br>";
echo "<br>";

echo "Result: $result";
