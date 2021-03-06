<?php

namespace Carica\Io\Event\Loop {

  include_once(__DIR__.'/../../Bootstrap.php');

  use Carica\Io\Event;

  /**
   * @covers Carica\Io\Event\Loop\Clock
   */
  class ClockTest extends \PHPUnit_Framework_TestCase {

    public function testSetTimeout() {
      $loop = new Clock();
      $success = FALSE;
      $loop->setTimeout(
        function () use (&$success) {
          $success = TRUE;
        },
        100
      );
      $loop->tick(99);
      $this->assertFalse($success);
      $loop->tick(1);
      $this->assertTrue($success);
    }

    public function testSetTimeoutOnlyCalledOnce() {
      $loop = new Clock();
      $counter = 0;
      $loop->setTimeout(
        function () use (&$counter) {
          $counter++;
        },
        100
      );
      $loop->tick(1000);
      $this->assertEquals(1, $counter);
    }

    public function testSetInterval() {
      $loop = new Clock();
      $counter = 0;
      $loop->setInterval(
        function () use (&$counter) {
          $counter++;
        },
        100
      );
      $loop->tick(1000);
      $this->assertEquals(10, $counter);
    }
  }
}