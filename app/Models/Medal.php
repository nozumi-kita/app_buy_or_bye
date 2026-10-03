<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medal extends Model
{
    use HasFactory;

    public function iconUrl()
    {
        return asset("images/medals/{$this->icon_key}.svg");
    }
}
