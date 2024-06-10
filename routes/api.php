<?php
/**
 *  application apps
 */


 $app->post('/find/{wsid}', \Budgetcontrol\SearchEngine\Http\Controller\SearchController::class. ':find');