# EmailMessage (+ EmailSenderHandler)

- Classe (message) : `AppoloDev\SFToolboxBundle\Message\EmailMessage`
- Classe (handler) : `AppoloDev\SFToolboxBundle\MessageHandler\EmailSenderHandler`
- Fichiers source : `src/Message/EmailMessage.php`, `src/MessageHandler/EmailSenderHandler.php`

## Rôle

Couple message/handler Symfony Messenger pour l'envoi (potentiellement asynchrone, selon le transport configuré) d'emails templatés Twig. Il suffit de `dispatch()` un `EmailMessage` sur le bus — l'envoi effectif est pris en charge par `EmailSenderHandler`, **enregistré automatiquement** (`#[AsMessageHandler]`).

## Configuration requise (projet consommateur)

Deux variables d'environnement, utilisées par `EmailSenderHandler` :

```
SENDER_EMAIL=contact@monsite.fr
SENDER_NAME="Mon Site"
```

## `EmailMessage`

### `__construct(array $recipients, string $object, string $template, array $parameters = [], string $locale = 'en', array $files = [], ?string $logoPath = null)`

- `$recipients` : liste d'adresses email (`string[]`) — converties en interne en `Symfony\Component\Mime\Address`.
- `$object` : **clé de traduction** du sujet de l'email (traduite dans le domaine `emails` par le handler — ce n'est pas un sujet littéral).
- `$template` : chemin du template Twig HTML de l'email (ex: `emails/welcome.html.twig`).
- `$parameters` : variables passées au contexte Twig du template.
- `$locale` : locale de traduction du sujet et de rendu, fusionnée dans le contexte Twig sous la clé `locale` (défaut `'en'`).
- `$files` : pièces jointes, tableau d'éléments `['file' => <objet avec getContent()>, 'filename' => string]`.
- `$logoPath` : chemin absolu vers un logo à intégrer en image inline (`cid:logo`) dans l'email ; si `null` ou fichier introuvable, le handler utilise `%kernel.project_dir%/public/assets/images/logo.png` par défaut (**ce chemin doit exister dans le projet consommateur**, sauf si `logoPath` est toujours fourni explicitement).

### Accesseurs

`getRecipients(): array`, `getObject(): string`, `getTemplate(): string`, `getParameters(): array`, `getLocale(): string`, `getFiles(): array`, `getLogoPath(): ?string` — simples getters, pas de logique.

## `EmailSenderHandler`

### `__invoke(EmailMessage $emailMessage): void`

Appelé automatiquement par Symfony Messenger lors du traitement du message (jamais directement). Construit un `Symfony\Bridge\Twig\Mime\TemplatedEmail` :
- expéditeur : `SENDER_EMAIL`/`SENDER_NAME` (env vars),
- sujet : traduction de `$emailMessage->getObject()` dans le domaine `emails`,
- logo intégré en pièce jointe inline (`cid:logo`),
- corps HTML : `$emailMessage->getTemplate()`, avec le contexte `$emailMessage->getParameters()` fusionné avec `['locale' => $emailMessage->getLocale()]`,
- pièces jointes issues de `$emailMessage->getFiles()`,

puis envoie via `MailerInterface::send()`. Peut lever une `Symfony\Component\Mailer\Exception\TransportExceptionInterface` en cas d'échec d'envoi.

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Message\EmailMessage;
use Symfony\Component\Messenger\MessageBusInterface;

public function register(MessageBusInterface $bus, User $user): void
{
    $bus->dispatch(new EmailMessage(
        recipients: [$user->getEmail()],
        object: 'welcome.subject', // clé de traduction, domaine "emails"
        template: 'emails/welcome.html.twig',
        parameters: ['firstname' => $user->getFirstname()],
        locale: $user->getLocale(),
    ));
}
```

Traductions à ajouter côté projet (domaine `emails`), ex. `translations/emails.fr.yaml` :

```yaml
welcome.subject: "Bienvenue sur Mon Site !"
```

Template `emails/welcome.html.twig` (extrait) :

```twig
<p>Bonjour {{ firstname }},</p>
<p>Bienvenue !</p>
```

## Voir aussi

- Templates d'email — le champ `object` est traduit dans le domaine `emails`, distinct de `messages`/`validators`.
