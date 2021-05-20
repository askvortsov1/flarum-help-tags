<?php

use Flarum\Database\Migration;

return Migration::addColumns('discussions', [
    'show_to_all' => ['boolean', 'default' => false]
]);
