<?php

/** PART 1 **/

// LOAD DATA
$data = explode( ",", trim( file_get_contents( __DIR__ . "/data.txt" ) ) );

$data = array_count_values( $data );
ksort( $data );

echo "<pre>";
var_dump($data);
echo "</pre>";

$result = [];
for ($i = 0; $i <= max(array_keys($data)); $i++) {
	$result[$i] = 0;
	foreach ($data as $xPosition => $submarines) {
		$result[$i] += abs($i - $xPosition) * $submarines;
	}
}

echo "<pre>";
var_dump($result);
echo "</pre>";

echo "<br>";
echo "<br>";
echo "-----------------------";
echo "<br>";
echo "<br>";

echo "Result: " . min($result);
