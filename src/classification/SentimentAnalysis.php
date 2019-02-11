<?php
namespace PhpmlTest\Classification;
use Phpml\Classification\NaiveBayes;

/**
 * Class SentimentAnalysis
 * @package PhpmlTest\Classification
 */
class SentimentAnalysis { 
    protected $classifier;

    public function __construct()
    {
        $this->classifier = new NaiveBayes();
    }

    public function train($samples, $labels)
    {
        $this->classifier->train($samples, $labels);
    }

    public function predict($samples)
    {
        return $this->classifier->predict($samples);
    }
}