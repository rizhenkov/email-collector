<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Domain;

class UploadList extends Model
{
    use HasFactory;

    public $table = 'upload_list';

    public $fillable = ['name'];

    public $timestamps = false;

    public $visible = ['id', 'name', 'upload_time', 'domains'];

    public $attach = ['domains'];

    public function domains()
    {
        return $this->belongsToMany(Domain::class, 'upload_domain');
    }
}
