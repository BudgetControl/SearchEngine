<?php
namespace Budgetcontrol\SearchEngine\Domain\Model\Entity;

use DateTime;
use DateTimeZone;

final class SearchField {

    private ?array $account;
    private ?array $category;
    private ?array $type;
    private ?array $tags;
    private ?string $text;
    private bool $planned;
    private ?array $dateTime;

    public function __construct(?array $account, ?array $category, ?array $type, ?array $tags, ?string $text, ?bool $planned, ?array $dateTime)
    {
        $this->account = $account;
        $this->category = $category;
        $this->type = $type;
        $this->tags = $tags;
        $this->text = $text;
        $this->planned = $planned ?? false;
        
        if(!empty($dateTime)) {
            $this->dateTime = $this->adjustDateTime($dateTime);
        } else {
            $this->dateTime = null;
        }
    }

    public function getAccount(): ?array
    {
        return $this->account ?? null;
    }

    public function getCategory(): ?array
    {
        return $this->category ?? null;
    }

    public function getType(): ?array
    {
        return $this->type ?? null;
    }

    public function getTags(): ?array
    {
        return $this->tags ?? null;
    }

    public function getText(): ?string
    {
        return $this->text ?? null;
    }

    public function isPlanned(): bool
    {
        return $this->planned;
    }

    /**
     * Get the value of dateTime
     *
     * @return ?array
     */
    public function getdate(): ?array
    {
        return $this->dateTime;
    }

  
    /**
     * Adjusts the given array of date and time values.
     *
     * @param array $dateTimeArray The array of date and time values to adjust.
     * @return array The adjusted array of date and time values.
     */
    private function adjustDateTime(array $dateTimeArray): array
    {
        $serverTimeZone = new DateTimeZone(date_default_timezone_get());
    
        return array_map(function($dateTime) use ($serverTimeZone) {
            $date = new DateTime($dateTime);
            $date->setTimezone($serverTimeZone);
            return $date->format('Y-m-d\TH:i:s.u\Z');
        }, $dateTimeArray);
    }
}