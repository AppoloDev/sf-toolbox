# ScriptHandler

- Classe : `AppoloDev\SFToolboxBundle\Composer\ScriptHandler`
- Fichier source : `src/Composer/ScriptHandler.php`

## Rôle

Script Composer (pas un service Symfony) chargé de relier le [Claude Code Skill](../../.claude/skills/sf-toolbox/SKILL.md) embarqué dans ce bundle au projet consommateur qui l'installe. Voir la section "Claude Code integration" du [README](../../README.md) pour le contexte complet et les instructions d'installation côté projet consommateur.

## API

### `static installClaudeSkill(Composer\Script\Event $event): void`

À appeler depuis les scripts `post-install-cmd`/`post-update-cmd` du **projet consommateur** (pas de ce bundle lui-même). Crée (ou met à jour) un lien symbolique relatif `.claude/skills/sf-toolbox` à la racine du projet, pointant vers `vendor/appolodev/sf-toolbox/.claude/skills/sf-toolbox`.

Comportement détaillé :
- Ne fait rien si le dossier source (`vendor/appolodev/sf-toolbox/.claude/skills/sf-toolbox`) n'existe pas.
- Ne fait rien (retour silencieux) si le lien existe déjà et pointe correctement vers la source (idempotent — pas de recréation inutile à chaque `composer install`).
- Si un dossier/fichier existe déjà à cet emplacement et **n'est pas** un symlink géré par ce script, abandonne avec un avertissement (`writeError`) plutôt que d'écraser un contenu existant potentiellement précieux.
- Sinon, crée un symlink **relatif** (portable entre machines/CI, tant que la structure du projet reste cohérente) ; si les symlinks ne sont pas supportés (ex: certaines configurations Windows), copie le dossier à la place (`Filesystem::mirror()`).

## Configuration côté projet consommateur

```json
{
    "scripts": {
        "post-install-cmd": [
            "AppoloDev\\SFToolboxBundle\\Composer\\ScriptHandler::installClaudeSkill"
        ],
        "post-update-cmd": [
            "AppoloDev\\SFToolboxBundle\\Composer\\ScriptHandler::installClaudeSkill"
        ]
    }
}
```

> Si d'autres scripts sont déjà déclarés sous ces clés (ex: `@auto-scripts` de Symfony Flex), ajoutez cette entrée à la liste existante plutôt que de la remplacer.

Après un `composer install`/`update`, le dossier `.claude/skills/sf-toolbox` apparaît à la racine du projet — à ajouter au `.gitignore` du projet consommateur (c'est un artefact dérivé de `vendor/`, régénéré à chaque installation) :

```
/.claude/skills/sf-toolbox
```

## Pourquoi ce mécanisme (et pas autre chose) ?

Claude Code ne découvre les Skills que dans le `.claude/skills/` propre au projet, jamais dans `vendor/`. Ce script permet donc de rendre le Skill disponible sans copier/maintenir manuellement sa documentation dans chaque projet consommateur — et comme le lien pointe vers le contenu réellement installé dans `vendor/`, le Skill reflète toujours **exactement** la version du bundle utilisée par ce projet précis (pas de dérive si un projet reste sur une ancienne version pendant qu'un autre bundle évolue).

## Voir aussi

- [README](../../README.md) — section "Claude Code integration".
- `.claude/skills/sf-toolbox/SKILL.md` — le Skill lui-même, destiné à un agent Claude, distinct de cette documentation `docs/` destinée aux humains.
