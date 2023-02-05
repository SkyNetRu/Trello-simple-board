<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $guarded = ['id'];
    protected $visible = ['id', 'title', 'desc', 'column_id', 'order'];

    public function column()
    {
        return $this->belongsTo(Column::class);
    }
}
