<?php
declare(strict_types=1);

namespace Budgetcontrol\SearchEngine\Domain\Model\Entity;

use BudgetcontrolLibs\Crypt\Traits\Hash;

final class Keywords
{
    use Hash;

    private array $keywords = [];
    private array $score = [];

    public function __construct(array $keyword, array $score = [0.0])
    {
        foreach ($keyword as $k) {
            $this->keywords[] = $this->hash($k);
            $this->score[] = $score[0] ?? 0.0; // Default score if not provided
        }
    }

    public function getKeywords(): array
    {
        return $this->keywords;
    }
    
    public function getScore(): array
    {
        return $this->score;
    }
}
?>