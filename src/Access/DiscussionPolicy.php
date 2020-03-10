<?php

/*
 * This file is part of Flarum.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Askvortsov\FlarumHelpTags\Access;

use Flarum\Discussion\Discussion;
use Flarum\Settings\SettingsRepositoryInterface;
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
        if (! $actor->isGuest()) {
            // Hack to make sure the "where" clause we're inserting will be the first where statement

            // This will be the 2nd where statement.
            if ($query->getQuery()->wheres != []) {
                // It will combine with the following clause  (with no effect, thanks Boolean Algebra!)
                // and stop it from destabilizing the next clause.
                array_unshift($query->getQuery()->wheres, [
                    "type" => "raw",
                    // Set the sql value to whatever will have no effect on the following clause.
                    "sql" => $query->getQuery()->wheres[0]['boolean'] === 'and' ? '1': '0',
                    "boolean" => "or"
                ]);
                array_unshift($query->getQuery()->bindings['where'], []);
            }

            // This will be the 1st where statement.
            // This always allows the author of a discussion to see the discussion.
            array_unshift($query->getQuery()->wheres, [
                "type" => "Basic",
                "column" => "discussions.user_id",
                "operator" => "=",
                "value" => $actor->id,
                "boolean" => "or"
            ]);
            array_unshift($query->getQuery()->bindings['where'], $actor->id);
        }
    }
}
