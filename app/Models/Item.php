<?php

namespace GrahamCampbell\BootstrapCMS\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model {

  // Uncomment this if you are using Laravel Scout.
  //use Searchable;

  public $timestamps = false;

  protected $fillable = [
    'tmdb_id',
    'title',
    'original_title',
    'poster',
    'media_type',
    'rating',
    'released',
    'created_at',
    'genre',
  ];

  public function episodes()
  {
    return $this->hasMany('GrahamCampbell\BootstrapCMS\Models\Episode', 'tmdb_id', 'tmdb_id');
  }

  public function latestEpisode()
  {
    return $this->hasOne('GrahamCampbell\BootstrapCMS\Models\Episode', 'tmdb_id', 'tmdb_id')
      ->orderBy('id', 'desc')
      ->where('seen', true)
      ->latest();
  }
}
