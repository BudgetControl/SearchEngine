<?php
namespace Budgetcontrol\SearchEngine\Domain\Repository;

use DateTime;
use Budgetcontrol\SearchEngine\Domain\Model\Entity\SearchField;
use Illuminate\Database\Capsule\Manager as DB;

class SearchEngineRepository
{
    private string $query;

    const TABLE = 'entries';

    public function __construct(int $wsid)
    {
        $this->query = 'SELECT sc.name as category_name, a.name as wallet_name, entry.* FROM ' . self::TABLE . ' as entry ';
        $this->query .= 'RIGHT JOIN sub_categories sc ON entry.category_id = sc.id ';
        $this->query .= 'RIGHT JOIN accounts a ON entry.account_id = a.id ';
        $this->query .= "WHERE entry.workspace_id = $wsid ";
    }

    public function findByAccount(array $account): self
    {
        $this->query .= 'AND entry.account_id in (' . implode(',', $account) . ') ';
        return $this;
    }

    public function findByCategory(array $category): self
    {
        $this->query .= 'AND entry.category_id in (' . implode(',', $category) . ') ';
        return $this;
    }

    public function findByType(array $type): self
    {
        $type = array_map(function ($t) {
            return "'$t'";
        }, $type);
        $this->query .= 'AND entry.type in (' . implode(',', $type) . ') ';
        return $this;
    }

    public function findByTags(array $tags): self
    {
        return $this;
    }

    public function findByText(string $text): self
    {
        $text = addslashes($text); // Prevent SQL injection (not the best way to do it, but it's a start
        $this->query .= "AND entry.note LIKE '%$text%' ";
        return $this;
    }

    public function findByPlanned(bool $planned): self
    {
        if ($planned === true) {
            $this->query .= 'AND entry.planned in (0,1) ';
        } else {
            $this->query .= 'AND entry.planned = 0 ';
        }
        
        return $this;
    }

    public function findByDate(array $dateTime): self
    {
        $this->query .= "AND entry.date_time BETWEEN '$dateTime[0]' AND '$dateTime[1]' ";
        return $this;
    }

    public function get(): array
    {   
        $this->query .= 'ORDER BY entry.date_time DESC';
        $result = DB::select($this->query);
        return $result;
    }
}