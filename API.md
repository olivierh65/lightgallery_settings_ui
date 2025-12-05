# API Documentation - LightGallery Settings UI

## Vue d'ensemble

Ce module fournit un trait réutilisable et des hooks pour gérer les paramètres LightGallery.

## Trait : LightGallerySettingsTrait

### Méthodes publiques

#### `getPluginsLibrary(): array`
Retourne la correspondance entre les identifiants de plugins et leurs noms de bibliothèque.

```php
$libraries = LightGallerySettingsTrait::getPluginsLibrary();
// ['zoom' => 'lgZoom', 'thumbnail' => 'lgThumbnail', ...]
```

#### `getLightGalleryPluginDefinitions(): array`
Retourne les définitions complètes des plugins avec leurs paramètres.

```php
$definitions = LightGallerySettingsTrait::getLightGalleryPluginDefinitions();
// Structure:
// [
//   'core' => [...],
//   'plugins' => [
//     'zoom' => ['label' => '...', 'params' => [...]],
//     ...
//   ]
// ]
```

#### `getGeneralSettings(array $all_settings): array`
Compile les paramètres depuis la configuration globale et locale.

```php
$settings = LightGallerySettingsTrait::getGeneralSettings([
  'lightgallery_settings' => [
    'core' => ['params' => [...]],
    'plugins' => [...]
  ]
]);
```

#### `buildCoreSettingsForm(array $definitions, $config, array $parents): array`
Construit le formulaire pour les paramètres de base.

#### `buildPluginSettingsForm(array $definitions, $config, array $parents): array`
Construit le formulaire pour les paramètres des plugins.

## Hooks disponibles

### hook_lightgallery_settings_ui_definitions_alter(&$definitions)
Permet de modifier les définitions des plugins.

```php
/**
 * Implements hook_lightgallery_settings_ui_definitions_alter().
 */
function mymodule_lightgallery_settings_ui_definitions_alter(&$definitions) {
  // Ajouter un nouveau paramètre au plugin zoom
  $definitions['plugins']['zoom']['params']['myCustomParam'] = [
    '#type' => 'checkbox',
    '#title' => t('My custom parameter'),
  ];
}
```

### hook_preprocess_lightgallery()
Le module implémente ce hook pour fusionner les paramètres globaux. Vous pouvez aussi l'utiliser.

```php
function mymodule_preprocess_lightgallery(&$variables) {
  // Modifier les paramètres avant l'affichage
  $variables['settings']['custom_option'] = TRUE;
}
```

## Configuration API

### Structure de configuration

```yaml
lightgallery.settings:
  core:
    params:
      licenseKey: '0000-0000-0000-0000'
      controls: true
      counter: true
      # ...
  plugins:
    zoom:
      enabled: true
      params:
        actualSize: true
        scale: 1
    thumbnail:
      enabled: true
      params:
        toggleThumb: true
```

### Accès par code

```php
// Lecture
$config = \Drupal::config('lightgallery.settings');
$license = $config->get('core.params.licenseKey');
$zoom_enabled = $config->get('plugins.zoom.enabled');

// Écriture
$config = \Drupal::configFactory()->getEditable('lightgallery.settings');
$config->set('core.params.licenseKey', 'NEW-KEY');
$config->set('plugins.zoom.enabled', TRUE);
$config->save();
```

## Utilisation dans un formatter personnalisé

```php
namespace Drupal\mymodule\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\lightgallery_settings_ui\Traits\LightGallerySettingsTrait;

/**
 * @FieldFormatter(
 *   id = "my_lightgallery_formatter",
 *   label = @Translation("My LightGallery Formatter"),
 *   field_types = {"image"}
 * )
 */
class MyLightGalleryFormatter extends FormatterBase {
  use LightGallerySettingsTrait;

  public static function defaultSettings() {
    return [
      'lightgallery_settings' => [
        'core' => [],
        'plugins' => [],
      ],
    ] + parent::defaultSettings();
  }

  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);
    
    $config = \Drupal::config('lightgallery.settings');
    
    $form['lightgallery_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('LightGallery'),
      '#tree' => TRUE,
    ];
    
    $form['lightgallery_settings']['core'] = $this->buildCoreSettingsForm(
      $this->getLightGalleryPluginDefinitions(),
      $config,
      ['settings', 'lightgallery_settings', 'core']
    );
    
    $form['lightgallery_settings']['plugins'] = [
      '#type' => 'details',
      '#title' => $this->t('Plugins'),
      '#tree' => TRUE,
    ];
    
    $form['lightgallery_settings']['plugins'] += $this->buildPluginSettingsForm(
      $this->getLightGalleryPluginDefinitions(),
      $config,
      ['settings', 'lightgallery_settings', 'plugins']
    );
    
    return $form;
  }

  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $settings = static::getGeneralSettings($this->getSettings());
    
    // Utiliser $settings dans votre rendu
    $elements['#attached']['drupalSettings']['lightgallery'] = $settings;
    
    return $elements;
  }
}
```

## Services disponibles

Le module n'injecte pas de services directement, mais utilise les services Drupal standards :

```php
// Configuration
$config = \Drupal::config('lightgallery.settings');

// Config Factory (éditable)
$config = \Drupal::configFactory()->getEditable('lightgallery.settings');
```

## Étendre le module

### Ajouter un nouveau plugin LightGallery

1. Modifiez les définitions via le hook :

```php
function mymodule_lightgallery_settings_ui_definitions_alter(&$definitions) {
  $definitions['plugins']['my_plugin'] = [
    'label' => t('My Custom Plugin'),
    'open' => FALSE,
    'description' => t('Description of my plugin'),
    'activable' => TRUE,
    'params' => [
      'option1' => [
        '#type' => 'checkbox',
        '#title' => t('Option 1'),
      ],
      'option2' => [
        '#type' => 'textfield',
        '#title' => t('Option 2'),
      ],
    ],
  ];
}
```

2. Ajoutez le nom de la bibliothèque dans le trait (ou via hook) :

```php
function mymodule_lightgallery_plugins_library_alter(&$libraries) {
  $libraries['my_plugin'] = 'lgMyPlugin';
}
```

### Ajouter des validations personnalisées

```php
/**
 * Implements hook_form_FORM_ID_alter().
 */
function mymodule_form_lightgallery_settings_ui_settings_form_alter(&$form, $form_state) {
  $form['#validate'][] = 'mymodule_lightgallery_settings_validate';
}

function mymodule_lightgallery_settings_validate($form, FormStateInterface $form_state) {
  $license = $form_state->getValue(['core', 'params', 'licenseKey']);
  
  if (!preg_match('/^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/', $license)) {
    $form_state->setErrorByName('core][params][licenseKey', 
      t('Invalid license key format.'));
  }
}
```

## Debugging

### Afficher les paramètres compilés

```php
// Dans un contrôleur ou preprocess
$settings = LightGallerySettingsTrait::getGeneralSettings([
  'lightgallery_settings' => [
    'core' => \Drupal::config('lightgallery.settings')->get('core'),
    'plugins' => \Drupal::config('lightgallery.settings')->get('plugins'),
  ],
]);

\Drupal::logger('lightgallery')->notice('<pre>@settings</pre>', [
  '@settings' => print_r($settings, TRUE),
]);
```

### Vérifier la configuration actuelle

```bash
# Exporter la configuration
drush config:get lightgallery.settings

# Éditer la configuration
drush config:edit lightgallery.settings

# Importer depuis YAML
drush config:set lightgallery.settings core.params.licenseKey "1234-5678-9012-3456"
```

## Bonnes pratiques

1. **Toujours utiliser les settings globaux comme fallback**
   ```php
   $field_settings = $this->getSetting('lightgallery_settings') ?? [];
   $global_settings = \Drupal::config('lightgallery.settings')->get();
   $merged = array_replace_recursive($global_settings, $field_settings);
   ```

2. **Ne pas hardcoder les clés de licence**
   - Utilisez toujours la configuration
   - Documentez où obtenir une clé

3. **Respecter la hiérarchie des paramètres**
   - Global (lightgallery.settings)
   - Par formatter (third party settings)
   - Par instance (custom_settings dans le formatter)

4. **Vider le cache après modification**
   ```bash
   drush cr
   ```

## Exemples complets

Consultez les fichiers suivants pour des exemples complets :
- `src/Form/SettingsForm.php` - Formulaire d'administration
- `src/Traits/LightGallerySettingsTrait.php` - Trait réutilisable
- `lightgallery_settings_ui.module` - Intégration hooks

## Support et contribution

Pour contribuer ou signaler un bug :
1. Créez un ticket sur drupal.org
2. Forkez le projet
3. Soumettez une merge request avec tests
