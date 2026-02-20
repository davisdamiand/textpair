<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Body extends Model
{
    protected $table = 'font_bodies';
    protected $fillable = [
        'font_pair_id',
        'name',
        'weight',
        'base_size',
        'line_height',
        'letter_spacing',
        'paragraph_width'
    ];

    public function fontPair()
    {
        return $this->belongsTo(FontPair::class);
    }
}
