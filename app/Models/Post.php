<?php

/*
 * This file is part of Bootstrap CMS.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\BootstrapCMS\Models;

use GrahamCampbell\BootstrapCMS\Models\Relations\HasManyCommentsTrait;
use GrahamCampbell\BootstrapCMS\Models\Relations\BelongsToManyUsersTrait;
use GrahamCampbell\BootstrapCMS\Models\Relations\PostBelongsToManyCategoriesTrait;
use GrahamCampbell\Credentials\Models\AbstractModel;
use GrahamCampbell\Credentials\Models\Relations\BelongsToUserTrait;
use GrahamCampbell\Credentials\Models\Relations\RevisionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use McCool\LaravelAutoPresenter\HasPresenter;
use Illuminate\Support\Facades\Log;


/**
 * This is the post model class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class Post extends AbstractModel implements HasPresenter
{
    use HasManyCommentsTrait, BelongsToUserTrait, RevisionableTrait, SoftDeletes, BelongsToManyUsersTrait, PostBelongsToManyCategoriesTrait;

    /**
     * The table the posts are stored in.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The model name.
     *
     * @var string
     */
    public static $name = 'post';

    /**
     * The properties on the model that are dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The revisionable columns.
     *
     * @var array
     */
    protected $keepRevisionOf = ['title', 'summary', 'body'];

    /**
     * The columns to select when displaying an index.
     *
     * @var array
     */
    public static $index = ['id', 'title', 'summary'];

    /**
     * The max posts per page when displaying a paginated index.
     *
     * @var int
     */
    public static $paginate = 10;

    /**
     * The columns to order by when displaying an index.
     *
     * @var string
     */
    public static $order = 'id';

    /**
     * The direction to order by when displaying an index.
     *
     * @var string
     */
    public static $sort = 'desc';

    public static $image_upload_directory = 'public/uploads/images/';

    /**
     * The post validation rules.
     *
     * @var array
     */
    public static $rules = [
        'title'   => 'required',
        'summary' => 'required',
        'body'    => 'required',
        'user_id' => 'required',
    ];

    public static function search($term) {
      return static::where('title', 'like', '%' . $term . '%')->get();
    }

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return 'GrahamCampbell\BootstrapCMS\Presenters\PostPresenter';
    }

    /**
     * Before deleting an existing model.
     *
     * @return void
     */
    public function beforeDelete()
    {
        $this->deleteComments();
    }

    public function hasImage()
    {
      Log::debug('Has Image ' . $this->id . ' Count = ' . count(glob(public_path() . "/uploads/images/{$this->id}.*")));
      foreach (glob(public_path(). "/uploads/images/{$this->id}.*") as $file) {
        Log::debug($file);
      }
      return count(glob(public_path() . "/uploads/images/{$this->id}.*")) > 0;
    }

    public function imagePath()
    {
      if($this->hasImage())
      {
        Log::debug('Image Path: ' . substr(glob(public_path() . "/uploads/images/{$this->id}.*")[0], strlen(public_path())));
        return substr(glob(public_path() . "/uploads/images/{$this->id}.*")[0], strlen(public_path()));
      }

    }

}
