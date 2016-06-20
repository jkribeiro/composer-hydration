<?php

/**
 * @file
 * Contains \Jkribeiro\Composer\ComposerHydrationHandler.
 */

namespace Jkribeiro\Composer;

use Composer\Script\Event;
use Symfony\Component\Finder\Finder;

class ComposerHydrationHandler
{
    const REPLACE_ARG = '--replace';
    const REPLACE_FILE_ARG = '--replace-file';

    /**
     * @var \Jkribeiro\Composer\ComposerHydrationHandler\event
     */
    public $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Checks if the given command argument is available.
     *
     * @param string $argument_name
     *   Command argument name to be verified.
     *
     * @return boolean
     *   Returns TRUE in case of success, FALSE otherwise.
     */
    public function cmdArgumentExist($argument_name)
    {
        return in_array($argument_name, [self::REPLACE_ARG, self::REPLACE_FILE_ARG]);
    }

    /**
     * Returns an array containing the replace values from command argument.
     *
     * @param string $arg_values
     *   Command argument replace value.
     *
     * @return array
     *   An array, following the format: SEARCH => REPLACE.
     */
    public function getReplaceValuesFromArgument($arg_values)
    {
        $replace_values = [];

        $arg_values = explode(',', $arg_values);
        foreach ($arg_values as $arg_value) {
            $arg_value = explode(':', $arg_value);
            if (count($arg_value) != 2) {
                throw new \ErrorException('Command argument "--replace" must follow the format: --replace="{SEARCH}:{REPLACE},..."');
            }

            $replace_values[trim($arg_value[0])] = trim($arg_value[1]);
        }

        return $replace_values;
    }

    /**
     * Returns an array containing the command arguments values.
     */
    public function getArguments()
    {
        // Checks if script received command arguments.
        $cmd_arguments = $this->event->getArguments();
        if (!$cmd_arguments) {
            throw new \ErrorException('Hydrate command expects arguments.');
        }

        // Treats arguments.
        $return_arguments = [];
        foreach ($cmd_arguments as $cmd_argument) {
            $cmd_argument = explode('=', $cmd_argument);
            $argument = $cmd_argument[0];

            // Checks if the argument exists.
            if (!$this->cmdArgumentExist($argument)) {
                throw new \ErrorException("Command argument '$argument' do not exist.");
            }

            // Treats REPLACE_ARG argument.
            if ($argument == self::REPLACE_ARG) {
                $replace_values = !empty($cmd_argument[1]) ? $cmd_argument[1] : NULL;
                if (!$replace_values) {
                    throw new \ErrorException('Command argument "--replace" must contain values, like: --replace="{SEARCH}:{REPLACE},.."');
                }

                $return_arguments['replace'] = $this->getReplaceValuesFromArgument($replace_values);
            }

            // Adds 'replace-file' flag to returned arguments.
            $return_arguments['replace-file'] = $argument == self::REPLACE_FILE_ARG ? TRUE : FALSE;
        }

        return $return_arguments;
    }

    public function replaceValues($base_path, $replace_map, $replace_file_name = FALSE)
    {
        $io = $this->event->getIO();

        $finder = new Finder();
        $finder->in($base_path)->exclude('vendor');

        foreach ($replace_map as $search => $replace) {
            // Restrict files by search.
            $finder->contains($search);
        }

        $io->write("[Hydration][INFO] Replacing the content of " . iterator_count($finder) . " files.");
        foreach ($finder as $file) {
            $file_path = $file->getRealpath();
            $io->write("[Hydration][INFO] Reading file: $file_path");

            // Replace values.
            $file_content = str_replace(array_keys($replace_map), array_values($replace_map), $file->getContents());

            // Save file with new replaced content.
            $file_saved_bytes = file_put_contents($file_path, $file_content);
            if (!$file_saved_bytes) {
                // Failed.
                throw new \ErrorException("Unable to Hydrate the file, check the file permissions and try again.");
            }

            // Success.
            $io->write("[Hydration][OK] File Hydrated.");

        }
    }

}
