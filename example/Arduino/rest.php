<?php
include('../../src/Carica/Io/Loader.php');
Carica\Io\Loader::register();

use Carica\Io;
use Carica\Io\Firmata;
use Carica\Io\Network\Http;

$board = new Io\Firmata\Board(
  new Io\Stream\Tcp('127.0.0.1', 5339)
);
$route = new Carica\Io\Network\Http\Route();
$route->match(
  '/pins',
  new Firmata\Rest\Pins($board)
);

$loop = Io\Event\Loop\Factory::get();

$board
  ->activate()
  ->done(
    function () {
      $server = new Carica\Io\Network\Server();
      $server->events()->on(
        'connection',
        function ($stream) use ($route) {
          $request = new Carica\Io\Network\Http\Connection($stream);
          $request->events()->on(
            'request',
            function ($request) use ($route) {
              echo $request->method.' '.$request->url."\n";
              if ($response = $route($request)) {
                $response->send();
              }
              $request->connection()->close();
            }
          );
        }
      );
      $server->listen(8080);
    }
  )
  ->fail(
    function ($error) {
      echo $error."\n";
    }
  );


Carica\Io\Event\Loop\Factory::run();