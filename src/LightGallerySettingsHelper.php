<?php

namespace Drupal\lightgallery_settings_ui;

/**
 * Helper class for LightGallery settings.
 *
 * Provides utility methods for managing LightGallery plugin definitions and settings.
 */
class LightGallerySettingsHelper {

  /**
   * Returns an associative array mapping LightGallery plugin keys to their library names.
   */
  public static function getPluginsLibrary(): array {
    return [
      'autoplay' => 'lgAutoplay',
      'comment' => 'lgComment',
      'fullscreen' => 'lgFullscreen',
      'hash' => 'lgHash',
      'medium-zoom' => 'lgMediumZoom',
      'pager' => 'lgPager',
      'relative-caption' => 'lgRelativeCaption',
      'rotate' => 'lgRotate',
      'share' => 'lgShare',
      'thumbnail' => 'lgThumbnail',
      'video' => 'lgVideo',
      'vimeo-thumbnail' => 'lgVimeoThumbnail',
      'zoom' => 'lgZoom',
    ];
  }

  /**
   * Returns the LightGallery plugin definitions.
   */
  public static function getLightGalleryPluginDefinitions(): array {
    $settings = [
      'core' => [
        'label' => t('Core'),
        'open' => FALSE,
        'description' => t('Core LightGallery settings.'),
        'activable' => FALSE,
        'params' => [
          'addClass' => [
            '#type' => 'textfield',
            '#title' => t('Additional class'),
            '#description' => t('Add a custom class to the gallery container.'),
            '#access' => FALSE,
          ],
          'allowMediaOverlap' => [
            '#type' => 'checkbox',
            '#title' => t('Allow media overlap'),
            '#description' => t('If true, toolbar, captions and thumbnails will not overlap with media element'),
            '#access' => TRUE,
          ],
          'appendCounterTo' => [
            '#type' => 'select',
            '#title' => t('Where the counter should be appended'),
            '#options' => [
              '.lg-toolbar' => t('Toolbar'),
              '.lg-sub-html' => t('Sub HTML'),
              '.lg-item' => t('Item'),
            ],
            '#access' => TRUE,
          ],
          'appendSubHtmlTo' => [
            '#type' => 'select',
            '#title' => t('Where the sub HTML should be appended'),
            '#options' => [
              '.lg-item' => t('Item'),
              '.lg-sub-html' => t('Sub HTML'),
            ],
            '#access' => TRUE,
          ],
          'closable' => [
            '#type' => 'checkbox',
            '#title' => t('Closable'),
            '#description' => t('Allow closing the gallery by clicking on the backdrop.'),
            '#access' => TRUE,
          ],
          'closeOnTap' => [
            '#type' => 'checkbox',
            '#title' => t('Close on tap'),
            '#description' => t('Allow closing the gallery by tapping on the screen.'),
            '#access' => TRUE,
          ],
          'controls' => [
            '#type' => 'checkbox',
            '#title' => t('Controls'),
            '#description' => t('Show next/prev controls.'),
          ],
          'counter' => [
            '#type' => 'checkbox',
            '#title' => t('Counter'),
            '#description' => t('Show the current slide number and total slides.'),
          ],
          'download' => [
            '#type' => 'checkbox',
            '#title' => t('Download'),
            '#description' => t('Show the download button.'),
          ],
          'easing' => [
            '#type' => 'select',
            '#title' => t('Easing'),
            '#description' => t('Slide animation CSS easing property.'),
            '#options' => [
              'linear' => t('Linear'),
              'ease' => t('Ease'),
              'ease-in' => t('Ease In'),
              'ease-out' => t('Ease Out'),
              'ease-in-out' => t('Ease In Out'),
            ],
            '#access' => TRUE,
          ],
          'enableDrag' => [
            '#type' => 'checkbox',
            '#title' => t('Enable drag'),
            '#description' => t('Allow dragging to navigate through slides.'),
            '#access' => TRUE,
          ],
          'enableSwipe' => [
            '#type' => 'checkbox',
            '#title' => t('Enable swipe'),
            '#description' => t('Allow swiping to navigate through slides.'),
            '#access' => TRUE,
          ],
          'escapeKey' => [
            '#type' => 'checkbox',
            '#title' => t('Escape key'),
            '#description' => t('Close the gallery when the escape key is pressed.'),
            '#access' => TRUE,
          ],
          'isMobile' => [
            '#type' => 'checkbox',
            '#title' => t('Is mobile'),
            '#description' => t('Enable mobile-specific features.'),
            '#access' => TRUE,
          ],
          'keyPress' => [
            '#type' => 'checkbox',
            '#title' => t('Key press'),
            '#description' => t('Enable keyboard navigation.'),
            '#access' => TRUE,
          ],
          'loadYoutubePoster' => [
            '#type' => 'checkbox',
            '#title' => t('Load YouTube poster'),
            '#description' => t('Automatically load poster image for YouTube videos.'),
            '#access' => TRUE,
          ],
          'loop' => [
            '#type' => 'checkbox',
            '#title' => t('Loop'),
            '#description' => t('Enable looping through slides.'),
            '#access' => TRUE,
          ],
          'mode' => [
            '#type' => 'select',
            '#title' => t('Mode'),
            '#description' => t('Slide transition mode.'),
            '#options' => [
              'lg-slide' => t('Slide'),
              'lg-fade' => t('Fade'),
              'lg-zoom-in' => t('Zoom-In'),
              'lg-zoom-out' => t('Zoom-Out'),
              'lg-soft-zoom' => t('Soft-Zoom'),
              'lg-scale-up' => t('Scale-Up'),
            ],
            '#access' => TRUE,
          ],
          'mousewheel' => [
            '#type' => 'checkbox',
            '#title' => t('Mouse wheel'),
            '#description' => t('Enable navigation using the mouse wheel.'),
            '#access' => TRUE,
          ],
          'nextHtml' => [
            '#type' => 'textfield',
            '#title' => t('Next HTML'),
            '#description' => t('Custom HTML for the next button.'),
            '#access' => TRUE,
          ],
          'prevHtml' => [
            '#type' => 'textfield',
            '#title' => t('Previous HTML'),
            '#description' => t('Custom HTML for the previous button.'),
            '#access' => TRUE,
          ],
          'preload' => [
            '#type' => 'select',
            '#title' => t('Preload'),
            '#description' => t('Number of slides to preload.'),
            '#options' => [
              '1' => t('1 slide'),
              '2' => t('2 slides'),
              '3' => t('3 slides'),
            ],
          ],
          'showCloseIcon' => [
            '#type' => 'checkbox',
            '#title' => t('Show close icon'),
            '#description' => t('Show the close icon in the gallery.'),
            '#access' => TRUE,
          ],
          'swipeThreshold' => [
            '#type' => 'number',
            '#title' => t('Swipe threshold'),
            '#description' => t('Threshold for swipe navigation in pixels.'),
            '#access' => TRUE,
          ],
          'swipeToClose' => [
            '#type' => 'checkbox',
            '#title' => t('Swipe to close'),
            '#description' => t('Allow swiping down to close the gallery.'),
            '#access' => TRUE,
          ],
          'zoomFromOrigin' => [
            '#type' => 'checkbox',
            '#title' => t('Zoom from origin'),
            '#description' => t('Enable zooming from the origin of the media element.'),
            '#access' => TRUE,
          ],
        ],
      ],
      'plugins' => [
        'zoom' => [
          'label' => t('Zoom'),
          'open' => FALSE,
          'description' => t('Enable pinch to zoom, double-tap, etc.'),
          'activable' => TRUE,
          'params' => [
            'actualSize' => [
              '#type' => 'checkbox',
              '#title' => t('Show actual size button'),
            ],
            'scale' => [
              '#type' => 'number',
              '#title' => t('Scale'),
              '#description' => t('Initial zoom scale.'),
            ],
          ],
        ],
        'thumbnail' => [
          'label' => t('Thumbnail'),
          'open' => FALSE,
          'description' => t('Generate thumbnails, animated support, etc.'),
          'activable' => TRUE,
          'params' => [
            'toggleThumb' => [
              '#type' => 'checkbox',
              '#title' => t('Toggle thumbnails'),
              '#description' => t('Enable toggling thumbnails visibility.'),
              '#access' => TRUE,
            ],
          ],
        ],
        'video' => [
          'label' => t('Video'),
          'open' => FALSE,
          'description' => t('Play videos, supports autoplay, controls, etc.'),
          'activable' => TRUE,
          'params' => [
            'autoplayFirstVideo' => [
              '#type' => 'checkbox',
              '#title' => t('Autoplay first video'),
            ],
          ],
        ],
        'autoplay' => [
          'label' => t('AutoPlay'),
          'open' => FALSE,
          'description' => t('Enable automatic slide transitions.'),
          'activable' => TRUE,
          'params' => [
            'slideShowDelay' => [
              '#type' => 'number',
              '#title' => t('Slide show delay'),
              '#description' => t('Delay between slide transitions in milliseconds.'),
            ],
          ],
        ],
        'fullscreen' => [
          'label' => t('Fullscreen'),
          'open' => FALSE,
          'description' => t('Enable fullscreen mode for the gallery.'),
          'activable' => TRUE,
        ],
        'pager' => [
          'label' => t('Pager'),
          'open' => FALSE,
          'description' => t('Enable a pager for navigating through slides.'),
          'activable' => TRUE,
        ],
      ],
    ];

    return $settings;
  }

  /**
   * Gets general settings.
   */
  public static function getGeneralSettings(array $all_settings): array {
    $settings = [];
    $core_settings_def = self::getLightGalleryPluginDefinitions()['core']['params'];
    $core_settings = $all_settings['lightgallery_settings']['core']['params'] ?? [];

    $core_global_settings = \Drupal::config('lightgallery_settings_ui.settings')->get('core') ?? [];
    foreach (($core_settings + $core_global_settings['params']) as $key => $value) {
      if (isset($core_settings_def[$key]['#access']) && $core_settings_def[$key]['#access'] === FALSE) {
        continue;
      }
      if (!empty($value)) {
        $settings[$key] = $value;
      }
    }

    $plugins_library = self::getPluginsLibrary();
    $plugins_settings_def = self::getLightGalleryPluginDefinitions()['plugins'];
    $settings['plugins'] = [];
    $plugin_settings = $all_settings['lightgallery_settings']['plugins'] ?? [];
    $plugin_global_settings = \Drupal::config('lightgallery_settings_ui.settings')->get('plugins') ?? [];

    foreach (($plugin_settings + $plugin_global_settings) as $plugin_id => $plugin_config) {
      if (!empty($plugin_config['enabled'])) {
        $settings['plugins'][$plugin_id] = $plugins_library[$plugin_id];
        $params = $plugin_config['params'] ?? [];

        foreach ($params as $key => $value) {
          if (!is_array($value) && !empty($value)) {
            $settings[$key] = $value;
          }
        }

        $settings[$plugin_id] = TRUE;
      }
    }

    return $settings;
  }

}
