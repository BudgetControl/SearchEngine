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
        $this->query = 'SELECT sc.slug as category_slug, sc.id as subcategory_id, sc.category_id as category_id,
         a.name as wallet_name, c.slug as currency_slug, c.icon as currency_icon, p.name as payee_name, p.id as payee_id, 
        cat.icon as category_icon,';
        $this->query .= 'entry.id, entry.date_time, entry.uuid, entry.amount, entry.note, 
        entry.type, entry.waranty, entry.transfer, entry.confirmed, 
        entry.planned, entry.account_id, entry.transfer_id, 
        entry.transfer_relation, entry.currency_id, entry.payment_type, 
        entry.payee_id, entry.geolocation, entry.workspace_id FROM ' . self::TABLE . ' as entry ';
        
        $this->query .= 'INNER JOIN sub_categories sc ON entry.category_id = sc.id ';
        $this->query .= 'LEFT JOIN wallets a ON entry.account_id = a.id ';
        $this->query .= 'LEFT JOIN currencies c ON entry.currency_id = c.id ';
        $this->query .= 'LEFT JOIN payees p ON entry.payee_id = p.id ';
        $this->query .= "INNER JOIN categories cat ON sc.category_id = cat.id ";
        $this->query .= "WHERE entry.workspace_id = $wsid AND entry.deleted_at IS NULL ";

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

    public function findByText(string $text): self
    {
        $text = addslashes($text); // Prevent SQL injection (not the best way to do it, but it's a start
        $this->query .= "AND entry.note LIKE '%$text%' ";
        return $this;
    }

    public function findByPlanned(bool $planned): self
    {
        if ($planned === false) {
            $this->query .= 'AND entry.planned = false ';
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
