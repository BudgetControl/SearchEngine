<?php
namespace Budgetcontrol\SearchEngine\Domain;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $table = 'entries';

    public function labels()
    {
        return $this->belongsToMany(Tags::class, 'entry_labels', 'entry_id', 'labels_id');
    }
}