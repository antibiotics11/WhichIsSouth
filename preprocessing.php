<?php

function jpg_to_json(String $file): String {

	$size = getimagesize($file);

	$image = imagecreatefromjpeg($file);
	$pixels = [];

	for ($i = 0; $i < $size[1]; $i++) {
		for ($j = 0; $j < $size[0]; $j++) {

			$rgb = imagecolorat($image, $j, $i);
			$r = ($rgb >> 16) & 0xff;
			$g = ($rgb >> 8) & 0xff;
			$b = $rgb & 0xff;

			$pixels[$i][$j][0] = $r;
			$pixels[$i][$j][1] = $g;
			$pixels[$i][$j][2] = $b;
		
		}
	}

	return json_encode($pixels);

}

function color_to_grayscale(String $json): String {
	
	$json = json_decode($json, true);
	$grayscale = [];

	for ($i = 0; $i < count($json); $i++) {
		for ($j = 0; $j < count($json[$i]); $j++) {

			$value = (
				0.299 * $json[$i][$j][0] +
				0.587 * $json[$i][$j][1] +
				0.114 * $json[$i][$j][2]
			);
			$grayscale[$i][$j] = $value;
		
		}
	}

	return json_encode($grayscale);

}

function read_json_file(String $file): Array {

	$contents = file_get_contents($file);
	return (Array)json_decode($contents);

}

function flatten(Array $arr): Array {

	$flatten = [];
	for ($i = 0; $i < count($arr); $i++) {
		for ($j = 0; $j < count($arr[$i]); $j++) {
			$flatten[] = (int)$arr[$i][$j] / 255.0;
		}
	}

	return $flatten;

}
