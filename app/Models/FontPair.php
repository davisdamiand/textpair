<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FontPair extends Model
{
    protected $table = 'font_pairs';
    protected $fillable = [
        'name',
        'is_dark_mode',
        'same_font_allowed'
    ];

    public function heading()
    {
        return $this->hasOne(Heading::class);
    }

    public function body()
    {
        return $this->hasOne(Body::class);
    }
}
