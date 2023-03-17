<?php

function jpg_to_json(String $file): String {

	$size   = getimagesize($file);
	$image  = imagecreatefromjpeg($file);
	$pixels = [];

	for ($y = 0; $y < $size[1]; $y++) {
		for ($x = 0; $x < $size[0]; $x++) {

			$rgb = imagecolorat($image, $x, $y);

			$r = ($rgb >> 16) & 0xFF;
			$g = ($rgb >> 8) & 0xFF;
			$b = $rgb & 0xFF;

			$pixels[$y][$x][0] = $r;
			$pixels[$y][$x][1] = $g;
			$pixels[$y][$x][2] = $b;

		}
	}

	return json_encode($pixels);

}

function rgb_to_grayscale(String $json): String {

	$json = json_decode($json, true);
	$grayscale = [];

	for ($y = 0; $y < count($json); $y++) {
		for ($x = 0; $x < count($json[$y]); $x++) {

			$value = (
				0.299 * $json[$y][$x][0] +
				0.587 * $json[$y][$x][1] +
				0.114 * $json[$y][$x][2]
			);
			$grayscale[$y][$x] = $value;

		}
	}

	return json_encode($grayscale);

}

function flatten(Array $pixels): Array {

	$flatten = [];
	for ($y = 0; $y < count($pixels); $y++) {
		for ($x = 0; $x < count($pixels[$y]); $x++) {
			$flatten[] = (int)$pixels[$y][$x] / 255.0;
		}
	}

	return $flatten;

}
