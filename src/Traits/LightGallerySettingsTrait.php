<?php

namespace Drupal\lightgallery_settings_ui\Traits;

use Drupal\Core\Config\Config;
use Drupal\lightgallery_settings_ui\LightGallerySettingsHelper;

/**
 * Provides reusable logic for LightGallery settings forms.
 */
trait LightGallerySettingsTrait {

  /**
   * Returns an associative array mapping LightGallery plugin keys to their library names.
   *
   * @deprecated Use LightGallerySettingsHelper::getPluginsLibrary() instead.
   */
  public static function getPluginsLibrary(): array {
    return LightGallerySettingsHelper::getPluginsLibrary();
  }

  /**
   * Returns the LightGallery plugin definitions.
   *
   * @deprecated Use LightGallerySettingsHelper::getLightGalleryPluginDefinitions() instead.
   */
  public static function getLightGalleryPluginDefinitions(): array {
    return LightGallerySettingsHelper::getLightGalleryPluginDefinitions();
  }

  /**
   * Gets general settings.
   *
   * @deprecated Use LightGallerySettingsHelper::getGeneralSettings() instead.
   */
  public static function getGeneralSettings(array $all_settings): array {
    return LightGallerySettingsHelper::getGeneralSettings($all_settings);
  }

  /**
   * Builds the parameters form for a plugin.
   */
  private function buildParamsForm(
    array $plugin_data,
    array $plugin_config,
    string $label,
    string $prefix = '',
    bool $wrap = TRUE,
  ): array {
    $form = [];

    foreach ($plugin_data as $key => $element) {
      if (isset($element['#type'])) {
        $config_key = trim("$prefix.$key", '.');
        $element['#default_value'] = $plugin_config[$key] ?? '';
        $element['#config_target'] = "lightgallery.settings:$config_key";
        $form[$key] = $element;
      }
      elseif (is_array($element) && isset($element['params'])) {
        $config_key = trim("$prefix.$key.params", '.');
        $wrapper = [
          '#type' => 'details',
          '#title' => $element['label'] ?? ucfirst($key),
          '#description' => $element['description'] ?? '',
          '#open' => $element['open'] ?? FALSE,
          '#tree' => TRUE,
        ];

        $wrapper['params'] = $this->buildParamsForm(
          $element['params'],
          $plugin_config[$key]['params'] ?? [],
          $element['label'] ?? ucfirst($key),
          $config_key,
          FALSE
        );

        $form[$key] = $wrapper;
      }
    }

    if ($wrap) {
      return [
        '#type' => 'details',
        '#title' => $this->t('@label settings', ['@label' => $label]),
        '#open' => TRUE,
        '#tree' => TRUE,
      ] + $form;
    }

    return $form;
  }

  /**
   * Builds the plugin form.
   */
  private function buildPluginForm(array $plugin_definitions, array $plugin_config, string $type, array $parents): array {
    $form = [];

    foreach ($plugin_definitions as $plugin_key => $plugin_data) {
      $config = $plugin_config[$plugin_key] ?? [];

      if ($type !== 'core') {
        $form[$plugin_key] = [
          '#type' => 'details',
          '#title' => $plugin_data['label'] ?? ucfirst($plugin_key),
          '#description' => $plugin_data['description'] ?? '',
          '#open' => $plugin_data['open'] ?? FALSE,
          '#tree' => TRUE,
          '#parents' => array_merge($parents, [$plugin_key]),
        ];
      }

      if (!empty($plugin_data['activable'])) {
        $form[$plugin_key]['enabled'] = [
          '#type' => 'checkbox',
          '#title' => $this->t('Enable'),
          '#default_value' => $config['enabled'] ?? FALSE,
          '#config_target' => "lightgallery.settings:plugins.$plugin_key.enabled",
          '#parents' => array_merge($parents, [$plugin_key, 'enabled']),
        ];
      }

      if (!empty($plugin_data['params'])) {
        $params_wrapper = $this->buildParamsForm(
          $plugin_data['params'],
          $config['params'] ?? [],
          $plugin_data['label'] ?? ucfirst($plugin_key),
          "$type.$plugin_key.params"
        );

        if (!empty($plugin_data['activable'])) {
          $params_wrapper['#states'] = $this->buildStatesFromParents(array_merge($parents, [$plugin_key, 'enabled']));
        }

        $form[$plugin_key]['params'] = $params_wrapper;
      }
    }

    return $form;
  }

  /**
   * Builds core settings form.
   */
  protected function buildCoreSettingsForm(array $plugin_definitions, $plugin_config, array $parents): array {
    if ($plugin_config instanceof Config) {
      $plugin_config = $plugin_config->get('core') ?? [];
    }
    elseif (is_array($plugin_config)) {
      $plugin_config = $plugin_config['core'] ?? [];
    }
    else {
      $plugin_config = [];
    }

    $form = $this->buildPluginForm(
      ['core' => $plugin_definitions['core']],
      ['core' => $plugin_config],
      '',
      $parents
    )['core'];

    return $form;
  }

  /**
   * Builds plugin settings form.
   */
  protected function buildPluginSettingsForm(array $plugin_definitions, $plugin_config, array $parents): array {
    if ($plugin_config instanceof Config) {
      $plugin_config = $plugin_config->get('plugins') ?? [];
    }
    elseif (is_array($plugin_config)) {
      $plugin_config = $plugin_config['plugins'] ?? [];
    }
    else {
      $plugin_config = [];
    }

    $form = $this->buildPluginForm(
      $plugin_definitions['plugins'],
      $plugin_config,
      'plugins',
      $parents
    );

    return $form;
  }

  /**
   * Builds states from parents.
   */
  protected function buildStatesFromParents(array $parents, string $expected_value = '1'): array {
    $flat_name = '';
    foreach ($parents as $depth => $part) {
      $flat_name .= ($depth === 0 ? '' : '[') . $part . ($depth > 0 ? ']' : '');
    }

    return [
      'visible' => [
        ":input[name='{$flat_name}']" => ['checked' => TRUE],
      ],
    ];
  }

}
