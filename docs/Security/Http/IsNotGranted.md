# IsNotGranted (+ IsNotGrantedAttributeListener)

- Attribut : `AppoloDev\SFToolboxBundle\Security\Http\Attribute\IsNotGranted`
- Écouteur : `AppoloDev\SFToolboxBundle\Security\Http\EventListener\IsNotGrantedAttributeListener`
- Fichiers source : `src/Security/Http/Attribute/IsNotGranted.php`, `src/Security/Http/EventListener/IsNotGrantedAttributeListener.php`

## Rôle

Attribut PHP au comportement **inverse** de `#[IsGranted]` (natif Symfony Security) : refuse l'accès quand la vérification **réussit**, plutôt que quand elle échoue. Utile pour restreindre une route aux utilisateurs qui n'ont **pas** un rôle/état donné — par exemple une page d'inscription réservée aux visiteurs non connectés, ou bloquer l'accès à des comptes bannis.

L'écouteur `IsNotGrantedAttributeListener` est **enregistré automatiquement** (autowiring + autoconfiguration, tag `kernel.event_subscriber`, priorité `20` sur `KernelEvents::CONTROLLER_ARGUMENTS`) — aucune configuration manuelle nécessaire, il suffit de poser l'attribut sur une classe/méthode/fonction contrôleur.

## `IsNotGranted` — paramètres du constructeur

Signature identique à celle de `#[IsGranted]` natif :

```php
public function __construct(
    public string|Expression $attribute,
    public array|string|Expression|null $subject = null,
    public ?string $message = null,
    public ?int $statusCode = null,
    public ?int $exceptionCode = null,
)
```

- `$attribute` : rôle, nom de Voter, ou expression `ExpressionLanguage` à évaluer.
- `$subject` : sujet passé au Voter/à l'expression — peut référencer un argument nommé du contrôleur (`string`), une expression (`Expression`), ou un tableau des deux (avec clés optionnelles).
- `$message` : message d'erreur personnalisé (sinon un message générique est généré).
- `$statusCode` : si fourni, lève une `HttpException` avec ce code HTTP plutôt qu'une `AccessDeniedException` (403 par défaut).
- `$exceptionCode` : code d'exception interne (distinct du code HTTP).

Répétable (`#[Attribute::IS_REPEATABLE]`), applicable sur classe, méthode ou fonction.

## `IsNotGrantedAttributeListener`

### `onKernelControllerArguments(ControllerArgumentsEvent $event): void`

Écoute `KernelEvents::CONTROLLER_ARGUMENTS`. Pour chaque `#[IsNotGranted]` présent sur le contrôleur appelé :
1. Résout le `$subject` (argument nommé du contrôleur, ou expression évaluée avec `request`/`args` dans le contexte).
2. Si `isGranted($attribute, $subject)` retourne `true` : lève une exception (`HttpException` si `statusCode` est fourni, sinon `AccessDeniedException` avec le message/attribut/sujet renseignés pour un débogage précis).
3. Ne fait rien si `isGranted()` retourne `false` (l'accès est autorisé — c'est bien la logique inversée par rapport à `#[IsGranted]`).

Vous n'appelez jamais cette méthode vous-même — elle est déclenchée automatiquement par le kernel HTTP.

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Security\Http\Attribute\IsNotGranted;
use Symfony\Component\ExpressionLanguage\Expression;

// Page d'inscription réservée aux visiteurs non connectés :
#[Route('/register', name: 'app_register')]
#[IsNotGranted(new Expression('is_granted("IS_AUTHENTICATED_FULLY")'))]
class RegisterController extends AbstractController
{
    // ...
}

// Bloquer l'accès à un compte banni :
#[Route('/profile', name: 'app_profile')]
#[IsNotGranted('ROLE_BANNED', message: 'Votre compte a été suspendu.', statusCode: 403)]
class ProfileController extends AbstractController
{
    // ...
}
```

> ⚠️ Ne pas confondre avec `#[IsGranted]` (natif Symfony) : ici, poser l'attribut **bloque** l'accès si la condition **est** vérifiée — c'est l'inverse du comportement habituel.
