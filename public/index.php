<?php
require_once(__DIR__.'/../src/Application.php');
require_once(__DIR__.'/../src/NavigationItem.php');
require_once(__DIR__.'/../src/Page/Example.php');

$app = new \WGFinanzen\Application();

$examplePage = new \WGFinanzen\Page\Example();
$exampleNavigation = new \WGFinanzen\NavigationItem('Example', 'example');

$app->addPage('example', $examplePage);
$app->addNavigationItem($exampleNavigation);

$app->run();