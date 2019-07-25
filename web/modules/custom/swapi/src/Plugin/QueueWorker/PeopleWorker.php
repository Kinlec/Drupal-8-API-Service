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
 *   id = "people_queue",
 *   title = @Translation("People Worker: Node"),
 *   cron = {"time" = 15}
 * )
 */
class PeopleWorker extends QueueWorkerBase {

  public function processItem($data) {
    $service = Drupal::service('swapi.create_node');
    if ($data['type'] == 'people') {
      $fields = [
        'type' => $data['type'],
        'title' => $data['name'],
        'langcode' => 'en',
        'uid' => 1,
        'status' => 0,
      ];
      $changeable_fields = [
        'field_name' => $data['name'],
        'field_height' => $data['height'],
        'field_mass' => $data['mass'],
        'field_hair_color' => $data['hair_color'],
        'field_skin_color' => $data['skin_color'],
        'field_eye_color' => $data['eye_color'],
        'field_birth_year' => $data['birth_year'],
        'field_gender' => $data['gender'],
        'field_created' => $data['created'],
        'field_ed' => $data['edited'],
        'field_url' => $data['url'],
      ];
      $service->createNode($data['name'], $fields, $changeable_fields, $data['edited']);
    }
  }

}
