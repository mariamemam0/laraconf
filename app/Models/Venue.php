<?php

namespace App\Models;

use App\Enums\Region;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'region'=> Region::class,
        ];
    }
}
