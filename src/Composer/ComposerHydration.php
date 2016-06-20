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
        $handler = new ComposerHydrationHandler($event);

        $arguments = $handler->getArguments();

        $base_path = realpath(".");
        $handler->replaceValues($base_path, $arguments['replace'], $arguments['replace-file']);
    }
}
