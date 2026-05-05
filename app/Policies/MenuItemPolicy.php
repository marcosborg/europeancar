<?php

namespace App\Policies;

class MenuItemPolicy extends AdminPolicy
{
    protected array $managers = ['admin', 'content_manager'];

    protected array $viewers = ['admin', 'content_manager', 'readonly'];
}
