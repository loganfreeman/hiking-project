<?php

namespace App\Models;

use GrahamCampbell\Credentials\Models\AbstractModel;
use App\Models\Relations\CategoryBelongsToManyPostsTrait;

class Category extends AbstractModel
{

  use CategoryBelongsToManyPostsTrait;
  /**
   * The table the comments are stored in.
   *
   * @var string
   */
  protected $table = 'categories';

  /**
   * The model name.
   *
   * @var string
   */
  public static $name = 'category';

  /**
   * The columns to select when displaying an index.
   *
   * @var array
   */
  public static $index = ['id', 'name', 'description', 'created_at', 'updated_at'];

  /**
   * The columns to order by when displaying an index.
   *
   * @var string
   */
  public static $order = 'name';

  /**
   * The direction to order by when displaying an index.
   *
   * @var string
   */
  public static $sort = 'desc';

  /**
   * The comment validation rules.
   *
   * @var array
   */
  public static $rules = [
      'name'    => 'required',
      'description' => 'required',
  ];

}
