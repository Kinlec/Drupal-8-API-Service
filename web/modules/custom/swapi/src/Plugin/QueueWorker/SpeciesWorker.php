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
 *   id = "species_queue",
 *   title = @Translation("Species Worker: Node"),
 *   cron = {"time" = 15}
 * )
 */
class SpeciesWorker extends QueueWorkerBase {

  public function processItem($data) {
    $service = Drupal::service('swapi.create_node');
    if ($data['type'] == 'species') {
      $fields = [
        'type' => $data['type'],
        'title' => $data['name'],
        'langcode' => 'en',
        'uid' => 1,
        'status' => 0,
      ];
      $changeable_fields = [
        'field_name' => $data['name'],
        'field_average_height' => $data['average_height'],
        'field_average_lifespan' => $data['average_lifespan'],
        'field_classification' => $data['classification'],
        'field_designation' => $data['designation'],
        'field_eye_color' => $data['eye_colors'],
        'field_hair_color' => $data['hair_colors'],
        'field_language' => $data['language'],
        'field_skin_color' => $data['skin_colors'],
        'field_created' => $data['created'],
        'field_ed' => $data['edited'],
        'field_url' => $data['url'],
      ];
      $service->createNode($data['name'], $fields, $changeable_fields, $data['edited']);
    }
  }
}
