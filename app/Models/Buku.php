<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Gallery;

class Buku extends Model
{
    protected $table = 'buku';
    protected $fillable = ['id', 'judul', 'harga', 'tgl_terbit', 'created_at', 'updated_at', 'filename', 'filepath'];
    protected $dates = ['tgl_terbit'];

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }
       
}