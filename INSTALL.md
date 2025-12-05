# Guide d'installation - LightGallery Settings UI

## Structure complète du module

Voici la structure de fichiers que vous devez créer :

```
modules/custom/lightgallery_settings_ui/
├── config/
│   ├── install/
│   │   └── lightgallery.settings.yml
│   └── schema/
│       └── lightgallery.settings.schema.yml
├── css/
│   └── lightgallery-settings.css
├── src/
│   ├── Form/
│   │   └── SettingsForm.php
│   └── Traits/
│       └── LightGallerySettingsTrait.php
├── tests/
│   └── src/
│       └── Functional/
│           └── SettingsFormTest.php
├── API.md
├── INSTALL.md
├── lightgallery_settings_ui.info.yml
├── lightgallery_settings_ui.install
├── lightgallery_settings_ui.links.menu.yml
├── lightgallery_settings_ui.module
├── lightgallery_settings_ui.permissions.yml
├── lightgallery_settings_ui.routing.yml
├── MIGRATION.md
└── README.md
```

## Installation pas à pas

### 1. Créer la structure de dossiers

```bash
cd /path/to/drupal
mkdir -p modules/custom/lightgallery_settings_ui
cd modules/custom/lightgallery_settings_ui
```

### 2. Créer les dossiers nécessaires

```bash
mkdir -p config/install
mkdir -p config/schema
mkdir -p css
mkdir -p src/Form
mkdir -p src/Traits
mkdir -p tests/src/Functional
```

### 3. Copier les fichiers

Copiez tous les fichiers fournis dans les artefacts précédents dans les dossiers correspondants.

**Liste des fichiers à créer :**

1. **Fichiers de base** :
   - `lightgallery_settings_ui.info.yml`
   - `lightgallery_settings_ui.install`
   - `lightgallery_settings_ui.module`
   - `lightgallery_settings_ui.routing.yml`
   - `lightgallery_settings_ui.links.menu.yml`
   - `lightgallery_settings_ui.permissions.yml`

2. **Configuration** :
   - `config/install/lightgallery.settings.yml`
   - `config/schema/lightgallery.settings.schema.yml`

3. **Code PHP** :
   - `src/Form/SettingsForm.php`
   - `src/Traits/LightGallerySettingsTrait.php`

4. **Assets** :
   - `css/lightgallery-settings.css`

5. **Tests** :
   - `tests/src/Functional/SettingsFormTest.php`

6. **Documentation** :
   - `README.md`
   - `MIGRATION.md`
   - `API.md`
   - `INSTALL.md`

### 4. Vérifier les permissions

```bash
cd /path/to/drupal/modules/custom
chmod -R 755 lightgallery_settings_ui
```

### 5. Activer le module

```bash
# Via Drush
drush en lightgallery_settings_ui -y
drush cr

# Ou via l'interface web
# Admin → Extend → chercher "LightGallery Settings UI" → cocher → Install
```

### 6. Configurer les permissions

```bash
# Via Drush
drush role:perm:add administrator "administer lightgallery"

# Ou via l'interface web
# Admin → People → Permissions → chercher "Administer LightGallery"
```

### 7. Vérifier l'installation

Accédez à : `/admin/config/media/lightgallery`

Vous devriez voir le formulaire de configuration avec :
- Section "Core" avec tous les paramètres de base
- Section "Plugins" avec les différents plugins activables

## Installation via Composer (optionnel)

Si vous voulez gérer ce module via Composer, ajoutez-le comme repository local :

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "modules/custom/lightgallery_settings_ui"
    }
  ],
  "require": {
    "drupal/lightgallery_settings_ui": "*"
  }
}
```

Puis :
```bash
composer require drupal/lightgallery_settings_ui
```

## Post-installation

### 1. Configurer la clé de licence

1. Allez sur `/admin/config/media/lightgallery`
2. Dans la section "Core", remplissez le champ "License key"
3. Format attendu : `XXXX-XXXX-XXXX-XXXX`
4. Enregistrez

### 2. Activer les plugins souhaités

Dans la section "Plugins", cochez les plugins que vous souhaitez activer :
- **Zoom** : pour le zoom sur les images
- **Thumbnail** : pour afficher les miniatures
- **Video** : pour la lecture de vidéos
- **Autoplay** : pour le défilement automatique
- **Fullscreen** : pour le mode plein écran
- **Pager** : pour la pagination

### 3. Configurer un formateur de champ

1. Allez dans **Structure → Types de contenu → [votre type] → Manage display**
2. Pour un champ image/média, sélectionnez un formateur LightGallery
3. Cliquez sur l'icône d'engrenage pour les paramètres
4. Vous verrez maintenant une section "LightGallery advanced settings"
5. Configurez selon vos besoins (les paramètres globaux sont utilisés par défaut)

## Dépannage

### Le module n'apparaît pas dans la liste

```bash
# Vérifier que tous les fichiers sont présents
ls -la modules/custom/lightgallery_settings_ui/

# Reconstruire le cache
drush cr

# Vérifier les erreurs
drush watchdog:show --type=php
```

### Erreur "Class not found"

Vérifiez que :
1. Le namespace est correct dans tous les fichiers PHP
2. L'autoloader est à jour : `composer dump-autoload`

### La configuration ne se sauvegarde pas

```bash
# Vérifier les permissions d'écriture
ls -la sites/default/files

# Vérifier la configuration
drush config:status

# Exporter la configuration
drush cex
```

### Les paramètres n'apparaissent pas dans le rendu

Vérifiez que :
1. Le cache est vidé : `drush cr`
2. Les templates utilisent bien `{{ settings.licenseKey }}` etc.
3. Le preprocessing fonctionne : ajoutez des logs dans `lightgallery_settings_ui_preprocess_lightgallery()`

## Désinstallation

```bash
# Désactiver le module
drush pm:uninstall lightgallery_settings_ui -y

# Supprimer les fichiers
rm -rf modules/custom/lightgallery_settings_ui

# Nettoyer le cache
drush cr
```

**Note** : La configuration dans `lightgallery.settings` sera conservée même après désinstallation. Pour la supprimer complètement :

```bash
drush config:delete lightgallery.settings
```

## Support

Pour toute question :
- Consultez `README.md` pour la documentation utilisateur
- Consultez `API.md` pour la documentation développeur
- Consultez `MIGRATION.md` si vous migrez depuis le patch

## Contribution

Les contributions sont les bienvenues ! Assurez-vous de :
1. Suivre les standards de code Drupal
2. Ajouter des tests pour les nouvelles fonctionnalités
3. Mettre à jour la documentation