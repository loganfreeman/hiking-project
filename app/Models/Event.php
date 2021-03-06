<?php

/*
 * This file is part of Bootstrap CMS.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Models;

use GrahamCampbell\Credentials\Models\AbstractModel;
use GrahamCampbell\Credentials\Models\Relations\BelongsToUserTrait;
use GrahamCampbell\Credentials\Models\Relations\RevisionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use McCool\LaravelAutoPresenter\HasPresenter;
use App\Models\EventSignups;


/**
 * This is the event model class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class Event extends AbstractModel implements HasPresenter
{
    use BelongsToUserTrait, RevisionableTrait, SoftDeletes;

    /**
     * The table the events are stored in.
     *
     * @var string
     */
    protected $table = 'events';

    /**
     * The model name.
     *
     * @var string
     */
    public static $name = 'event';

    /**
     * The properties on the model that are dates.
     *
     * @var array
     */
    protected $dates = ['date', 'deleted_at'];

    /**
     * The revisionable columns.
     *
     * @var array
     */
    protected $keepRevisionOf = ['title', 'body', 'date', 'location'];

    /**
     * The columns to select when displaying an index.
     *
     * @var array
     */
    public static $index = ['id', 'title', 'date'];

    /**
     * The max events per page when displaying a paginated index.
     *
     * @var int
     */
    public static $paginate = 10;

    /**
     * The columns to order by when displaying an index.
     *
     * @var string
     */
    public static $order = 'date';

    /**
     * The direction to order by when displaying an index.
     *
     * @var string
     */
    public static $sort = 'asc';

    /**
     * The event validation rules.
     *
     * @var array
     */
    public static $rules = [
        'title'    => 'required',
        'location' => 'required',
        'date'     => 'required',
        'body'     => 'required',
        'user_id'  => 'required',
    ];

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return 'App\Presenters\EventPresenter';
    }

    public function signups()
    {
        return $this->belongsToMany('App\Models\User', 'event_signups');
    }

    public function signupsCount()
    {
      return count($this->signups()->get());
    }

    public function isSignupedbyme($user)
    {
      return EventSignups::whereUserId($user->id)->whereEventId($this->id)->exists();
    }

    public function signupedUsers() {
      return array_map(function($o) { return $o['first_name'] . ' ' . $o['last_name']; }, $this->signups->toArray());
    }

    public function getRegisteredUsersCountAttribute()
    {
      return count($this->registered_users);
    }

    public function getRegisteredUsersAttribute()
    {
      return array_map(function($o) { return $o['first_name'] . ' ' . $o['last_name']; }, $this->signups->toArray());
    }
}
