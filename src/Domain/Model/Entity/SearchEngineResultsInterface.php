<?php
namespace Budgetcontrol\SearchEngine\Domain\Model\Entity;

interface SearchEngineResultsInterface {
    
    public function toArray(): array;

    public function toJson(): string;

}