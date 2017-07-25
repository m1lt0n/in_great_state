<?php

namespace InGreatState;

/**
 * The StatefulInterface should be implemented by any class that
 * needs to keep track of an internal state. The methods of this interface
 * are necessary for interaction of the object with the state machine.
 *
 * @author Pantelis Vratsalis <pvratsalis@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 */
interface StatefulInterface
{
    /**
     * Returns the current state of the object.
     *
     * @return string
     */
    public function currentState();

    /**
     * Sets the state of the object. If the object is a model that interacts
     * with a database, setState could store the passed $state in the database.
     *
     * @param $state string the new state of the object
     * @return void
     */
    public function setState($state);
}
