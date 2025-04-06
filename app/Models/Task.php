<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['title','completed', 'description', 'start_date', 'end_date'];

    protected function completed(): Attribute
    {
        return Attribute::make(
            get: fn (bool $value) => $value ? 'انجام شده' : 'انجام نشده',
        );
    }

}
