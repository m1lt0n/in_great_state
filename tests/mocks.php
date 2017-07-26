<?php

class Issue implements InGreatState\StatefulInterface
{
    public $statesLog = [];
    public $state = 'open';

    public function currentState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }
}

class IssueStateMachine extends InGreatState\StateMachine
{
    public function states()
    {
        return ['open', 'closed', 'in progress'];
    }

    public function registerTransitions()
    {
        $this->addTransition()->from('in progress')->to('closed')->actions(function ($owner) {
            $owner->statesLog[] = "in progress->closed";
        });

        $this->addTransition()->from('open')->to('in progress')->actions(function ($owner) {
            $owner->statesLog[] = "open->in progress";
        });

        $this->addTransition()->to('closed')->actions(function ($owner) {
            $owner->statesLog[] = "any->closed";
        });
    }
}
