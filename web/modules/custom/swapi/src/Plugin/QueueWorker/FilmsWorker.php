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
 *   id = "films_queue",
 *   title = @Translation("Films Worker: Node"),
 *   cron = {"time" = 15}
 *
 * )
 */
class FilmsWorker extends QueueWorkerBase {

  public function processItem($data) {
    if ($data['type'] == 'films') {
      $fields = [
        'type' => $data['type'],
        'title' => $data['title'],
        'langcode' => 'en',
        'uid' => 1,
        'status' => 0,
      ];
      $changeable_fields = [
        'field_director' => $data['director'],
        'field_producer' => $data['producer'],
        'field_episode_id' => $data['episode_id'],
        'field_opening_crawl' => $data['opening_crawl'],
        'field_release_date' => $data['release_date'],
        'field_created' => $data['created'],
        'field_ed' => $data['edited'],
        'field_url' => $data['url'],
      ];
      $result = Drupal::entityTypeManager()
        ->getStorage('node')
        ->loadByProperties(['title' => $data['title']]);
      $current_time = Drupal::time()->getCurrentTime();
      $edited = strtotime($data['edited']);

      if (empty($result)) {
        $node = Node::create(array_merge($fields, $changeable_fields));
        $node->save();
      }

      if (!empty($result) && $edited >= $current_time) {
        $id = key($result);
        $node = Node::load($id);
        foreach ($changeable_fields as $field => $value) {
          $node->{$field} = $value;
        }
        $node->save();
      }
    }
  }

}
