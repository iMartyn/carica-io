<?php

namespace Carica\Io\Firmata\Response\SysEx {

  include_once(__DIR__.'/../../../Bootstrap.php');

  use Carica\Io;
  use Carica\Io\Firmata;

  class StringTest extends \PHPUnit_Framework_TestCase {

    public function testConstructor() {
      $string = new String(
        0x71,
        [0x71, 0x48, 0x00, 0x61, 0x00, 0x6C, 0x00, 0x6C, 0x00, 0x6F, 0x00]
      );
      $this->assertEquals(
        'Hallo',
        $string->text
      );
    }
  }
}