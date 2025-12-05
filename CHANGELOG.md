# Changelog

Toutes les modifications notables de ce projet seront documentées dans ce fichier.

Le format est basé sur [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/),
et ce projet adhère au [Semantic Versioning](https://semver.org/lang/fr/).

## [1.0.0] - 2025-10-01

### Ajouté
- Module initial créé à partir du patch #21 pour le module lightgallery
- Trait `LightGallerySettingsTrait` pour la gestion réutilisable des settings
- Formulaire d'administration complet pour les paramètres LightGallery
- Support de tous les paramètres core de LightGallery :
  - Licence key
  - Contrôles de navigation
  - Animations et transitions
  - Comportement clavier/souris
  - Options d'affichage
- Support des plugins LightGallery :
  - Zoom (avec paramètres d'échelle)
  - Thumbnail (miniatures)
  - Video (lecture automatique)
  - Autoplay (diaporama)
  - Fullscreen (plein écran)
  - Pager (pagination)
- Configuration globale via `/admin/config/media/lightgallery`
- Override des paramètres au niveau des formatters de champs
- Migration automatique depuis l'ancienne structure de configuration
- Documentation complète (README, MIGRATION, API, INSTALL)
- Tests fonctionnels pour le formulaire de settings
- Fichier CSS pour améliorer l'UI du formulaire
- Hook pour l'intégration avec les formatters existants
- Schéma de configuration complet avec validation

### Changé
- Structure de configuration : passage de `license_key` plat vers `core.params.licenseKey` hiérarchique
- Namespace : déplacé de `Drupal\lightgallery\*` vers `Drupal\lightgallery_settings_ui\*`

### Sécurité
- Permission `administer lightgallery` requise pour accéder aux paramètres
- Validation du format de la clé de licence

## [Non publié] - Prévisions futures

### À ajouter
- Support des plugins additionnels (share, comment, rotate)
- Import/export de presets de configuration
- Interface de prévisualisation en temps réel
- Intégration avec les styles d'images Drupal
- Support multilingue des descriptions
- Documentation vidéo
- Exemples de configurations prédéfinies

### À améliorer
- Performance du chargement des paramètres
- UI/UX du formulaire d'administration
- Tests de couverture (unit tests, kernel tests)
- Intégration avec les outils de développement Drupal

---

## Guide des types de changements

- **Ajouté** : pour les nouvelles fonctionnalités
- **Changé** : pour les modifications de fonctionnalités existantes
- **Déprécié** : pour les fonctionnalités bientôt supprimées
- **Supprimé** : pour les fonctionnalités maintenant supprimées
- **Corrigé** : pour les corrections de bugs
- **Sécurité** : en cas de vulnérabilités