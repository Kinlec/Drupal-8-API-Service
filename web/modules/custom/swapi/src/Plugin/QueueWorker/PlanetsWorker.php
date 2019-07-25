<?php

namespace Drupal\swapi\Plugin\QueueWorker;

use Drupal\Core\Annotation\QueueWorker;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\node\Entity\Node;
use Drupal;

/**
 * Processes Node Tasks.
 *
 * @QueueWorker(
 *   id = "planets_queue",
 *   title = @Translation("Planets Worker: Node"),
 *   cron = {"time" = 15}
 * )
 */
class PlanetsWorker extends QueueWorkerBase {

  public function processItem($data) {
    $service = Drupal::service('swapi.create_node');
    if ($data['type'] == 'planets') {
      $fields = [
        'type' => $data['type'],
        'title' => $data['name'],
        'langcode' => 'en',
        'uid' => 1,
        'status' => 0,
      ];
      $changeable_fields = [
        'field_name' => $data['name'],
        'field_climate' => $data['climate'],
        'field_diameter' => $data['diameter'],
        'field_gravity' => $data['gravity'],
        'field_orbital_period' => $data['orbital_period'],
        'field_population' => $data['population'],
        'field_rotation_period' => $data['rotation_period'],
        'field_surface_water' => $data['surface_water'],
        'field_terrain' => $data['terrain'],
        'field_created' => $data['created'],
        'field_ed' => $data['edited'],
        'field_url' => $data['url'],
      ];
      $service->createNode($data['name'], $fields, $changeable_fields, $data['edited']);
    }
  }
}
