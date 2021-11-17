<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    public $table = 'domain';

    public $timestamps = false;

    public $fillable = ['domain_name'];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
