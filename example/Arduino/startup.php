<?php

use Carica\Io\Stream\SerialPort;

include('../../src/Carica/Io/Loader.php');
Carica\Io\Loader::register();

use Carica\Io;
use Carica\Io\Firmata;

$board = new Firmata\Board(
  $stream = new Io\Stream\Serial\Dio('COM7:')
);

$loop = Io\Event\Loop\Factory::get();

$board->events()->on(
  'reportversion',
  function () use ($board) {
    echo 'Firmata version: '.$board->version."\n";
  }
);
$board->events()->on(
  'queryfirmware',
  function () use ($board) {
    echo 'Firmware version: '.$board->firmware."\n";
  }
);

$active = $board
  ->activate()
  ->done(
    function () {
      echo "activated\n";
    }
  )
  ->fail(
    function ($error) {
      echo $error."\n";
    }
  );

if ($board->isActive()) {
  $loop->run();
}


