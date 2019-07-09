<?php

namespace App;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
	use Searchable;
    protected $fillable = ['name', 'email', 'photo'];

}
