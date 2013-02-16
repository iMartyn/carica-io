<?php

namespace Carica\Io\Firmata\Response\Sysex {

  use Carica\Io\Firmata;

  class AnalogMappingResponse extends Firmata\Response\Sysex {

    private $_pins = array();
    private $_channels = array();

    public function __construct($bytes) {
      parent::__construct($bytes);
      $length = count($bytes);
      for ($i = 1, $pin = 0; $i < $length; ++$i, ++$pin) {
        $channel = $bytes[$i];
        if ($channel !== 127) {
          $this->_channels[$channel] = $pin;
          $this->_pins[$pin] = $channel;
        }
      }
    }

    public function __get($name) {
      switch ($name) {
      case 'channels' :
        return $this->_channels;
      case 'pins' :
        return $this->_pins;
      }
      throw new \LogicException(sprintf('Unknown property %s::$%s', __CLASS__, $name));
    }
  }
}