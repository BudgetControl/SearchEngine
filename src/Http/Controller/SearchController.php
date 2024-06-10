<?php
namespace Budgetcontrol\SearchEngine\Http\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Budgetcontrol\SearchEngine\Service\SearchEngineService;
use Budgetcontrol\SearchEngine\Domain\Model\Entity\SearchField;
use Illuminate\Support\Facades\Log;

class SearchController {

    public function find(Request $request, Response $response, $argv): Response
    {
        $body = $request->getParsedBody();
        $wsid = (int) $argv['wsid'];

        if(empty($wsid)) {
            Log::error('Missing workspace');
            return response(['error' => 'Missing workspace'], 400);
        }

        $account = $body['account'];
        $category = $body['category'];
        $type = $body['type'];
        $tags = $body['tags'];
        $text = $body['text'];
        $planned = $body['planned'];
        $month = $body['month'];
        $year = $body['year'];

        $searchField = new SearchField($account, $category, $type, $tags, $text, $planned, $month, $year);

        try {
            $searchEngineService = new SearchEngineService($searchField, $wsid);
            $result = $searchEngineService->find();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response(['error' => 'An error occurred'], 400);
        
        }

        return response($result, 200);
    }
}