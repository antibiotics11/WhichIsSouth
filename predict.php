#!/usr/bin/env php
<?php

include_once __DIR__ . "/vendor/autoload.php";
include_once __DIR__ . "/classes.php";
include_once __DIR__ . "/preprocessing.php";

const PREDICTION_MODEL = "trained_model";

$manager = new Phpml\ModelManager;
$model = $manager->restoreFromFile(PREDICTION_MODEL);

$samples = [];

$samples_dir = __DIR__ . "/test_data";
$files = scandir($samples_dir);
foreach ($files as $imagefile) {

	if (strcmp($imagefile, ".") === 0 || strcmp($imagefile, "..") === 0) {
		continue;
	}

	$json = jpg_to_json($samples_dir.DIRECTORY_SEPARATOR.$imagefile);
	$pixels = flatten(json_decode(rgb_to_grayscale($json)));

	$result = $model->predict($pixels);
	$class = FLAG_CLASSES[$result];

	printf("%s is %s, %s\r\n", $imagefile, $class[0], $class[1]);

}
