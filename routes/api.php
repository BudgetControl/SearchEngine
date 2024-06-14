<?php
/**
 *  application apps
 */


 $app->post('/find/{wsid}', \Budgetcontrol\SearchEngine\Http\Controller\SearchController::class. ':find');
$app->get('/monitor', \Budgetcontrol\SearchEngine\Http\Controller\Controller::class . ':monitor');
