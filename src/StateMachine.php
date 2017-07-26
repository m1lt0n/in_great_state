<?php

namespace InGreatState;

/**
 * StateMachine is an abstract class that provides some base functionality
 * for all state machines that need to be created. The only methods that need
 * to be implemented by subclasses are: a) states, that which return an array
 * of the states of the object and b) registerTransitions, which adds the
 * actions performed when a transition takes place (triggered by transitionTo).
 *
 * Sample usage (e.g. an issue tracker):
 *
 * $issue = new Issue();
 * $sm = new IssueStateMachine($issue);
 * $sm->addTransition()->to('resolved')->actions(function ($owner) {
 *     // any action that should be performed on state transition
 * });
 * $issue->stateMachine()->transitionTo('resolved')
 *
 * @author Pantelis Vratsalis <pvratsalis@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 */
abstract class StateMachine
{
    protected $owner = null;
    protected $transitions = [];

    /**
     * Instantiate a state machine.
     *
     * @param StatefulInterface $owner the object which the machine handles
     * @return StateMachine
     */
    public function __construct(StatefulInterface $owner)
    {
        $this->owner = $owner;
        $this->registerTransitions();
    }

    /**
     * Adds a transition that can be handled by the machine.
     *
     * @return void
     */
    public function addTransition()
    {
        $newTransition = new StateTransition($this->states());
        $this->transitions[] = $newTransition;

        return $newTransition;
    }

    /**
     * Transitions the stateful object to the $state. If the transition is not
     * registered in the state machine (via addTransition or registerTransitions),
     * an InGreatState\Exceptions\InvalidStateTransition exception is thrown.
     *
     * @param string $state the state to which the object should transition to
     * @throws InGreatState\Exceptions\InvalidStateTransition
     * @return void
     */
    public function transitionTo($state)
    {
        $transitioned = false;

        foreach ($this->transitions as $transition) {
            if ($transition->to !== $state ||
                ($transition->from !== null && $transition->from !== $this->owner->currentState())
            ) {
                continue;
            }

            $cb = $transition->actions;
            $cb($this->owner);
            $transitioned = true;
        }

        if ($transitioned) {
            $this->owner->setState($state);
        } else {
            throw new Exceptions\InvalidStateTransition(
                "Attempted transition is not registered in the state machine"
            );
        }
    }

    /**
     * Abstract method that should be implemented by subclasses. Returns an
     * array with the states that the object handled by the machine can be in.
     *
     * @return array the states the object can be in
     */
    abstract public function states();

    /**
     * Abstract method that should be implemented by subclasses. registers
     * all the transitions between states of the handled object.
     *
     * @return void
     */
    abstract public function registerTransitions();
}
