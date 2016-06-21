<?php

/**
 * @file
 * Contains \Jkribeiro\Composer\ComposerHydration.
 */

namespace Jkribeiro\Composer;

use Composer\Script\Event;
use Jkribeiro\Composer\ComposerHydrationHandler;

/**
 * Composer Script Hydration Class.
 */
class ComposerHydration
{
    /**
     * Composer callback method for Hydration process.
     *
     * @param object $event
     *   Composer Event object.
     */
    public static function meatOnBones(Event $event)
    {
        $basePath = realpath(".");
        $handler = new ComposerHydrationHandler($event, $basePath);

        $handler->hydrate();
    }
}
