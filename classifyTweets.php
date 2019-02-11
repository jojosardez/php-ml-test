<?php

namespace PhpmlTest;

//ini_set('memory_limit', '6048M');

include 'src/classification/SentimentAnalysis.php';

use PhpmlTest\Classification\SentimentAnalysis;
use Phpml\Dataset\CsvDataset;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WordTokenizer;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Dataset\ArrayDataset;
use Phpml\CrossValidation\StratifiedRandomSplit;
use Phpml\Metric\Accuracy;

require __DIR__ . '/vendor/autoload.php';

// Step 1: Load the Dataset
$dataset = new CsvDataset('datasets/clean_tweets.csv',1);
$samples = [];
foreach ($dataset->getSamples() as $sample) {
    $samples[] = $sample[0];    
}

// Step 2: Prepare the Dataset
$vectorizer = new TokenCountVectorizer(new WordTokenizer());
$vectorizer->fit($samples);
$vectorizer->transform($samples);

$tfIdfTransformer = new TfIdfTransformer();
$tfIdfTransformer->fit($samples);
$tfIdfTransformer->transform($samples);

// Step 3: Generate the training/testing Dataset
$dataset = new ArrayDataset($samples, $dataset->getTargets());

$randomSplit = new StratifiedRandomSplit($dataset, 0.1);
$trainingSamples = $randomSplit->getTrainSamples();
$trainingLabels     = $randomSplit->getTrainLabels();
$testSamples = $randomSplit->getTestSamples();
$testLabels      = $randomSplit->getTestLabels();

// Step 4: Train the classifier 
$classifier = new SentimentAnalysis();
$classifier->train($trainingSamples, $trainingLabels);
$predictedLabels = $classifier->predict($testSamples);

// Step 5: Test the classifier accuracy 
echo 'Accuracy: '.Accuracy::score($testLabels, $predictedLabels);