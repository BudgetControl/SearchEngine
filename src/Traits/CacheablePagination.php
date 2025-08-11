<?php
namespace Budgetcontrol\SearchEngine\Traits;

use Illuminate\Support\Facades\Cache;

trait CacheablePagination
{
    private int $cacheExpiration = 3600; // 1 ora

    /**
     * Genera una chiave cache unica basata sui parametri di ricerca
     */
    private function generateCacheKey(array $searchParams, int $wsid): string
    {
        $key = md5(serialize($searchParams) . $wsid);
        return "search_results_{$wsid}_{$key}";
    }

    /**
     * Recupera i risultati dalla cache o esegue la ricerca
     */
    public function getCachedPaginatedResults(callable $searchCallback, array $searchParams, int $wsid, int $page, int $perPage): array
    {
        $cacheKey = $this->generateCacheKey($searchParams, $wsid);
        
        // Verifica se i risultati completi sono in cache
        $cachedResults = Cache::get($cacheKey);
        
        if ($cachedResults === null) {
            // Esegui la ricerca e salva in cache
            $allResults = $searchCallback();
            $cachedResults = [
                'data' => $allResults,
                'total' => count($allResults),
                'cached_at' => time()
            ];
            
            Cache::put($cacheKey, $cachedResults, $this->cacheExpiration);
        }
        
        // Calcola paginazione
        $total = $cachedResults['total'];
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;
        
        // Estrai solo la pagina richiesta
        $pageData = array_slice($cachedResults['data'], $offset, $perPage);
        
        return [
            'data' => $pageData,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => $totalPages,
                'has_next_page' => $page < $totalPages,
                'has_prev_page' => $page > 1
            ],
            'cached' => true
        ];
    }

    /**
     * Pulisce la cache per un workspace specifico
     */
    public function clearSearchCache(int $wsid): void
    {
        $pattern = "search_results_{$wsid}_*";
        // Nota: questo richiede una implementazione specifica del driver cache
        Cache::flush(); // In alternativa, potresti implementare una logica più specifica
    }
}
