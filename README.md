# LightGallery Settings UI

Ce module fournit une interface utilisateur avancée pour configurer les paramètres de LightGallery globalement et par formateur de champ.

## Prérequis

- Drupal 10 ou 11
- Module LightGallery (lightgallery:lightgallery)

## Installation

1. Placez ce module dans `modules/custom/lightgallery_settings_ui`
2. Activez le module : `drush en lightgallery_settings_ui`
3. Accordez la permission "Administer LightGallery" aux rôles appropriés
4. Accédez à la page de configuration : `/admin/config/media/lightgallery`

## Configuration

Le module crée sa propre configuration dans `lightgallery_settings_ui.settings`, séparée de celle du module de base lightgallery. Cela évite tout conflit lors des mises à jour du module lightgallery.

## Fonctionnalités

### Configuration globale

Le module ajoute une interface complète pour configurer :

- **Paramètres de base (Core)** :
  - Clé de licence
  - Contrôles de navigation
  - Animations et transitions
  - Comportement du clavier et de la souris
  - Et plus encore...

- **Plugins** :
  - Zoom (activer/désactiver, échelle initiale)
  - Thumbnails (miniatures)
  - Video (lecture automatique)
  - Autoplay (diaporama automatique)
  - Fullscreen (plein écran)
  - Pager (pagination)

### Configuration par champ

Les paramètres peuvent être surchargés au niveau de chaque formateur de champ dans la configuration d'affichage des entités.

## Structure du module

```
lightgallery_settings_ui/
├── config/
│   ├── install/
│   │   └── lightgallery.settings.yml
│   └── schema/
│       └── lightgallery.settings.schema.yml
├── src/
│   ├── Form/
│   │   └── SettingsForm.php
│   └── Traits/
│       └── LightGallerySettingsTrait.php
├── lightgallery_settings_ui.info.yml
├── lightgallery_settings_ui.install
├── lightgallery_settings_ui.routing.yml
├── lightgallery_settings_ui.links.menu.yml
├── lightgallery_settings_ui.permissions.yml
└── README.md
```

## Migration depuis l'ancienne configuration

Si vous aviez une ancienne configuration LightGallery avec seulement `license_key`, le module migrera automatiquement vos paramètres lors de l'installation.

## Utilisation

### Configuration globale

1. Allez dans **Administration → Configuration → Media → LightGallery**
2. Configurez les paramètres de base dans l'onglet "Core"
3. Activez et configurez les plugins souhaités
4. Enregistrez

### Configuration par champ

1. Allez dans la gestion d'affichage de votre type de contenu
2. Configurez le formateur d'un champ média/image avec LightGallery
3. Dans les paramètres du formateur, vous trouverez une section "LightGallery settings"
4. Les paramètres définis ici surchargent les paramètres globaux

## Trait réutilisable

Le trait `LightGallerySettingsTrait` peut être réutilisé dans vos propres modules pour :

- Générer des formulaires de configuration LightGallery
- Récupérer les définitions des plugins
- Fusionner les paramètres globaux et locaux

## Support

Pour toute question ou problème, veuillez ouvrir un ticket sur le projet Drupal.org.

## Licence

GPL-2.0+
