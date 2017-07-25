<?php

namespace InGreatState;

/**
 * StateTransition is a class that encapsulates the elements of a state
 * transition, namely the state from which the transition begins, the state
 * to which it ends and the actions performed during the transition.
 *
 * As a user of this library, you don't need to create instances of the
 * StateTransition directly. Just use the addTransition method
 * of the StateMachine instance.
 *
 * @author Pantelis Vratsalis <pvratsalis@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 */
class StateTransition
{
    public $from = null;
    public $to = null;
    public $actions = null;
    private $availableStates = [];

    /**
     * Instantiates a state transition.
     *
     * @param array $availableStates the states the stateful object can be in
     * @return InGreatState\StateTransition
     */
    public function __construct($availableStates)
    {
        $this->availableStates = $availableStates;
    }

    /**
     * Sets the initial state of the transition. Before doing so, it checks
     * if the state is valid (i.e. included in the availableStates as defined
     * when instantiating the transition).
     *
     * @param string $state the initial state of the transition
     * @return InGreatState\StateTransition
     * @throws InGreatState\Exceptions\InvalidStateTransition
     */
    public function from($state)
    {
        if ($state !== null && !in_array($state, $this->availableStates)) {
            throw new Exceptions\InvalidStateTransition(
                "State {$state} is not in the list of states."
            );
        }

        $this->from = $state;
        return $this;
    }

    /**
     * Sets the final state of the transition. Before doing so, it checks
     * if the state is valid (i.e. included in the availableStates as defined
     * when instantiating the transition).
     *
     * @param string $state the final state of the transition
     * @return InGreatState\StateTransition
     * @throws InGreatState\Exceptions\InvalidStateTransition
     */
    public function to($state)
    {
        if (!in_array($state, $this->availableStates)) {
            throw new Exceptions\InvalidStateTransition(
                "State {$state} is not in the list of states."
            );
        }

        $this->to = $state;
        return $this;
    }

    /**
     * Sets the actions that need to be performed when the transaction is
     * triggered. If a \Closure is not passed as an argument, an exception
     * is thrown.
     *
     * @param \Closure $actions the actions performed when the transition is triggered
     * @return InGreatState\StateTransition
     * @throws InGreatState\Exceptions\InvalidStateTransition
     */
    public function actions($actions)
    {
        if (!($actions instanceof \Closure)) {
            throw new Exceptions\InvalidStateTransition(
                "Actions should be a closure."
            );
        }

        $this->actions = $actions;
        return $this;
    }
}
