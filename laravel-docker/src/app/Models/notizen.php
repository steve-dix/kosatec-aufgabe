<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class notizen extends Model
{
	//use HasFactory;
	protected $table = 'notizens';
	protected $fillable = ['titel','inhalt','erstellungsdatum','wichtig'];
}
