<?php
/**
 * Created by PhpStorm.
 * User: 3525339
 * Date: 11/21/2018
 * Time: 3:48 PM
 */

use Service\StdIo;

class TaskMaster extends Phalcon\Cli\Task
{
    /**
     * Describes the items in this command.
     */
    public function mainAction()
    {
        $reflector = new Service\Reflector($this);

        StdIo::outln('Sub-commands:');
        foreach ($reflector->getFormattedDescriptions() as $description) {
            StdIo::outln("\t" . $description);
        }
    }

    /**
     * Describes the items in this command.
     */
    public function helpAction()
    {
		$reflector = new Service\Reflector($this);

        StdIo::outln('Sub-commands:');
        foreach ($reflector->getFormattedDescriptions() as $description) {
            StdIo::outln("\t" . $description);
        }
    }
}
