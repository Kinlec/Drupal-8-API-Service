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
 *   id = "vehicles_queue",
 *   title = @Translation("Vehicles Worker: Node"),
 *   cron = {"time" = 15}
 * )
 */
class VehiclesWorker extends QueueWorkerBase {

  public function processItem($data) {
    $service = Drupal::service('swapi.create_node');
    if ($data['type'] == 'vehicles') {
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
        'field_cost_in_credits' => $data['cost_in_credits'],
        'field_crew' => $data['crew'],
        'field_length' => $data['length'],
        'field_manufacturer' => $data['manufacturer'],
        'field_max_atmosphering_speed' => $data['max_atmosphering_speed'],
        'field_model' => $data['model'],
        'field_passengers' => $data['passengers'],
        'field_vehicle_class' => $data['vehicle_class'],
        'field_created' => $data['created'],
        'field_edited' => $data['edited'],
        'field_url' => $data['url'],
      ];
      $service->createNode($data['name'], $fields, $changeable_fields, $data['edited']);
    }
  }
}
