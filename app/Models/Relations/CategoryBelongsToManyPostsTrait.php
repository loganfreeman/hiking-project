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
trait CategoryBelongsToManyPostsTrait
{
    /**
     * Get the post relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
     public function posts()
     {
         return $this->belongsToMany('App\Models\Post', 'post_categories', 'category_id', 'post_id');
     }
}
