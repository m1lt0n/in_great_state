<?php

use PHPUnit\Framework\TestCase;

class StateTransitionTest extends TestCase
{
    protected $sut;

    public function setup()
    {
        $this->sut = new InGreatState\StateTransition([
            'open', 'closed', 'in progress'
        ]);
    }

    public function testFromWithNull()
    {
        $result = $this->sut->from(null);
        $this->assertEquals(null, $this->sut->from);
        $this->assertEquals($this->sut, $result);
    }

    public function testFromWithInvalidState()
    {
        $this->expectException(InGreatState\Exceptions\InvalidStateTransition::class);
        $this->sut->from('invalid state');
    }

    public function testFromWithValidState()
    {
        $result = $this->sut->from(null);
        $this->assertEquals(null, $this->sut->from);
        $this->assertEquals($this->sut, $result);
    }

    public function testToWithInvalidState()
    {
        $this->expectException(InGreatState\Exceptions\InvalidStateTransition::class);
        $this->sut->to('invalid state');
    }

    public function testToWithValidState()
    {
        $result = $this->sut->to('closed');
        $this->assertEquals('closed', $this->sut->to);
        $this->assertEquals($this->sut, $result);
    }

    public function testActionsWithValidArguments()
    {
        $fn = function () { return 'from closure'; };
        $result = $this->sut->actions($fn);
        $this->assertEquals($fn, $this->sut->actions);
        $this->assertEquals($this->sut, $result);
    }

    public function testActionsWithInvalidArguments()
    {
        $this->expectException(InGreatState\Exceptions\InvalidStateTransition::class);
        $this->sut->actions('not a closure');
    }
}
