<?php

namespace Goellner\TableOnSteroids\Tests;

use Goellner\TableOnSteroids\ServiceProvider;
use Statamic\Testing\AddonTestCase;

abstract class TestCase extends AddonTestCase
{
    protected string $addonServiceProvider = ServiceProvider::class;
}
