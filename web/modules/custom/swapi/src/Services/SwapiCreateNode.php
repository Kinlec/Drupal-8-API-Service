<?php


namespace Drupal\swapi\Services;

use Drupal;
use Drupal\node\Entity\Node;

class SwapiCreateNode {

  public function createNode($name, $static_fields, $dynamic_fields, $time_edited) {
    $result = Drupal::entityTypeManager()
      ->getStorage('node')
      ->loadByProperties(['title' => $name]);
    $current_time = Drupal::time()->getCurrentTime();
    $edited = strtotime($time_edited);

    if (empty($result)) {
      $node = Node::create(array_merge($static_fields, $dynamic_fields));
      $node->save();
    }

    if (!empty($result) && $edited >= $current_time) {
      $id = key($result);
      $node = Node::load($id);
      foreach ($dynamic_fields as $field => $value) {
        $node->{$field} = $value;
      }
      $node->save();
    }
  }
}