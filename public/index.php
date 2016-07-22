<?php
require_once(__DIR__.'/../src/Application.php');
require_once(__DIR__.'/../src/NavigationItem.php');
require_once(__DIR__.'/../src/Page/Example.php');
require_once(__DIR__.'/../src/Page/AddFlatMate.php');

$app = new \WGFinanzen\Application();

$examplePage = new \WGFinanzen\Page\Example();
$exampleNavigation = new \WGFinanzen\NavigationItem('Example', 'example');

$addMate = new \WGFinanzen\Page\AddFlatMate($app->getData());
$addMateNavigation = new \WGFinanzen\NavigationItem('Neuer Mitbewohner', 'addMate');


$app->addPage('example', $examplePage);
$app->addPage('addMate', $addMate);
$app->addNavigationItem($exampleNavigation);
$app->addNavigationItem($addMateNavigation);

$app->run();