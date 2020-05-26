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
     * @param User    $actor
     * @param Builder $query
     */
    public function find(User $actor, Builder $query)
    {
        if (!$actor->isGuest()) {
            $utilQuery = $query->getQuery()->newQuery();
            if ($query->getQuery()->wheres[0]['type'] === 'Basic' && $query->getQuery()->wheres[0]['column'] === 'id') {
                $utilQuery->orWhere(function ($subquery) use ($actor, $query) {
                    $subquery
                        ->where('discussions.user_id', $actor->id)
                        ->where('id', $query->getQuery()->wheres[0]['value']);
                });
            } else {
                $utilQuery->orWhere('discussions.user_id', $actor->id);
            }
            if ($query->getQuery()->wheres[0]['boolean'] === 'and') {
                $utilQuery->orWhereRaw('1');
            }

            $query->getQuery()->wheres = array_merge($utilQuery->wheres, $query->getQuery()->wheres);
            $query->getQuery()->bindings['where'] = array_values(
                array_merge($utilQuery->bindings['where'], $query->getQuery()->bindings['where'])
            );
        }
    }
}
