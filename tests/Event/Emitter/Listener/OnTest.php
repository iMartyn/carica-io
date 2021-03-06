<?php

namespace Carica\Io\Event\Emitter\Listener {

  include_once(__DIR__.'/../../../Bootstrap.php');

  class OnTest extends \PHPUnit_Framework_TestCase {

    public $calledCallback = FALSE;

    /**
     * @covers Carica\Io\Event\Emitter\Listener\On::__construct
     */
    public function testConstructor() {
      $emitter = $this->getMock('Carica\\Io\\Event\\Emitter');
      $callback = function() {};
      $event = new On($emitter, 'foo', $callback);
      $this->assertSame($emitter, $event->emitter);
      $this->assertEquals('foo', $event->event);
      $this->assertSame($callback, $event->callback);
    }

    /**
     * @covers Carica\Io\Event\Emitter\Listener\On::__isset
     * @dataProvider provideValidProperties
     */
    public function testPropertyIsset($property) {
      $event = new On($this->getMock('Carica\\Io\\Event\\Emitter'), 'foo', function() {});
      $this->assertTrue(isset($event->$property));
    }

    /**
     * @covers Carica\Io\Event\Emitter\Listener\On::__isset
     */
    public function testPropertyIssetWithInvalidPropertyExpectingFalse() {
      $event = new On($this->getMock('Carica\\Io\\Event\\Emitter'), 'foo', function() {});
      $this->assertFalse(isset($event->INVALID_PROPERTY));
    }

    /**
     * @covers Carica\Io\Event\Emitter\Listener\On::__get
     */
    public function testGetPropertyEmitter() {
      $event = new On($emitter = $this->getMock('Carica\\Io\\Event\\Emitter'), 'foo', function() {});
      $this->assertSame($emitter, $event->emitter);
    }

    /**
     * @covers Carica\Io\Event\Emitter\Listener\On::__get
     */
    public function testGetPropertyEvent() {
      $event = new On($this->getMock('Carica\\Io\\Event\\Emitter'), 'foo', function() {});
      $this->assertEquals('foo', $event->event);
    }

    /**
     * @covers Carica\Io\Event\Emitter\Listener\On::__get
     */
    public function testGetPropertyCallback() {
      $event = new On($this->getMock('Carica\\Io\\Event\\Emitter'), 'foo', $callback = function() {});
      $this->assertSame($callback, $event->callback);
    }

    /**
     * @covers Carica\Io\Event\Emitter\Listener\On::getCallback
     */
    public function testGetCallback() {
      $event = new On($this->getMock('Carica\\Io\\Event\\Emitter'), 'foo', $callback = function() {});
      $this->assertSame($callback, $event->getCallback());
    }

    /**
     * @covers Carica\Io\Event\Emitter\Listener\On::__get
     */
    public function testGetInvalidPropertyExpectingException() {
      $event = new On($this->getMock('Carica\\Io\\Event\\Emitter'), 'foo', function() {});
      $this->setExpectedException('LogicException');
      $dummy = $event->INVALID_PROPERTY;
    }

    /**
     * @covers Carica\Io\Event\Emitter\Listener\On::__set
     */
    public function testSetIsBlockedExpectingException() {
      $event = new On($this->getMock('Carica\\Io\\Event\\Emitter'), 'foo', function() {});
      $this->setExpectedException('LogicException');
      $event->emitter = $this->getMock('Carica\\Io\\Event\\Emitter');
    }

    /**
     * @covers Carica\Io\Event\Emitter\Listener\On::__unset
     */
    public function testUnsetIsBlockedExpectingException() {
      $event = new On($this->getMock('Carica\\Io\\Event\\Emitter'), 'foo', function() {});
      $this->setExpectedException('LogicException');
      unset($event->emitter);
    }

    /**
     * @covers Carica\Io\Event\Emitter\Listener\On::__invoke
     */
    public function testInvokeCallsCallback() {
      $that = $this;
      $event = new On(
        $this->getMock('Carica\\Io\\Event\\Emitter'),
        'foo',
        function() use ($that) {
          $that->calledCallback = TRUE;
        }
      );
      $event();
      $this->assertTrue($this->calledCallback);
    }

    /**************************
     * Data Provider
     *************************/

    public static function provideValidProperties() {
      return array(
        array('emitter'),
        array('event'),
        array('callback')
      );
    }
  }
}