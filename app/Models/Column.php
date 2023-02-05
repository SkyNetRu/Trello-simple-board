<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    protected $guarded = ['id'];

    public function cards()
    {
        return $this->hasMany(Card::class)->orderBy('order');
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($column) {
            $column->cards()->delete();
        });
    }
}
