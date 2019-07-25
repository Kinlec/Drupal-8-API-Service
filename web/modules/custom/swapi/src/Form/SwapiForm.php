<?php

namespace Drupal\swapi\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Render\FormattableMarkup;


class SwapiForm extends FormBase {

  public function getFormId() {
    return 'queue_node_form';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Create queues for each type'),
    ];

    return $form;
  }


  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $service = Drupal::service('swapi.root_api');
    $types = [
      'planets' => 'https://swapi.co/api/planets/',
      'films' => 'https://swapi.co/api/films/',
      'species' => 'https://swapi.co/api/species/',
      'people' => 'https://swapi.co/api/people/',
      'starships' => 'https://swapi.co/api/starships/',
      'vehicles' => 'https://swapi.co/api/vehicles/',
    ];
    foreach ($types as $type => $link) {
      $service->getData($link, $type);
    }
  }
}