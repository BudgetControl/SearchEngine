<?php

namespace Budgetcontrol\SearchEngine\Service;

use Budgetcontrol\SearchEngine\Domain\Model\Entity\EntriesResults;
use DateTime;
use Budgetcontrol\SearchEngine\Domain\Model\Entity\SearchField;
use Budgetcontrol\SearchEngine\Domain\Repository\SearchEngineRepository;

class SearchEngineService
{

    private SearchField $searchField;
    private int $wsid;

    public function __construct(SearchField $searchField, int $wsid)
    {
        $this->searchField = $searchField;
        $this->wsid = $wsid;
    }

    public function find(): array
    {
        $results = [];
        $repository = new SearchEngineRepository($this->wsid);

        if (!empty($this->searchField->getAccount())) {
            $repository->findByAccount($this->searchField->getAccount());
        }

        if (!empty($this->searchField->getCategory())) {
            $repository->findByCategory($this->searchField->getCategory());
        }

        if (!empty($this->searchField->getType())) {
            $repository->findByType($this->searchField->getType());
        }

        if ($this->searchField->getText() !== null) {
            $repository->findByText($this->searchField->getText());
        }

        $repository->findByPlanned($this->searchField->isPlanned());

        if ($this->searchField->getDate() !== null) {
            $repository->findByDate($this->searchField->getDate());
        }

        foreach ($repository->get() as $result) {
            $data = new EntriesResults($result);

            // filter by tag
            if(!empty($this->searchField->getTags())) {
                $tags = $data->getLabel();
                foreach($tags as $tag) {
                    if(in_array($tag['id'], $this->searchField->getTags())) {
                        $results[] = $data->toArray();
                    }
                }

            } else {
                $results[] = $data->toArray();
            }
        }


        return $results;
    }
    
}
