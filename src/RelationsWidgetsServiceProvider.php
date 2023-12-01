<?php

namespace izica\RelationsWidgets;

use Illuminate\Support\ServiceProvider;

class RelationsWidgetsServiceProvider extends ServiceProvider
{
    use AutomaticServiceProvider;

    protected $vendorName = 'izica';
    protected $packageName = 'relations-widgets-for-backpack';
    protected $commands = [];

}