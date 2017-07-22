<?php

namespace GrahamCampbell\BootstrapCMS\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['post_id', 'user_id'];
}
