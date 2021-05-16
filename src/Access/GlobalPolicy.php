<?php

/*
 * This file is part of Flarum.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Askvortsov\FlarumHelpTags\Access;

use Flarum\User\Access\AbstractPolicy;
use Flarum\User\User;

class GlobalPolicy extends AbstractPolicy
{
    public function can(User $actor, string $ability)
    {
        if ($ability === 'viewForum' && $actor->can('startDiscussion')) {
            return $this->forceAllow();
        }
    }
}
