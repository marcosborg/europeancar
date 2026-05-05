<?php

namespace App\Policies;

class VehicleFeaturePolicy extends AdminPolicy
{
    protected array $managers = ['admin', 'sales_manager'];

    protected array $viewers = ['admin', 'sales_manager', 'readonly'];
}
