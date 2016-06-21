<?php

/**
 * @file
 * Contains \Jkribeiro\Composer\ComposerHydration.
 */

namespace Jkribeiro\Composer;

use Composer\Script\Event;
use Jkribeiro\Composer\ComposerHydrationHandler;

class ComposerHydration
{
    /**
     * Composer callback method for Hydration process.
     */
    public static function meatOnBones(Event $event)
    {
        $basePath = realpath(".");
        $handler = new ComposerHydrationHandler($event, $basePath);

        $handler->hydrate();
    }
}
