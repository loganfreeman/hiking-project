<?php

namespace App\Models;

use GrahamCampbell\Credentials\Models\AbstractModel;

class PostCategory extends AbstractModel
{

  /**
   * The table the comments are stored in.
   *
   * @var string
   */
  protected $table = 'post_categories';

  /**
   * The model name.
   *
   * @var string
   */
  public static $name = 'post_category';

  /**
   * The columns to select when displaying an index.
   *
   * @var array
   */
  public static $index = ['id', 'post_id', 'category_id', 'created_at', 'updated_at'];

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


}
