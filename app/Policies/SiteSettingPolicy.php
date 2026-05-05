<?php

namespace App\Policies;

class SiteSettingPolicy extends AdminPolicy
{
    protected array $managers = ['admin', 'content_manager'];

    protected array $viewers = ['admin', 'content_manager', 'readonly'];
}
