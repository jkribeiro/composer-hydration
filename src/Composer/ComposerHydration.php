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
     * TODO Add doc.
     */
    public static function meatOnBones(Event $event)
    {
        $base_path = realpath(".");
        $handler = new ComposerHydrationHandler($event, $base_path);

        $handler->hydrate();
    }
}
