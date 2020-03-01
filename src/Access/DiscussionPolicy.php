<?php

/*
 * This file is part of Flarum.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Askvortsov\FlarumPrivateTags\Access;

use Flarum\Discussion\Discussion;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\Tags\Tag;
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
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    /**
     * @param SettingsRepositoryInterface $settings
     */
    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param User $actor
     * @param Builder $query
     */
    public function find(User $actor, Builder $query)
    {
        // Hide discussions which have tags that the user is not allowed to see.
        $query->orWhere('user_id', '=', $actor->id);
    }

    /**
     * @param User $actor
     * @param Builder $query
     * @param string $ability
     */
    public function findWithPermission(User $actor, Builder $query, $ability)
    {
        // If a discussion requires a certain permission in order for it to be
        // visible, then we can check if the user has been granted that
        // permission for any of the discussion's tags.
        $query->whereExists(function ($query) use ($actor, $ability) {
            return $query->selectRaw('1')
                ->from('discussion_tag')
                ->whereIn('tag_id', Tag::getIdsWhereCan($actor, 'discussion.' . $ability))
                ->whereColumn('discussions.id', 'discussion_id');
        });
    }
}
