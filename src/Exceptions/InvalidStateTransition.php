<?php

namespace InGreatState\Exceptions;

/**
 * Exception thrown when trying to transition from or to an invalid
 * state or passing something other than a closure to the actions of
 * a transition.
 *
 * @author Pantelis Vratsalis <pvratsalis@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 */
class InvalidStateTransition extends \Exception
{
}
