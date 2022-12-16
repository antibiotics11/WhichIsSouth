#!/usr/bin/php
<?php

ini_set("memory_limit", -1);

include_once __DIR__."/vendor/autoload.php";
include_once __DIR__."/preprocessing.php";

const PREDICTION_MODEL = "trained_model_2";

$model = (new \Phpml\ModelManager())->restoreFromFile(PREDICTION_MODEL);


$samples_dir = __DIR__."/train_data/resized/";
$samples = [];
$classes = [ "태극기", "인공기", "성조기", "일장기", "오성홍기" ];

$files = scandir($samples_dir);

foreach ($files as $file) {

	if ($file == "." || $file == "..") {
		continue;
	}

	$json = jpg_to_json($samples_dir.$file);
	$arr = json_decode(color_to_grayscale($json));
	$pixels = flatten($arr);
	
	$result = $model->predict($pixels);

	printf("%s 는 아마도 %s 입니다.\n", $file, $classes[$result]);
}
