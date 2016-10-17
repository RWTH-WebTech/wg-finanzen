<?php
require_once(__DIR__.'/../vendor/autoload.php');

$app = new \WGFinanzen\Application();

$examplePage = new \WGFinanzen\Page\Example();
$exampleNavigation = new \WGFinanzen\NavigationItem('Example', 'example');

$login = new \WGFinanzen\Page\Login($app->getSessionManager());
$loginNavigation = new \WGFinanzen\NavigationItem('Login', 'login');
$logout = new \WGFinanzen\Page\Logout($app->getSessionManager());
$logoutNavigation = new \WGFinanzen\NavigationItem('Logout', 'logout');
$addMateNavigation = new \WGFinanzen\NavigationItem('Neuer Mitbewohner', 'addMate');
$addMate = new \WGFinanzen\Page\AddFlatMate($app->getData());
$addMateNavigation = new \WGFinanzen\NavigationItem('Neuer Mitbewohner', 'addMate');
$addPurchase = new \WGFinanzen\Page\AddPurchase($app->getData());
$addPurchaseNavigation = new \WGFinanzen\NavigationItem('Neuer Kauf', 'addPurchase');
$showPurchases = new \WGFinanzen\Page\ShowPurchases($app->getData());
$showPurchasesNavigation = new \WGFinanzen\NavigationItem('Käufe', 'showPurchases');
$showBalances = new \WGFinanzen\Page\Balances($app->getData());
$showBalancesNavigation = new \WGFinanzen\NavigationItem('Bilanzen', 'balances');
$readShoppingList = new \WGFinanzen\Page\ReadShoppingList($app->getData());


$app->addPage('example', $examplePage);
$app->addPage('addMate', $addMate);
$app->addPage('addPurchase', $addPurchase);
$app->addPage('showPurchases', $showPurchases);
$app->addPage('balances', $showBalances);
$app->addPage('login', $login);
$app->addPage('logout', $logout);
$app->addPage('readShoppingList', $readShoppingList);
$app->addNavigationItem($loginNavigation);
$app->addNavigationItem($logoutNavigation);
$app->addNavigationItem($exampleNavigation);
$app->addNavigationItem($addMateNavigation);
$app->addNavigationItem($addPurchaseNavigation);
$app->addNavigationItem($showPurchasesNavigation);
$app->addNavigationItem($showBalancesNavigation);

$app->run();