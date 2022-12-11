#!/usr/bin/php
<?php

const TEST_FILE_DIR = __DIR__."/prediction_test_data/";
const PREDICTION_MODEL = "trained_model_1";

include_once __DIR__."/vendor/autoload.php";
include_once __DIR__."/jpg_utils.php";

use Phpml\ModelManager;

ini_set("memory_limit", -1);

$model = (new ModelManager())->restoreFromFile(PREDICTION_MODEL);
$test_files = scandir(TEST_FILE_DIR);

foreach ($test_files as $file) {

	if ($file == "." || $file == ".." || $file == "resized") {
		continue;
	}

	$grayscale_arr = read_json_as_array(TEST_FILE_DIR.$file);
	$grayscale_pixels = flatten_grayscale_pixels($grayscale_arr);

	$result = $model->predict($grayscale_pixels);

	if (!$result) {
		printf("%s는 아마도 태극기입니다.\n", $file);
	} else {
		printf("%s는 아마도 인공기입니다.\n", $file);
	}

}

