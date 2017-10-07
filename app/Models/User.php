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

use App\Models\Relations\HasManyCommentsTrait;
use App\Models\Relations\UserHasManyEventsTrait;
use App\Models\Relations\BelongsToManyPostsTrait;
use App\Models\Relations\UserHasManyFavoritePosts;
use App\Models\Relations\HasManyEventsTrait;
use App\Models\Relations\HasManyPagesTrait;
use App\Models\Relations\HasManyPostsTrait;
use GrahamCampbell\Credentials\Models\User as CredentialsUser;

/**
 * This is the user model class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class User extends CredentialsUser
{
    use HasManyPagesTrait, HasManyPostsTrait, HasManyEventsTrait, HasManyCommentsTrait, BelongsToManyPostsTrait, UserHasManyFavoritePosts, UserHasManyEventsTrait;

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return 'App\Presenters\UserPresenter';
    }

    /**
     * Before deleting an existing model.
     *
     * @return void
     */
    public function beforeDelete()
    {
        $this->deletePages();
        $this->deletePosts();
        $this->deleteEvents();
        $this->deleteComments();
    }

    public function getFullNameAttribute()
    {
      return $this->first_name . ' ' . $this->last_name;
    }

}
