<?php


namespace Drupal\swapi\Services;

use Drupal;
use Drupal\node\Entity\Node;

class SwapiReferenceUpdater {

  public function updateReferences($array, $name, $type) {
    $node = Drupal::entityTypeManager()
      ->getStorage('node')
      ->loadByProperties([
        'field_name' => $name,
        'type' => $type,
      ]);
    $node = Node::load(key($node));
    foreach ($array as $field => $value) {
      foreach ($value as $link) {
        $node_url = Drupal::entityTypeManager()
          ->getStorage('node')
          ->loadByProperties(['field_url' => $link]);
        $node->{$field}[] = ['target_id' => key($node_url)];
      }
    }
    $node->save();
  }
}