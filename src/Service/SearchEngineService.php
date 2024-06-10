<?php

namespace Budgetcontrol\SearchEngine\Service;

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

        if (!empty($this->searchField->getTags())) {
            $repository->findByTags($this->searchField->getTags());
        }

        if ($this->searchField->getText() !== null) {
            $repository->findByText($this->searchField->getText());
        }

        if ($this->searchField->isPlanned() === true) {
            $repository->findByPlanned($this->searchField->isPlanned());
        }

        if ($this->searchField->getDate() !== null) {
            $repository->findByDate($this->searchField->getDate());
        }

        return $repository->get();
    }
}
