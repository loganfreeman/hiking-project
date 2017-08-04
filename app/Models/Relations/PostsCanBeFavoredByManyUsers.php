<?php

/*
 * This file is part of Bootstrap CMS.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\BootstrapCMS\Models\Relations;
use GrahamCampbell\Credentials\Facades\Credentials;
use GrahamCampbell\BootstrapCMS\Models\Favorite;
use Illuminate\Support\Facades\Log;



/**
 * This is the belongs to post trait.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
trait PostsCanBeFavoredByManyUsers
{
    /**
     * Get the post relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
     public function favorites()
     {
         return $this->belongsToMany('GrahamCampbell\BootstrapCMS\Models\User', 'favorites');
     }

     public function favoritesCount()
     {
       return count($this->favorites()->get());
     }

     public function isFavoritedByMe($user)
     {
       Log::debug("User id " . $user->id);
       Log::debug("Poser id " . $this->id);
       return Favorite::whereUserId($user->id)->wherePostId($this->id)->exists();
     }
}
