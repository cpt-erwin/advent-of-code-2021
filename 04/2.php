<?php

/** PART 2 **/

// LOAD DATA
$data = explode( "\n", file_get_contents( __DIR__ . "/data.txt" ) );

// SPLIT NUMBERS AND TABLES
$numberSequence = explode( ",", array_shift( $data ) );

$tables = array_chunk( $data, 6 );

// Removes the whitespace at the end of a data file from collection.
array_pop( $tables );

foreach ( $tables as &$table ) {
	array_shift( $table );
	foreach ( $table as &$row ) {
		$row = preg_split( '/\s+/', $row, - 1, PREG_SPLIT_NO_EMPTY );
		foreach ( $row as &$column ) {
			$column = [
				'number'     => (int) $column,
				'inSequence' => false
			];
		}
	}
}

function markAndCheck( array &$table, int $number ) : bool {
	$verticalCheck = [];

	foreach ( $table as $rowIndex => &$row ) {

		$horizontalCheck = 0;
		foreach ( $row as $columnIndex => &$column ) {
			if ( ! array_key_exists( $columnIndex, $verticalCheck ) ) {
				$verticalCheck[ $columnIndex ] = 0;
			}

			if ( $column['number'] === $number ) {
				$column['inSequence'] = true;
			}

			if ( $column['inSequence'] ) {
				$horizontalCheck ++;
				$verticalCheck[ $columnIndex ] ++;
			}
		}

		if ( $horizontalCheck == sizeof( $row ) ) {
			return true;
		}
	}

	if ( in_array( sizeof( $verticalCheck ), $verticalCheck ) ) {
		return true;
	}

	return false;
}

function getTheResult( array &$table, int $number ) : int {
	$sum = 0;
	foreach ( $table as &$row ) {
		foreach ( $row as &$column ) {
			if ( ! $column['inSequence'] ) {
				$sum += $column['number'];
			}
		}
	}
	return $sum * $number;
}

$result = 0;
$lastNumber = 0;

// LOOP NUMBERS AND NOTE TABLES + BINGO CHECK
foreach ( $numberSequence as $turn => $currentNumber ) {
	foreach ( $tables as $index => &$table ) {
		if ( markAndCheck( $table, (int) $currentNumber ) ) {
			if (sizeof($tables) !== 1) {
				unset($tables[$index]);
			} else {
				$lastNumber = (int) $currentNumber;
				$result = getTheResult($table, $lastNumber);
				break 2;
			}
		}
	}
}

// DEBUG
foreach ( $tables as &$table ) {
	echo "<table>";
	foreach ( $table as &$row ) {
		echo "<tr>";
		foreach ( $row as &$column ) {
			if ( $column['inSequence'] ) {
				echo "<th>" . $column['number'] . "</th>";
			} else {
				echo "<td>" . $column['number'] . "</td>";
			}
		}
		echo "<tr>";
	}
	echo "</table>";
	echo "<br>";
}

echo "---------------------------<br><br>";
echo "Last number: $lastNumber<br>";
echo "Result: $result";

