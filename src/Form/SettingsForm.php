<?php

namespace Drupal\custom_company_token\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Configure Custom Company Token settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_company_token_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['custom_company_token.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = \Drupal::config('custom_company_token.settings');

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#default_value' => $config->get('name'),
      '#description' => t('Usage: <span class="placeholder">@data</span>', ['@data' => '[company:name]'])
    ];
    $form['description'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Description'),
      '#default_value' => $config->get('description'),
      '#description' => t('Usage: <span class="placeholder">@data</span>', ['@data' => '[company:description]'])
    ];
    $form['street'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Street'),
      '#default_value' => $config->get('street'),
      '#description' => t('Usage: <span class="placeholder">@data</span>', ['@data' => '[company:street]'])
    ];
    $form['plz'] = [
      '#type' => 'number',
      '#size' => 5,
      '#maxlength' => 5,
      '#title' => $this->t('PLZ'),
      '#default_value' => $config->get('plz'),
      '#description' => t('Usage: <span class="placeholder">@data</span>', ['@data' => '[company:plz]'])
    ];
    $form['locality'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Locality'),
      '#default_value' => $config->get('locality'),
      '#description' => t('Usage: <span class="placeholder">@data</span>', ['@data' => '[company:locality]'])
    ];
    $form['phone'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone'),
      '#default_value' => $config->get('phone'),
      '#description' => t('Usage: <span class="placeholder">@data</span> or as link: <span class="placeholder">@data2</span>', ['@data' => '[company:phone]','@data2' => '[company:phonelink]'])
    ];
    $form['fax'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Fax'),
      '#default_value' => $config->get('fax'),
      '#description' => t('Usage: <span class="placeholder">@data</span>', ['@data' => '[company:fax]'])
    ];
    $defaultmail = ($config->get('mail')) ? $config->get('mail') : \Drupal::config('system.site')->get('mail');
    $form['mail'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Mail'),
      '#default_value' => $defaultmail,
      '#description' => t('Usage: <span class="placeholder">@data</span> or as link: <span class="placeholder">@data2</span>', ['@data' => '[company:mail]','@data2' => '[company:maillink]'])
    ];
    $mapslink = 'https://www.google.com/maps/place/'.$config->get('street').','.$config->get('plz').'+'.$config->get('locality');
    $form['map'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL to Map'),
      '#default_value' => $config->get('map'),
      '#description' => t('Usage: <span class="placeholder">@data</span> or as link: <span class="placeholder">@data2</span>', ['@data' => '[company:map]','@data2' => '[company:maplink]']).'<br><a href="'.$mapslink.'" target="_blank">www.google.com/maps</a>'
    ];
    // Free Api: https://nominatim.openstreetmap.org/search?q=Engelsgrube+25,+23552+Lübeck,+DE&format=json
    $form['latitude'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Latitude'),
      '#default_value' => $config->get('latitude'),
      '#description' => t('Usage: <span class="placeholder">@data</span>', ['@data' => '[company:latitude]']).'<br>"53.123" <a href="https://www.latlong.net" target="_blank">www.latlong.net</a><br>Or: Right click on place on <a href="'.$mapslink.'" target="_blank">www.google.com/maps</a>'
    ];
    $form['longitude'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Longitude'),
      '#default_value' => $config->get('longitude'),
      '#description' => t('Usage: <span class="placeholder">@data</span>', ['@data' => '[company:longitude]']).'<br>"10.123" <a href="https://www.latlong.net" target="_blank">www.latlong.net</a><br>Or: Right click on place on <a href="'.$mapslink.'" target="_blank">www.google.com/maps</a>'
    ];

    // Media-Browser:
    // https://www.drupal.org/node/2418529
    // (Extended Version: https://www.drupal.org/project/media_library_form_element)
    $fid = $config->get('image');
    $file = (isset($fid) ? \Drupal\media\Entity\Media::load($fid) : null);
    $form['image'] = array(
      '#type' => 'entity_autocomplete',
      '#target_type'   => 'media',
      '#title' => $this->t('Image'),
      '#description' => t('Usage: <span class="placeholder">@data</span>', ['@data' => '[company:image]']).'<br>'.t('Representative image for the content of the website (<a target="_blank" href="/admin/content/media" alt="Content Media">Media</a>)'),
      '#default_value' => $file,
    );

    $form['color'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Basecolor (Hex)'),
      '#default_value' => $config->get('color'),
      '#description' => t('Usage: <span class="placeholder">@data</span>', ['@data' => '[company:color]'])
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
/*     if ($form_state->getValue('example') != 'example') {
      $form_state->setErrorByName('example', $this->t('The value is not correct.'));
    } */
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('custom_company_token.settings')
      ->set('name', $form_state->getValue('name'))
      ->set('description', $form_state->getValue('description'))
      ->set('street', $form_state->getValue('street'))
      ->set('plz', $form_state->getValue('plz'))
      ->set('locality', $form_state->getValue('locality'))
      ->set('phone', $form_state->getValue('phone'))
      ->set('fax', $form_state->getValue('fax'))
      ->set('mail', $form_state->getValue('mail'))
      ->set('map', $form_state->getValue('map'))
      ->set('latitude', $form_state->getValue('latitude'))
      ->set('longitude', $form_state->getValue('longitude'))
      ->set('image', $form_state->getValue('image'))
      ->set('color', $form_state->getValue('color'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
