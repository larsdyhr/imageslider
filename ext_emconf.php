<?php

$EM_CONF[$_EXTKEY] = array (
  'title' => 'Dycon Carousel',
  'description' => 'Dycon Carousel. intended to provide a simple to use image slider. Based on the work of Benjamin Kott',
  'category' => 'templates',
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '6.2.0-8.99.99',
    ),
    'conflicts' => 
    array (
    ),
    'suggests' => 
    array (
    ),
  ),
  'autoload' => 
  array (
    'psr-4' => 
    array (
      'dycon\\DyconCarousel\\' => 'Classes',
    ),
  ),
  'state' => 'stable',
  'uploadfolder' => false,
  'createDirs' => '',
  'clearCacheOnLoad' => 1,
  'version' => '1.0.0',
  'clearcacheonload' => true,
);

