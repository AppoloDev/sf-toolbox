# InteractCommand

- Trait : `AppoloDev\SFToolboxBundle\Maker\Command\InteractCommand`
- Fichier source : `src/Maker/Command/InteractCommand.php`

## Rôle

Trait utilitaire factorisant la logique d'interaction console (questions/confirmations), utilisé par [`MakeDomainEntityCommand`](MakeDomainEntityCommand.md) et [`MakeScaffoldCommand`](MakeScaffoldCommand.md) dans leur méthode `interact()`. Pas destiné à être utilisé en dehors de ces commandes Maker.

## API

### `askQuestion(string $argName, InputInterface $input, OutputInterface $output): void`

Pose une question à l'utilisateur en se basant sur la **description de l'argument de console** `$argName` (déclarée dans `configure()` via `addArgument(..., description: ...)`), puis assigne la réponse dans `$input` via `setArgument()`.

```php
protected function configure(): void
{
    $this->addArgument('domain', InputArgument::OPTIONAL, 'Nom du domaine (ex: Catalog)');
}

public function interact(InputInterface $input, OutputInterface $output): void
{
    $this->askQuestion('domain', $input, $output); // pose la question "Nom du domaine (ex: Catalog)"
}
```

### `askConfirmation(string $argName, InputInterface $input, OutputInterface $output): void`

Pose une question de confirmation (oui/non, `ConfirmationQuestion`), avec un validateur qui lève une `\InvalidArgumentException` si la réponse n'est pas positive — pensé pour une boucle qui bloque tant que l'utilisateur ne confirme pas (voir l'usage dans [`MakeDomainEntityCommand::execute()`](MakeDomainEntityCommand.md), qui boucle tant que `mapping` n'est pas confirmé `true`).

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Maker\Command\InteractCommand;

#[AsCommand(name: 'make:my-thing')]
class MakeMyThingCommand extends Command
{
    use InteractCommand;

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::OPTIONAL, 'Nom à donner');
    }

    public function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->askQuestion('name', $input, $output);
    }
}
```
