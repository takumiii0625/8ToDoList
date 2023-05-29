<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\FolderController;

class Folder extends Model
{
    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }
}
