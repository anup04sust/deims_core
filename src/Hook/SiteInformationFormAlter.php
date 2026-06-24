<?php

declare(strict_types=1);

namespace Drupal\deims_core\Hook;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;

/**
 * Alters Drupal core site information form for DEIMS.
 */
final class SiteInformationFormAlter {

  /**
   * Alters the Basic site settings form.
   *
   * Implements hook_form_FORM_ID_alter().
   */
  #[Hook('form_system_site_information_settings_alter')]
  public function alterSiteInformationForm(array &$form, FormStateInterface $form_state, string $form_id): void {
    $config = \Drupal::config('deims_core.institute_settings');

    $form['deims_institute_information'] = [
      '#type' => 'details',
      '#title' => t('DEIMS Institute Information'),
      '#open' => TRUE,
      '#weight' => 20,
    ];

    $form['deims_institute_information']['deims_eiin_number'] = [
      '#type' => 'textfield',
      '#title' => t('EIIN Number'),
      '#default_value' => $config->get('eiin_number') ?? '',
      '#maxlength' => 20,
      '#required' => TRUE,
      '#description' => t('Enter the official EIIN number of the institute.'),
    ];

    $form['deims_institute_information']['deims_institution_type'] = [
      '#type' => 'select',
      '#title' => t('Institution Type'),
      '#default_value' => $config->get('institution_type') ?? '',
      '#options' => [
        '' => t('- Select -'),
        'high_school' => t('High School'),
        'primary_school' => t('Primary School'),
        'college' => t('College'),
        'school_and_college' => t('School & College'),
      ],
      '#required' => TRUE,
    ];

    $form['deims_institute_information']['deims_education_board'] = [
      '#type' => 'select',
      '#title' => t('Education Board'),
      '#default_value' => $config->get('education_board') ?? '',
      '#options' => [
        '' => t('- Select -'),
        'dhaka' => t('Dhaka'),
        'rajshahi' => t('Rajshahi'),
        'comilla' => t('Comilla'),
        'jessore' => t('Jessore'),
        'chittagong' => t('Chittagong'),
        'barisal' => t('Barisal'),
        'sylhet' => t('Sylhet'),
        'dinajpur' => t('Dinajpur'),
      ],
      '#required' => TRUE,
    ];

    $form['deims_institute_information']['deims_mpo_status'] = [
      '#type' => 'radios',
      '#title' => t('MPO Status'),
      '#default_value' => $config->get('mpo_status') ?? 'no',
      '#options' => [
        'yes' => t('Yes'),
        'no' => t('No'),
      ],
      '#required' => TRUE,
    ];

    $form['deims_institute_information']['deims_institute_code'] = [
      '#type' => 'textfield',
      '#title' => t('Institute Code'),
      '#default_value' => $config->get('institute_code') ?? '',
      '#maxlength' => 50,
    ];

    $form['deims_institute_information']['deims_establishment_year'] = [
      '#type' => 'textfield',
      '#title' => t('Establishment Year'),
      '#default_value' => $config->get('establishment_year') ?? '',
      '#maxlength' => 4,
      '#description' => t('Example: 1998'),
    ];

    $form['#validate'][] = [$this, 'validateSiteInformationForm'];
    $form['#submit'][] = [$this, 'submitSiteInformationForm'];
  }

  /**
   * Validates DEIMS institute fields.
   */
  public function validateSiteInformationForm(array &$form, FormStateInterface $form_state): void {
    $eiin_number = trim((string) $form_state->getValue('deims_eiin_number'));
    $establishment_year = trim((string) $form_state->getValue('deims_establishment_year'));

    if ($eiin_number !== '' && !preg_match('/^[0-9]+$/', $eiin_number)) {
      $form_state->setErrorByName('deims_eiin_number', t('EIIN Number should contain numbers only.'));
    }

    if ($establishment_year !== '' && !preg_match('/^[0-9]{4}$/', $establishment_year)) {
      $form_state->setErrorByName('deims_establishment_year', t('Establishment Year must be a valid 4-digit year.'));
      return;
    }

    if ($establishment_year !== '') {
      $year = (int) $establishment_year;
      $current_year = (int) date('Y');

      if ($year < 1800 || $year > $current_year) {
        $form_state->setErrorByName('deims_establishment_year', t('Establishment Year must be between 1800 and @year.', [
          '@year' => $current_year,
        ]));
      }
    }
  }

  /**
   * Saves DEIMS institute fields.
   */
  public function submitSiteInformationForm(array &$form, FormStateInterface $form_state): void {
    \Drupal::configFactory()
      ->getEditable('deims_core.institute_settings')
      ->set('eiin_number', trim((string) $form_state->getValue('deims_eiin_number')))
      ->set('institution_type', $form_state->getValue('deims_institution_type'))
      ->set('education_board', $form_state->getValue('deims_education_board'))
      ->set('mpo_status', $form_state->getValue('deims_mpo_status'))
      ->set('institute_code', trim((string) $form_state->getValue('deims_institute_code')))
      ->set('establishment_year', trim((string) $form_state->getValue('deims_establishment_year')))
      ->save();
  }

}