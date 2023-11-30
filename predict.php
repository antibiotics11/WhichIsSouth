#!/usr/bin/env php
<?php

include_once __DIR__ . "/vendor/autoload.php";
include_once __DIR__ . "/flag_classes.php";
include_once __DIR__ . "/preprocessing.php";

$manager = new Phpml\ModelManager;
$model = $manager->restoreFromFile(__DIR__ . "/trained_model");

$samples = [];

$samples_dir = __DIR__ . "/test_data";
$files = scandir($samples_dir);
foreach ($files as $image_file) {

  if (strcmp($image_file, ".") == 0 || strcmp($image_file, "..") == 0) {
    continue;
  }

  $json = jpg_to_json($samples_dir . DIRECTORY_SEPARATOR . $image_file);
  $pixels = flatten(json_decode(rgb_to_grayscale($json)));

  $result = $model->predict($pixels);
  $class = FLAG_CLASSES[$result];

  printf("%s seems to be the '%s' of %s.\r\n", $image_file, $class[0], $class[1]);

}
