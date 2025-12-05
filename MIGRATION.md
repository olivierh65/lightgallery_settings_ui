# Guide de migration depuis le patch vers le module

Ce guide explique comment migrer votre code du patch vers le nouveau module `lightgallery_settings_ui`.

## Étapes de migration

### 1. Annuler le patch existant

Si vous avez appliqué le patch 21.patch sur le module lightgallery, annulez-le d'abord :

```bash
cd modules/contrib/lightgallery
git apply -R /path/to/21.patch
# ou
patch -R -p1 < /path/to/21.patch
```

### 2. Installer le nouveau module

```bash
# Copier le module dans modules/custom
cp -r lightgallery_settings_ui modules/custom/

# Activer le module
drush en lightgallery_settings_ui -y

# Vider le cache
drush cr
```

### 3. Vérifier la configuration

La configuration devrait être automatiquement migrée. Vérifiez en allant sur :
`/admin/config/media/lightgallery`

### 4. Mettre à jour vos formatters personnalisés (si applicable)

Si vous avez créé des formatters personnalisés qui utilisent le trait, mettez à jour le namespace :

**Ancien (dans le patch) :**
```php
use Drupal\lightgallery\Traits\LightGallerySettingsTrait;
```

**Nouveau (dans le module) :**
```php
use Drupal\lightgallery_settings_ui\Traits\LightGallerySettingsTrait;
```

### 5. Modifications à apporter dans le module lightgallery

Le module lightgallery doit être légèrement modifié pour utiliser le nouveau trait. Créez un patch pour lightgallery :

**fichier : lightgallery-use-settings-ui.patch**

```diff
diff --git a/src/Plugin/Field/FieldFormatter/EntityReferenceLightgalleryFormatterTrait.php b/src/Plugin/Field/FieldFormatter/EntityReferenceLightgalleryFormatterTrait.php
index b7ca541..4066fa8 100644
--- a/src/Plugin/Field/FieldFormatter/EntityReferenceLightgalleryFormatterTrait.php
+++ b/src/Plugin/Field/FieldFormatter/EntityReferenceLightgalleryFormatterTrait.php
@@ -10,6 +10,7 @@ use Drupal\Core\File\FileUrlGeneratorInterface;
 use Drupal\file\FileInterface;
 use Drupal\image\ImageStyleInterface;
 use Drupal\image\Plugin\Field\FieldType\ImageItem;
+use Drupal\lightgallery_settings_ui\Traits\LightGallerySettingsTrait;
 
 trait EntityReferenceLightgalleryFormatterTrait {
+  use LightGallerySettingsTrait;
 
   protected function buildLightgalleryItems(...) {
     // ...
-    $build['#settings'] = $this->getLightgallerySettings();
+    $build['#settings'] = static::getGeneralSettings($this->getSettings());
+    $build['#settings'] += static::getLightgallerySettings();
     // ...
   }
```

Appliquez ce patch avec composer :

```json
{
  "extra": {
    "patches": {
      "drupal/lightgallery": {
        "Use lightgallery_settings_ui": "patches/lightgallery-use-settings-ui.patch"
      }
    }
  }
}
```

### 6. Alternative : Utiliser un module .module hook

Si vous ne souhaitez pas patcher lightgallery, créez un fichier `lightgallery_settings_ui.module` :

```php
<?php

/**
 * @file
 * Hooks for lightgallery_settings_ui module.
 */

use Drupal\lightgallery_settings_ui\Traits\LightGallerySettingsTrait;

/**
 * Implements hook_field_formatter_settings_summary_alter().
 */
function lightgallery_settings_ui_field_formatter_settings_summary_alter(&$summary, $context) {
  $formatter = $context['formatter'];
  
  if (strpos($formatter->getPluginId(), 'lightgallery') !== FALSE) {
    $settings = $formatter->getSettings();
    
    if (isset($settings['lightgallery_settings']['plugins'])) {
      $enabled = array_filter(
        $settings['lightgallery_settings']['plugins'] ?? [],
        fn($p) => ($p['enabled'] ?? FALSE)
      );
      
      if (!empty($enabled)) {
        $summary[] = t('LG Plugins: @list', [
          '@list' => implode(', ', array_keys($enabled))
        ]);
      }
    }
  }
}

/**
 * Implements hook_lightgallery_settings_alter().
 */
function lightgallery_settings_ui_lightgallery_settings_alter(&$settings) {
  // Merge global settings with field-specific settings
  $config = \Drupal::config('lightgallery.settings');
  
  if (!isset($settings['licenseKey'])) {
    $settings = array_merge(
      LightGallerySettingsTrait::getGeneralSettings([
        'lightgallery_settings' => [
          'core' => $config->get('core') ?? [],
          'plugins' => $config->get('plugins') ?? [],
        ]
      ]),
      $settings
    );
  }
}
```

## Différences principales

| Aspect | Patch | Module séparé |
|--------|-------|---------------|
| **Maintenance** | Difficile, doit être réappliqué | Facile, module standard |
| **Updates** | Risque de conflit | Indépendant |
| **Namespace** | `Drupal\lightgallery\*` | `Drupal\lightgallery_settings_ui\*` |
| **Configuration** | Modifie lightgallery directement | Config séparée |

## Résolution de problèmes

### La configuration n'est pas migrée

Exportez manuellement votre ancienne configuration :

```bash
drush config:export --single lightgallery.settings
```

Puis adaptez-la au nouveau format dans `config/install/lightgallery.settings.yml`

### Les paramètres ne s'appliquent pas

Vérifiez que :
1. Le cache est vidé : `drush cr`
2. Les permissions sont correctes : `/admin/people/permissions`
3. Le module est activé : `drush en lightgallery_settings_ui`

### Erreur de namespace

Si vous obtenez des erreurs de classe non trouvée, vérifiez que tous les `use` statements pointent vers le bon namespace :

```php
// Correct
use Drupal\lightgallery_settings_ui\Traits\LightGallerySettingsTrait;

// Incorrect (ancien)
use Drupal\lightgallery\Traits\LightGallerySettingsTrait;
```

## Support

Pour toute question, consultez la documentation sur drupal.org ou ouvrez un ticket.