# Livre de Recettes - Projet Symfony

Ce projet est une application Symfony faite dans le cadre de mon bachelor. Elle permet à des utilisateurs de créer, consulter et modifier des recettes de cuisine. Chaque recette peut être associée à des origines et à des tags.

## Fonctionnalités principales

- Authentification utilisateur (accès restreint à certaines actions)
- Création, édition et consultation de recettes
- Ajout d'origines
- Ajout de tags
- Détection des doublons (tags et origines)
- Pssibilité d'ajouter une image en URL pour chaque recette
- Pagination sur la liste des recettes

## Liste des routes

| Route               | Méthode  | Description                                                  |
| ------------------- | -------- | ------------------------------------------------------------ |
| `/recipes`          | GET      | Affiche la liste paginée des recettes                        |
| `/recipes/{id}`     | GET      | Affiche le détail d’une recette                              |
| `/recipe/new`       | GET/POST | Formulaire de création d’une recette + tags/origines         |
| `/recipe/{id}/edit` | GET/POST | Formulaire d’édition d’une recette (propriétaire uniquement) |

## Formulaires intégrés

- `RecipeType` : formulaire principal pour créer/éditer une recette
- `OriginType` : formulaire pour ajouter une nouvelle origine géographique
- `TagType` : formulaire pour ajouter un nouveau tag

## Fonctionnalités supplémentaires

- Vérification de doublon lors de l’ajout d’un tag ou d’une origine (insensible à la casse et aux accents)
- Messages de succès/erreur via `addFlash()` affichés dans le template

---

## Environnement de développement

- Symfony 6+
- Doctrine ORM
- Twig pour les templates
- KnpPaginatorBundle pour la pagination

---

## TODO

- Améliorer la recherche et le filtrage des recettes

---
