<?php

namespace Drupal\lightgallery_settings_ui\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\lightgallery_settings_ui\Traits\LightGallerySettingsTrait;

/**
 * Provides a form to edit the LightGallery settings.
 */
class SettingsForm extends ConfigFormBase {
  use LightGallerySettingsTrait;

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['lightgallery_settings_ui.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'lightgallery_settings_ui_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $definitions = $this->getLightGalleryPluginDefinitions();
    $config = $this->config('lightgallery.settings');

    $form['#tree'] = TRUE;

    // Core settings block.
    $form['core'] = $this->buildCoreSettingsForm(
      $definitions,
      $config ?? [],
      [
        'lightgallery_settings',
        'core',
        'params',
      ]
    );

    // Plugins settings block.
    $form['plugins'] = [
      '#type' => 'details',
      '#title' => $this->t('LightGallery Plugins'),
      '#description' => $this->t('Configure the LightGallery plugins.'),
      '#open' => TRUE,
      '#tree' => TRUE,
    ];
    $form['plugins'] += $this->buildPluginSettingsForm(
      $definitions,
      $config ?? [],
      [
        'lightgallery_settings',
        'plugins',
      ]
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $config = $this->config('lightgallery_settings_ui.settings');

    $values = $form_state->getValues();

    // Save core settings.
    if (isset($values['core'])) {
      $config->set('core', $values['core']);
    }

    // Save plugins settings.
    if (isset($values['plugins'])) {
      $config->set('plugins', $values['plugins']);
    }

    $config->save();

    parent::submitForm($form, $form_state);
  }

}