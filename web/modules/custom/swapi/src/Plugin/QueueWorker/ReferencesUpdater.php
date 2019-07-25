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
 *   id = "reference_queue",
 *   title = @Translation("References Worker: Node"),
 *   cron = {"time" = 30}
 *
 * )
 */
class ReferencesUpdater extends QueueWorkerBase {

  public function processItem($data) {
    $service = Drupal::service('swapi.reference_updater');
    switch ($data['type']) {
      case 'films':
        $array_fields = [
          'field_planets' => $data['planets'],
          'field_people' => $data['characters'],
          'field_species' => $data['species'],
          'field_vehicles' => $data['vehicles'],
          'field_starships' => $data['starships'],
        ];
        $node = Drupal::entityTypeManager()
          ->getStorage('node')
          ->loadByProperties([
            'title' => $data['name'],
            'type' => $data['type'],
          ]);
        $node = Node::load(key($node));
        foreach ($array_fields as $field => $value) {
          foreach ($value as $link) {
            $node_url = Drupal::entityTypeManager()
              ->getStorage('node')
              ->loadByProperties(['field_url' => $link]);
            $node->{$field}[] = ['target_id' => key($node_url)];
          }
        }
        $node->save();
        break;
      case 'people':
        $array_fields = [
          'field_planets' => $data['homeworld'],
          'field_films' => $data['films'],
          'field_species' => $data['species'],
          'field_vehicles' => $data['vehicles'],
          'field_starships' => $data['starships'],
        ];
        $service->updateReferences($array_fields, $data['name'], 'people');
        break;
      case 'planets':
        $array_fields = [
          'field_films' => $data['films'],
          'field_people' => $data['residents'],
        ];
        $service->updateReferences($array_fields, $data['name'], 'planets');
        break;
      case 'species':
        $array_fields = [
          'field_films' => $data['films'],
          'field_people' => $data['people'],
          'field_planets' => $data['homeworld'],
        ];
        $service->updateReferences($array_fields, $data['name'], 'species');
        break;
      case 'vehicles':
        $array_fields = [
          'field_films' => $data['films'],
          'field_planets' => $data['pilots'],
        ];
        $service->updateReferences($array_fields, $data['name'], 'vehicles');
        break;
      case 'starships':
        $array_fields = [
          'field_films' => $data['films'],
          'field_planets' => $data['pilots'],
        ];
        $service->updateReferences($array_fields, $data['name'], 'starships');
        break;
    }
  }
}