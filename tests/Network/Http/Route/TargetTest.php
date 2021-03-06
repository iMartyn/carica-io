<?php

namespace Carica\Io\Network\Http\Route {

  include_once(__DIR__.'/../../../Bootstrap.php');

  use Carica\Io\Network\Http;

  class TargetTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers Carica\Io\Network\Http\Route\Target
     */
    public function testConstructor() {
      $target = new Target_TestProxy($callback = function() {});
      $this->assertSame($callback, $target->getCallback());
    }

    /**
     * @covers Carica\Io\Network\Http\Route\Target
     */
    public function testCallableInterfaceVaildationSuccessful() {
      $request = $this
        ->getMockBuilder('Carica\\Io\\Network\\Http\\Request')
        ->disableOriginalConstructor()
        ->getMock();
      $target = new Target_TestProxy(
        function(Http\Request $request, array $parameters) use (&$result) {
          return TRUE;
        }
      );
      $target->validationResult = array();
      $this->assertTrue($target($request));
    }
    /**
     * @covers Carica\Io\Network\Http\Route\Target
     */
    public function testCallableInterfaceVaildationFailed() {
      $request = $this
        ->getMockBuilder('Carica\\Io\\Network\\Http\\Request')
        ->disableOriginalConstructor()
        ->getMock();
      $target = new Target_TestProxy(
        function(Http\Request $request, array $parameters) use (&$result) {
          return TRUE;
        }
      );
      $target->validationResult = FALSE;
      $this->assertFalse($target($request));
    }
  }

  class Target_TestProxy extends Target {

    public $validationResult = NULL;

    public function validate(Http\Request $request) {
      return $this->validationResult;
    }
  }
}