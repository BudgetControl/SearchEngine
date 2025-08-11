<?php
namespace Budgetcontrol\SearchEngine\Http\Controller;

use Budgetcontrol\SearchEngine\Domain\Model\Entity\Keywords;
use DonatelloZa\RakePlus\RakePlus;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Budgetcontrol\SearchEngine\Service\SearchEngineService;
use Budgetcontrol\SearchEngine\Domain\Model\Entity\SearchField;
use Budgetcontrol\SearchEngine\Traits\CacheablePagination;

class SearchController {
    
    use CacheablePagination;

    public function find(Request $request, Response $response, $argv): Response
    {
        $body = $request->getParsedBody();
        $queryParams = $request->getQueryParams();
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
        $date = $body['date_time'];
        
        // Parametri di paginazione da GET
        $page = isset($queryParams['page']) ? (int) $queryParams['page'] : 1;
        $perPage = isset($queryParams['per_page']) ? (int) $queryParams['per_page'] : 30;
        $perPage = min($perPage, 30); // Limite massimo di 30 elementi

        $keywords = RakePlus::create($text);
        $searchField = new SearchField($account, $category, $type, $tags, new Keywords($keywords->get()), $planned, $date );

        try {
            // Parametri per la chiave cache
            $searchParams = compact('account', 'category', 'type', 'tags', 'text', 'planned', 'date');
            
            // Utilizza il caching paginato
            $paginatedResponse = $this->getCachedPaginatedResults(
                function() use ($searchField, $wsid) {
                    $searchEngineService = new SearchEngineService($searchField, $wsid);
                    return $searchEngineService->find();
                },
                $searchParams,
                $wsid,
                $page,
                $perPage
            );
            
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response(['error' => 'An error occurred'], 400);
        }

        return response($paginatedResponse, 200);
    }
}