<?php

namespace AppoloDev\SFToolboxBundle\Security\Http\EventListener;

use AppoloDev\SFToolboxBundle\Security\Http\Attribute\IsNotGranted;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\RuntimeException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class IsNotGrantedAttributeListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $authChecker,
        private ?ExpressionLanguage $expressionLanguage = null,
    ) {
    }
    // TODO à FAIRE
    public function onKernelControllerArguments(ControllerArgumentsEvent $event): void
    {
        /** @var IsNotGranted[] $attributes */
        if (!\is_array($attributes = $event->getAttributes()[IsNotGranted::class] ?? null)) {
            return;
        }

        $request = $event->getRequest();
        $arguments = $event->getNamedArguments();

        foreach ($attributes as $attribute) {
            $subject = null;

            if ($subjectRef = $attribute->subject) {
                if (\is_array($subjectRef)) {
                    foreach ($subjectRef as $refKey => $ref) {
                        $subject[\is_string($refKey) ? $refKey : (string) $ref] = $this->getIsGrantedSubject($ref, $request, $arguments);
                    }
                } else {
                    $subject = $this->getIsGrantedSubject($subjectRef, $request, $arguments);
                }
            }

            if ($this->authChecker->isGranted($attribute->attribute, $subject)) {
                $message = $attribute->message ?: sprintf('Access Denied by #[IsGranted(%s)] on controller', $this->getIsGrantedString($attribute));

                if ($statusCode = $attribute->statusCode) {
                    throw new HttpException($statusCode, $message, code: $attribute->exceptionCode ?? 0);
                }

                $accessDeniedException = new AccessDeniedException($message, code: $attribute->exceptionCode ?? 403);
                $accessDeniedException->setAttributes($attribute->attribute);
                $accessDeniedException->setSubject($subject);

                throw $accessDeniedException;
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::CONTROLLER_ARGUMENTS => ['onKernelControllerArguments', 20]];
    }

    private function getIsGrantedSubject(string|Expression $subjectRef, Request $request, array $arguments): mixed
    {
        if ($subjectRef instanceof Expression) {
            $this->expressionLanguage ??= new ExpressionLanguage();

            return $this->expressionLanguage->evaluate($subjectRef, [
                'request' => $request,
                'args' => $arguments,
            ]);
        }

        if (!\array_key_exists($subjectRef, $arguments)) {
            throw new RuntimeException(sprintf('Could not find the subject "%s" for the #[IsGranted] attribute. Try adding a "$%s" argument to your controller method.', $subjectRef, $subjectRef));
        }

        return $arguments[$subjectRef];
    }

    private function getIsGrantedString(IsNotGranted $isNotGranted): string
    {
        $processValue = fn ($value) => sprintf($value instanceof Expression ? 'new Expression("%s")' : '"%s"', $value);

        $argsString = $processValue($isNotGranted->attribute);

        if (null !== $subject = $isNotGranted->subject) {
            $subject = !\is_array($subject) ? $processValue($subject) : array_map(function ($key, $value) use ($processValue) {
                $value = $processValue($value);

                return \is_string($key) ? sprintf('"%s" => %s', $key, $value) : $value;
            }, array_keys($subject), $subject);

            $argsString .= ', '.(!\is_array($subject) ? $subject : '['.implode(', ', $subject).']');
        }

        return $argsString;
    }
}