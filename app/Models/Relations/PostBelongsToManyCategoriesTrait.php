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
trait PostBelongsToManyCategoriesTrait
{
    /**
     * Get the post relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
     public function categories()
     {
         return $this->belongsToMany('App\Models\Category', 'post_categories');
     }

     public function categoryNames() {
       return array_map(function($o) { return $o['name']; }, $this->categories->toArray());
     }

}
