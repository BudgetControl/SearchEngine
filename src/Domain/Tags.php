<?php
namespace Budgetcontrol\SearchEngine\Domain;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $table = 'labels';

    public function entries()
    {
        return $this->belongsToMany(Entry::class, 'entries_labels', 'labels_id', 'entry_id');
    }
}