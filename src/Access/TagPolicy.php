<?php

/*
 * This file is part of Flarum.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Askvortsov\FlarumHelpTags\Access;

use Flarum\Tags\Tag;
use Flarum\User\AbstractPolicy;
use Flarum\User\User;
use Illuminate\Database\Eloquent\Builder;

class TagPolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = Tag::class;

    /**
     * @param User $actor
     * @param Builder $query
     */
    public function find(User $actor, Builder $query)
    {
        $query
            ->orWhereIn('id', Tag::getIdsWhereCan($actor, 'startDiscussion'))
            ->orWhereIn('id', Tag::getIdsWhereCan($actor, 'discussion.viewTag'))
            ->orWhereIn('id', Tag::getIdsWhereCan($actor, 'viewTag'));
    }
}
