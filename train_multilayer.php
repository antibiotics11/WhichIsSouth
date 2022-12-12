#!/usr/bin/php
<?php

include_once __DIR__."/vendor/autoload.php";
include_once __DIR__."/jpg_utils.php";

use Phpml\Classification\MLPClassifier;
use Phpml\NeuralNetwork\ActivationFunction\{PReLU, Sigmoid};
use Phpml\NeuralNetwork\Layer;
use Phpml\NeuralNetwork\Node\Neuron;
use Phpml\ModelManager;

ini_set("memory_limit", -1);

$samples = [];
$targets = [];

$sample_dir = __DIR__."/train_data/";
for ($i = 1; $i <= 10; $i++) {

	$grayscale_arr = read_json_as_array($sample_dir.$i.".json");
	$grayscale_pixels = flatten_grayscale_pixels($grayscale_arr);

	$target = ($i > 5) ? 1 : 0;

	$samples[] = $grayscale_pixels;
	$targets[] = $target;

}

printf("samples: %d\n", count($samples));
printf("targets: [ ");
for ($i = 0; $i < count($targets); $i++) {
	printf("%d, ", $targets[$i]);
}
printf("]\n");

$model = new MLPClassifier(
	1200,
	[
		new Layer(2, Neuron::class, new PReLU),
		new Layer(2, Neuron::class, new Sigmoid)
	],
	[0, 1]
);

$model->train($samples, $targets);

(new ModelManager)->saveToFile($model, __DIR__."/trained_model_2");

