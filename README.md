# InGreatState
An easy to use and flexible state machine.

InGreatState is a simple and very extensible state machine. It is not opinionated and it can be used in combination with ORMs, web frameworks or in standalone scripts.

### Εxample
Assume you have an issue tracker. Issues can be in one of several states (e.g. open, wontfix, resolved, reopened etc). When transitioning from one state to the other, we want to do several things (e.g. send an email to the owner of the issue etc). An easy way to organize such actions are by using a state machine. An example of the example above could be:

```php
<?php

// The minimum requirements of a StateFul interface is to implement the
// currentState method (that returns the object's current state) and the
// setState method (that updates the state of the object).

class Issue implements InGreatState\StatefulInterface
{
    private $state;

    public function __construct()
    {
        $this->setState('open');
    }

    public function currentState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
        echo "Setting state to {$state}" . PHP_EOL;
    }
}

// The minimum requirements for a state machine that extends the InGreatState
// state machine is to implement the method states (which returns an array of
// all the states an object can be) and registerTransitions (which adds all
// the allowed state transitions and the actions they trigger).

class IssueStateMachine extends InGreatState\StateMachine
{
    public function states()
    {
        return ['open', 'wontfix', 'resolved', 'reopened'];
    }

    public function registerTransitions()
    {
        $this->addTransition()->from('open')->to('resolved')->actions(function ($owner) {
            echo "Resolving transition from {$owner->currentState()} to resolved" . PHP_EOL;
        });

        $this->addTransition()->from('open')->to('wontfix')->actions(function ($owner) {
            echo "Resolving transition from {$owner->currentState()} to wontfix" . PHP_EOL;
        });
    }
}

// Now, we can use the state machine :-)

$p = new Issue();
$sm = new IssueStateMachine($p);
$sm->transitionTo('resolved');
```

This code will set the state of the issue to 'resolved' and also echo out
the phrases:
* Resolving transition from open to resolved (triggered from the transition open->resolved)
* Setting state to resolved  (triggered from setState of Issue)

The architecture is flexible enough accomodate any web framework or ORM integration and it's very easy to add features such as state logging (you can just store the state transitions in a database or even a flat file by implementing the functionality in the setState method of the stateful object). You are 100% in control!
