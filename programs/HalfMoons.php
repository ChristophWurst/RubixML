<?php

include dirname(__DIR__) . '/vendor/autoload.php';

use Rubix\ML\Datasets\Labeled;
use Rubix\ML\CrossValidation\KFold;
use Rubix\ML\Reports\PredictionSpeed;
use Rubix\ML\Classifiers\KDNeighbors;
use Rubix\ML\Other\Generators\HalfMoon;
use Rubix\ML\Kernels\Distance\Euclidean;
use Rubix\ML\CrossValidation\Metrics\MCC;
use Rubix\ML\Other\Generators\Agglomerate;
use Rubix\ML\Classifiers\KNearestNeighbors;
use Rubix\ML\Transformers\ZScaleStandardizer;
use League\Csv\Reader;

echo '╔═════════════════════════════════════════════════════╗' . "\n";
echo '║                                                     ║' . "\n";
echo '║ Half Moon classifier using K Nearest Neighbors      ║' . "\n";
echo '║                                                     ║' . "\n";
echo '╚═════════════════════════════════════════════════════╝' . "\n";

echo "\n";

$generator = new Agglomerate([
    'left' => new HalfMoon([-1, 0], 1.5, 90.0, 0.5),
    'right' => new HalfMoon([1, 0], 1.5, 270.0, 0.5),
], [
    5, 6,
]);

$dataset = $generator->generate(2000);

$estimator1 = new KDNeighbors(3, 10, new Euclidean());
$estimator2 = new KNearestNeighbors(3, new Euclidean());

$validator = new KFold(10);

$report = new PredictionSpeed();

list($training, $testing) = $dataset->randomize()->split(0.8);

echo 'KD Neighbors:' . "\n";

echo "\n";

var_dump($validator->test($estimator1, $dataset, new MCC()));

$estimator1->train($training);

var_dump($report->generate($estimator1, $testing));

echo "\n";

echo 'K Nearest Neighbors:' . "\n";

echo "\n";

var_dump($validator->test($estimator2, $dataset, new MCC()));

$estimator2->train($training);

var_dump($report->generate($estimator2, $testing));