<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    public $table = 'domain_contact';

    public $timestamps = false;

    public $fillable = ['domain_id', 'first_name', 'last_name', 'email', 'confidence'];
}
