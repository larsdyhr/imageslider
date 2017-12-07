<?php

$EM_CONF[$_EXTKEY] = array (
  'title' => 'Dycon Carousel',
  'description' => 'Dycon Carousel. intended to provide a simple to use image slider.',
  'category' => 'plugin',
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '6.2.0-7.99.99',
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
      'DYCON\\DyconCarousel\\' => 'Classes',
    ),
  ),
  'state' => 'stable',
  'uploadfolder' => false,
  'createDirs' => '',
  'version' => '1.1.0',
  'clearcacheonload' => true,
);

