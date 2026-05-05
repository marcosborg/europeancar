<?php

namespace App\Policies;

class PagePolicy extends AdminPolicy
{
    protected array $managers = ['admin', 'content_manager'];

    protected array $viewers = ['admin', 'content_manager', 'readonly'];
}
