<?php

use PHPUnit\Framework\TestCase;

require 'mocks.php';

class StateMachineTest extends TestCase
{
    public function setup()
    {
        $issue = new Issue();
        $issue->setState('in progress');
        $this->sut = new IssueStateMachine($issue);
    }

    public function testAddTransition()
    {
        $tr = $this->sut->addTransition();

        $tr->from('open')->to('closed')->actions(function ($owner) {
            $owner->statesLog[] = "open->closed";
        });

        $refl = new ReflectionClass($this->sut);
        $transitions = $refl->getProperty('transitions');
        $transitions->setAccessible(true);
        $this->assertEquals($tr, array_slice($transitions->getValue($this->sut), -1)[0]);
    }

    public function testTransitionToInvalidTransition()
    {
        $this->expectException(InGreatState\Exceptions\InvalidStateTransition::class);
        $this->sut->transitionTo('open');
    }

    public function testTransitionToValidTransition()
    {
        $this->sut->transitionTo('closed');
        $refl = new ReflectionClass($this->sut);
        $owner = $refl->getProperty('owner');
        $owner->setAccessible(true);

        $this->assertEquals('closed', $owner->getValue($this->sut)->state);
        $this->assertEquals('in progress->closed', $owner->getValue($this->sut)->statesLog[0]);
    }
}
