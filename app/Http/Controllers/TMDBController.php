<?php

namespace GrahamCampbell\BootstrapCMS\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use GrahamCampbell\BootstrapCMS\Http\Requests;

use Illuminate\Support\Facades\Log;

use GrahamCampbell\BootstrapCMS\API\TMDB;

class TMDBController extends AbstractController {

  private $tmdb;

  public function __construct(TMDB $tmdb)
  {
    $this->tmdb = $tmdb;
  }

  public function search()
  {
    return $this->tmdb->search(Input::get('q'));
  }

  public function suggestions($tmdbID, $mediaType)
  {
    return $this->tmdb->suggestions($mediaType, $tmdbID);
  }

  public function trending()
  {
    return $this->tmdb->trending();
  }

  public function upcoming()
  {
    return $this->tmdb->upcoming();
  }
}
