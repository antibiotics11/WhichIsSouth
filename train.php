#!/usr/bin/env php
<?php

include_once __DIR__ . "/vendor/autoload.php";
include_once __DIR__ . "/classes.php";
include_once __DIR__ . "/preprocessing.php";

use Phpml\Classification\MLPClassifier;
use Phpml\NeuralNetwork\Layer;
use Phpml\NeuralNetwork\Node\Neuron;
use Phpml\NeuralNetwork\ActivationFunction\{PReLU, Sigmoid, HyperbolicTangent};
use Phpml\ModelManager;

$samples_dir = __DIR__ . "/train_data";
$files = scandir($samples_dir);

foreach ($files as $imagefile) {

	if (strcmp($imagefile, ".") === 0 || strcmp($imagefile, "..") === 0) {
		continue;
	}

	$json = jpg_to_json($samples_dir.DIRECTORY_SEPARATOR.$imagefile);
	$pixels = flatten(json_decode(rgb_to_grayscale($json)));

	$class = explode("-", $imagefile)[0];
	$target = -1;

	if (strcmp($class, "sk") === 0) {
		$target = 0;
	} else if (strcmp($class, "nk") === 0) {
		$target = 1;
	} else if (strcmp($class, "us") === 0) {
		$target = 2;
	} else if (strcmp($class, "jp") === 0) {
		$target = 3;
	} else if (strcmp($class, "cn") === 0) {
		$target = 4;
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

printf("Number of prepared samples: %d\r\n", count($samples));

$model = new MLPClassifier(
	inputLayerFeatures: 40 * 30,
	hiddenLayers: [
		new Layer(20, Neuron::class, new Sigmoid),
	],
	classes: [ 0, 1, 2, 3, 4 ],
	iterations: 500,
	activationFunction: new Sigmoid
);

printf("Start training\r\n");
$model->train($samples, $targets);

$manager = new ModelManager;
$manager->saveToFile($model, __DIR__."/trained_model");

printf("Complete\r\n");
