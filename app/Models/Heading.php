<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Heading extends Model
{
    protected $table = 'font_headings';
    protected $fillable = [
        'font_pair_id',
        'name',
        'letter_spacing',
    ];

    public function fontPair()
    {
        return $this->belongsTo(FontPair::class);
    }
}
