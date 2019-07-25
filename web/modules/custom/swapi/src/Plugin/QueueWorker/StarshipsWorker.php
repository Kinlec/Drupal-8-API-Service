<?php

namespace Drupal\swapi\Plugin\QueueWorker;

use Drupal;
use Drupal\Core\Annotation\QueueWorker;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\node\Entity\Node;

/**
 * Processes Node Tasks.
 *
 * @QueueWorker(
 *   id = "starships_queue",
 *   title = @Translation("Starships Worker: Node"),
 *   cron = {"time" = 15}
 * )
 */
class StarshipsWorker extends QueueWorkerBase {

  public function processItem($data) {
    $service = Drupal::service('swapi.create_node');
    if ($data['type'] == 'starships') {
      $fields = [
        'type' => $data['type'],
        'title' => $data['name'],
        'langcode' => 'en',
        'uid' => 1,
        'status' => 0,
      ];
      $changeable_fields = [
        'field_name' => $data['name'],
        'field_cargo_capacity' => $data['cargo_capacity'],
        'field_consumables' => $data['consumables'],
        'field_cost_i' => $data['cost_in_credits'],
        'field_crew' => $data['crew'],
        'field_hyperdrive_rating' => $data['hyperdrive_rating'],
        'field_length' => $data['length'],
        'field_manufacturer' => $data['manufacturer'],
        'field_max_atmosphering_speed' => $data['max_atmosphering_speed'],
        'field_mglt' => $data['MGLT'],
        'field_model' => $data['model'],
        'field_passengers' => $data['passengers'],
        'field_starship_class' => $data['starship_class'],
        'field_created' => $data['created'],
        'field_ed' => $data['edited'],
        'field_url' => $data['url'],
      ];
      $service->createNode($data['name'], $fields, $changeable_fields, $data['edited']);
    }
  }
}
