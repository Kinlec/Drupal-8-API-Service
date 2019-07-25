<?php

namespace Drupal\swapi\Services;

use Drupal;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;


class SwapiRootAPI {

  public function getData($url, $type) {
    $client = new Client();
    $queue = Drupal::queue($type . '_queue');
    $reference_queue = Drupal::queue('reference_queue');
    $queue->createQueue();
    $reference_queue->createQueue();
    $something_else = [];
    $i = 1;
    do {
      try {
        $request = $client->request('GET', $url . '?page=' . $i);
      } catch (RequestException $exception) {
        echo Psr7\str($exception->getRequest());
        if ($exception->hasResponse()) {
          echo Psr7\str($exception->getResponse());
        }
      }
      $request = \GuzzleHttp\json_decode($request->getBody(), TRUE);
      $next = $request["next"];

      foreach ($request["results"] as $something) {
        foreach ($something as $key => $value) {
          $something['type'] = $type;
          $something_else['type'] = $type;
          if ($type == 'films') {
            $something_else['name'] = $something['title'];
          }
          else {
            $something_else['name'] = $something['name'];
          }
          if (!is_array($value) && $value != $something['homeworld']) {
            $something[$key] = $value;
          }
          else {
            if (empty($value)) {
              unset($something_else[$key], $something[$key]);
            }
            else {
              $something_else[$key] = $value;
              unset($something[$key]);
            }
          }
        }
        $queue->createItem($something);
        $reference_queue->createItem($something_else);
      }

      $i++;
    } while (!is_null($next));
    unset($something);
    unset($something_else);
  }
}