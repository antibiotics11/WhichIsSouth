#!/usr/bin/php
<?php

include_once __DIR__."/vendor/autoload.php";
include_once __DIR__."/preprocessing.php";

use Phpml\Classification\MLPClassifier;
use Phpml\NeuralNetwork\Layer;
use Phpml\NeuralNetwork\Node\Neuron;
use Phpml\NeuralNetwork\ActivationFunction\{PReLU, Sigmoid, HyperbolicTangent};
use Phpml\ModelManager;

ini_set("memory_limit", -1);

$samples_dir = __DIR__."/train_data/resized/";
$train_data = [];

$files = scandir($samples_dir);

foreach ($files as $file) {

	if ($file == "." || $file == "..") {
		continue;
	}

	$json = jpg_to_json($samples_dir.$file);
	$arr = json_decode(color_to_grayscale($json));
	$pixels = flatten($arr);
	
	$class = explode("-", $file)[0];
	$target = -1;
	
	if ($class == "sk") {
		$target = 0;
	} else if ($class == "nk") {
		$target = 1;
	} else if ($class == "us") {
		$target = 2;
	}
	
	$train_data[] = [ $pixels, $target ];

}

shuffle($train_data);

$samples = [];
$targets = [];

for ($i = 0; $i < count($train_data); $i++) {
	
	$samples[] = $train_data[$i][0];
	$targets[] = $train_data[$i][1];
	
}

print_r($targets);

$model = new MLPClassifier(
	1200,
	[
		new Layer(3, Neuron::class, new Sigmoid)
	],
	[0, 1, 2],
	35
);

printf("Start training\n");

$model->train($samples, $targets);

(new ModelManager)->saveToFile($model, __DIR__."/trained_model_2");

printf("Complete\n");
