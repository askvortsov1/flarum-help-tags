<?php

/*
 * This file is part of askvortsov/flarum-help-tags
 *
 *  Copyright (c) 2021 Alexander Skvortsov.
 *
 *  For detailed copyright and license information, please view the
 *  LICENSE file that was distributed with this source code.
 */

namespace Askvortsov\FlarumHelpTags;

use Flarum\Discussion\Discussion;
use Flarum\Extend;
use Flarum\Tags\Tag;

return [
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    (new Extend\Policy())
        ->globalPolicy(Access\GlobalPolicy::class),

    (new Extend\ModelVisibility(Discussion::class))
        ->scope(Access\ScopeDiscussionVisibility::class, 'viewForumInRestrictedTags'),

    (new Extend\ModelVisibility(Tag::class))
        ->scope(Access\ScopeTagVisibility::class),

    new Extend\Locales(__DIR__.'/resources/locale'),
];
