<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use GrahamCampbell\Credentials\Models\AbstractModel;

class EventSignups extends AbstractModel
{
    protected $fillable = ['event_id', 'user_id'];

    /**
     * The table the comments are stored in.
     *
     * @var string
     */
    protected $table = 'event_signups';

    /**
     * The model name.
     *
     * @var string
     */
    public static $name = 'event_signup';

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
