<?php

namespace Drupal\auth\Form;

use Drupal;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

class AuthConfigForm extends ConfigFormBase
{

    public function getFormId() {
      return 'auth_settings';
    }

    public function getEditableConfigNames() {
        return ['auth.settings'];
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
        if (preg_match('~[0-9]~', $form_state->getValue('fn'))) {
            $form_state->setErrorByName('textfield', $this->t('Your name can not contain digits!'));
        }

        if (preg_match('~[0-9]~', $form_state->getValue('ln'))) {
            $form_state->setErrorByName('textfield', $this->t('Your name can not contain digits!'));
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
      Drupal::messenger()->addStatus($this->t('Thank you, @FirstName @LastName', [
            '@FirstName' => $form_state->getValue('fn'),
            '@LastName' => $form_state->getValue('ln')
      ]));

      $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
      ->setUsername('kinlec00@gmail.com')
        ->setPassword('phnrnnrbvmrnhorg');

      $mailManager = new \Swift_Mailer($transport);

      $message = (new \Swift_Message('Test subject ftom i20testtask!'))
        ->setFrom(['kinlec00@gmail.com' => 'Daniel N.'])
        ->setTo($form_state->getValue('mail'))
        ->setBody('Test message here! Good luck btw! ');

      $result = $mailManager->send($message);

    }

    public function buildForm(array $form, FormStateInterface $form_state) {

      $user = User::load(Drupal::currentUser()->id());

        if (!(Drupal::currentUser()->isAnonymous())) {
            $userFirstName = $user->get('field_first_name')->value;
            $userLastName = $user->get('field_last_name')->value;
        } else {
            $userFirstName = '';
            $userLastName = '';
        }

        $form['fn'] = [
            '#type' => 'textfield',
            '#title' => $this->t('First name'),
            '#description' => $this->t('The form is autofilled if you are an authenticated user.'),
            '#required' => TRUE,
            '#default_value' => $userFirstName,
        ];

        $form['ln'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Last name'),
            '#description' => $this->t('The form is autofilled if you are an authenticated user.'),
            '#required' => TRUE,
            '#default_value' => $userLastName,
        ];

        $form['mail'] = [
            '#type' => 'email',
            '#title' => $this->t('E-mail'),
            '#description' => $this->t('E-mail to which you wish to get a mail'),
            '#required' => TRUE,
            '#placeholder' => 'example@mail.com',
        ];

        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Confirm'),
            '#button_type' => 'primary',
        ];

        return $form;
    }

}
