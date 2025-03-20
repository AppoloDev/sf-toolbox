<?php

namespace AppoloDev\SFToolboxBundle\MessageHandler;

use AppoloDev\SFToolboxBundle\Message\EmailMessage;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsMessageHandler]
class EmailSenderHandler extends AbstractController
{
    public function __construct(
        #[Autowire('%env(SENDER_EMAIL)%')]
        private readonly string $senderEmail,
        #[Autowire('%env(SENDER_NAME)%')]
        private readonly string $senderName,
        private readonly MailerInterface $mailer,
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(EmailMessage $emailMessage): void
    {
        $logoPath = $this->projectDir.'/public/assets/images/logo.png';

        $email = (new TemplatedEmail())
            ->from(new Address($this->senderEmail, $this->senderName))
            ->to(...$emailMessage->getRecipients())
            ->subject($this->translator->trans($emailMessage->getObject()))
            ->embedFromPath($logoPath, 'logo')
            ->htmlTemplate($emailMessage->getTemplate())
            ->context(array_merge($emailMessage->getParameters(), ['locale' => $emailMessage->getLocale()]))
        ;

        if (!is_null($emailMessage->getFile())) {
            $email->attach($emailMessage->getFile()->getContent(), $emailMessage->getFilename() ?? 'attachment');
        }

        $this->mailer->send($email);
    }
}
