<?php

namespace Goellner\TableOnSteroids;

use Goellner\TableOnSteroids\Fieldtypes\TableOnSteroids;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $fieldtypes = [
        TableOnSteroids::class,
    ];

    protected $scripts = [
        __DIR__ . '/../resources/dist/js/table_on_steroids-fieldtype.js',
    ];
}
