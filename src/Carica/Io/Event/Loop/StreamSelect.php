<?php

namespace Carica\Io\Event\Loop {

  use Carica\Io\Event;

  class StreamSelect implements Event\Loop {

    private $_wait = 5;

    private $_listeners = array();
    private $_streams = array(
      'read' => array(),
      'write' => array(),
      'except' => array()
    );
    private $_hasStreams = FALSE;

    public function setTimeout(Callable $callback, $milliseconds) {
      return $this->add(
        new Listener\Timeout($this, $callback, $milliseconds)
      );
    }

    public function setInterval(Callable $callback, $milliseconds) {
      return $this->add(
        new Listener\Interval($this, $callback, $milliseconds)
      );
    }

    public function setStreamReader(Callable $callback, $stream) {
      $listener = $this->add(
        new Listener\StreamReader($this, $callback, $stream)
      );
      $this->_streams['read'][spl_object_hash($listener)] = $stream;
      $this->updateStreamStatus();
      return $listener;
    }

    private function add(Listener $listener) {
      return $this->_listeners[spl_object_hash($listener)] = $listener;
    }

    public function remove($listener) {
      if (!is_object($listener)) {
        $key = spl_object_hash($listener);
        if (isset($this->_listeners[$key])) {
          unset($this->_listeners[$key]);
          if (isset($this->_streams['read'][$key])) {
            unset($this->_streams['read'][$key]);
          }
        }
      }
      $this->updateStreamStatus();
    }

    public function run() {
      $this->_running = TRUE;
      while ($this->tick()) {
        // ticking
      }
    }

    public function stop() {
      $this->_running = FALSE;
    }

    private function tick() {
      if ($this->_running) {
        foreach ($this->_listeners as $listener) {
          $listener->tick();
        }
        $this->schedule();
        return TRUE;
      }
      return FALSE;
    }

    private function schedule() {
      if ($this->_hasStreams) {
        $read = $this->_streams['read'];
        $write = $this->_streams['write'];
        $except = $this->_streams['except'];
        stream_select($read, $write, $except, $this->_wait);
      } else {
        usleep($this->_wait);
      }
    }

    private function updateStreamStatus() {
      $this->_hasStreams = (
        count($this->_streams['read']) > 0 ||
        count($this->_streams['write']) > 0 ||
        count($this->_streams['except']) > 0
      );
    }
  }
}