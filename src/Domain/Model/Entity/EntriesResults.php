<?php
namespace Budgetcontrol\SearchEngine\Domain\Model\Entity;

use Budgetcontrol\SearchEngine\Domain\Entry;
use BudgetcontrolLibs\Crypt\Traits\Crypt;
use stdClass;

/**
 * Represents the results of a search query for entries.
 */
final class EntriesResults implements SearchEngineResultsInterface {

    use Crypt;

    private float $amount;
    private bool $confirmed;
    private string $date_time;
    private bool $exclude_from_stats;
    private int $id;
    private int $installment;
    private array $payment_type;
    private bool $planned;
    private bool $transfer;
    private string $type;
    private string $uuid;
    private bool $waranty;
    private int $workspace_id;

    private array $wallet;
    private array $sub_category;
    private array $currency;
    private ?array $payee;

    private ?int $transfer_id;
    private ?string $transfer_relation;
    private ?int $payee_id;
    private ?string $geolocation;
    private ?string $note;
    private ?int $model_id;

    private array $labels = [];
    
    public function __construct(
        array|stdClass $data
    ) {
        $this->key = env('APP_KEY');

        $data = (array) $data;

        $this->uuid = $data['uuid'];
        $this->amount = $data['amount'];
        $this->note = $this->decrypt($data['note']);
        $this->type = $data['type'];
        $this->waranty = (bool) $data['waranty'];
        $this->transfer = (bool) $data['transfer'];
        $this->confirmed = (bool) $data['confirmed'];
        $this->planned = (bool) $data['planned'];
        $this->installment = (int) $data['installment'];
        $this->model_id = $data['model_id'];
        $this->wallet = [
            'name' => $this->decrypt($data['wallet_name']),
        ];
        $this->transfer_id = $data['transfer_id'];
        $this->transfer_relation = $data['transfer_relation'];

        $this->currency = [
            'slug' => $data['currency_slug'],
            'icon' => $data['currency_icon'],
        ];
        $this->sub_category = [
            'slug' => $data['category_slug'],
            'id' => $data['subcategory_id'],
            'category' => [
                'icon' => $data['category_icon'],
            ],
        ];

        $this->payment_type = [
            'id' => $data['payment_type'],
        ];
        $this->payee_id = $data['payee_id'];
        $this->geolocation = $data['geolocation'];
        $this->workspace_id = $data['workspace_id'];
        $this->exclude_from_stats = (bool) $data['exclude_from_stats'];

        if($data['payee_name']) {
            $this->payee = [
                'name' => $data['payee_name'],
                'id' => $data['payee_id'],
            ];
        } else {
            $this->payee = null;
        }   

        $entry = Entry::find($data['id']);
        $labels = $entry->labels;

        $this->labels = $labels->toArray();
        $this->date_time = $data['date_time'];

    }

    public function toArray(): array
    {
        return [
                'uuid' => $this->uuid,
                'date_time' => $this->date_time,
                'amount' => $this->amount,
                'note' => $this->note,
                'type' => $this->type,
                'waranty' => $this->waranty,
                'transfer' => $this->transfer,
                'confirmed' => $this->confirmed,
                'planned' => $this->planned,
                'installment' => $this->installment,
                'model_id' => $this->model_id,
                'wallet' => $this->wallet,
                'transfer_id' => $this->transfer_id,
                'transfer_relation' => $this->transfer_relation,
                'currency' => $this->currency,
                'sub_category' => $this->sub_category,
                'payment_type' => $this->payment_type,
                'payee_id' => $this->payee_id,
                'geolocation' => $this->geolocation,
                'workspace_id' => $this->workspace_id,
                'exclude_from_stats' => $this->exclude_from_stats,
                'payee' => $this->payee,
                'labels' => $this->labels,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function getWallet(): array
    {
        return $this->wallet;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getSubCategory(): array
    {
        return $this->sub_category;
    }

    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    public function getCurrency(): array
    {
        return $this->currency;
    }

    public function getDateTime(): string
    {
        return $this->date_time;
    }

    public function isExcludeFromStats(): bool
    {
        return $this->exclude_from_stats;
    }

    public function getGeolocation(): ?string
    {
        return $this->geolocation;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getInstallment(): int
    {
        return $this->installment;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function getPayee(): ?array
    {
        return $this->payee;
    }

    public function getPayeeId(): ?int
    {
        return $this->payee_id;
    }

    public function getPaymentType(): int
    {
        return $this->payment_type;
    }

    public function isPlanned(): bool
    {
        return $this->planned;
    }

    public function isTransfer(): bool
    {
        return $this->transfer;
    }

    public function getTransferId(): ?int
    {
        return $this->transfer_id;
    }

    public function getTransferRelation(): ?string
    {
        return $this->transfer_relation;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function isWaranty(): bool
    {
        return $this->waranty;
    }

    public function getWorkspaceId(): int
    {
        return $this->workspace_id;
    }

    public function getModelId(): int
    {
        return $this->model_id;
    }


    /**
     * Get the value of label
     *
     * @return ?array
     */
    public function getLabel(): ?array
    {
        return $this->labels;
    }
}
