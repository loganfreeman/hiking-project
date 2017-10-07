<?php

/*
 * This file is part of Bootstrap CMS.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Models\Relations;

/**
 * This is the belongs to post trait.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
trait UserHasManyEventsTrait
{
    /**
     * Get the post relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
     public function signups()
     {
         return $this->belongsToMany('App\Models\Event', 'event_signups', 'user_id', 'event_id');
     }
}
