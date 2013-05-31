<?php
include('../../src/Carica/Io/Loader.php');
Carica\Io\Loader::register();

use Carica\Io;

$mysqlOne = new Io\Deferred\MySQL(new mysqli('localhost'));
$mysqlTwo = new Io\Deferred\MySQL(new mysqli('localhost'));

$time = microtime(TRUE);

$queries = Io\Deferred::When(
  $mysqlOne("SELECT 'Query 1', SLEEP(5)")
    ->done(
      function($result) use ($time) {
        var_dump(iterator_to_array($result));
        var_dump(microtime(TRUE) - $time);
      }
    ),
  $mysqlTwo("SELECT 'Query 2', SLEEP(1)")
    ->done(
      function($result) use ($time) {
        var_dump(iterator_to_array($result));
        var_dump(microtime(TRUE) - $time);
      }
    )
);

$loop = Io\Event\Loop\Factory::get();
Io\Event\Loop\Factory::run($queries);


