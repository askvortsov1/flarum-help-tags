<?php

/*
 * This file is part of askvortsov/flarum-help-tags
 *
 *  Copyright (c) 2020 Alexander Skvortsov.
 *
 *  For detailed copyright and license information, please view the
 *  LICENSE file that was distributed with this source code.
 */

namespace Askvortsov\FlarumHelpTags\Access;

use Flarum\Discussion\Discussion;
use Flarum\User\AbstractPolicy;
use Flarum\User\User;
use Illuminate\Database\Eloquent\Builder;

class DiscussionPolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = Discussion::class;

    /**
     * @param User    $actor
     * @param Builder $query
     */
    public function findDiscussionsInRestrictedTags(User $actor, Builder $query)
    {
        if (!$actor->isGuest()) {
            $query->orWhere('discussions.user_id', $actor->id);
        }
    }
}
