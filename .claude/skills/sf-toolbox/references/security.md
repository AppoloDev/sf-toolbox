# Security

Namespace: `AppoloDev\SFToolboxBundle\Security\*`.

## `AbstractVoter` (`Security\Authorization\AbstractVoter`)

Extends Symfony's core `Voter`. Constructor takes `Symfony\Bundle\SecurityBundle\Security $security` (autowired). Provides two protected helpers on top of the usual `supports()`/`voteOnAttribute()` you still implement yourself:
- `canAllow(array|string $roles): bool` — `true` if the current token is granted *any one* of the given role(s) (single role or list, OR semantics).
- `canAllowAdmin(): bool` — shorthand for `canAllow('ROLE_ADMIN')`.

This is what `make:scaffold`-generated voters extend (see [maker-commands.md](maker-commands.md)); the generated `voteOnAttribute()` body is left as a stub returning `true` — always replace it with real logic (e.g. `return $this->canAllowAdmin() || $this->canAllow(['ROLE_EDITOR']);` or subject-based ownership checks).

## `#[IsNotGranted]` (`Security\Http\Attribute\IsNotGranted`)

An attribute with the exact same shape as Symfony core's `#[IsGranted]` (`attribute`, `subject`, `message`, `statusCode`, `exceptionCode`) but with **inverted semantics**: access is denied when the check **passes** (is granted), not when it fails. Repeatable, targets classes/methods/functions.

Use case: gate a route to users who do *not* have a role/aren't in a given state — e.g. `#[IsNotGranted('ROLE_BANNED')]` to block banned users, or `#[IsNotGranted(new Expression('is_granted("ROLE_USER")'))]` to restrict a "guest only" page (registration, login) to anonymous visitors.

Handled by `IsNotGrantedAttributeListener` (`Security\Http\EventListener`, auto-registered as a `kernel.event_subscriber` on `KernelEvents::CONTROLLER_ARGUMENTS`, priority 20) — no manual wiring needed, just add the attribute. Throws `AccessDeniedException` (default) or `HttpException` with the given `statusCode` if the underlying `isGranted()` check returns `true`.
