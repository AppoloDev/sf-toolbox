<?php

namespace AppoloDev\SFToolboxBundle\MessageHandler;

use App\Kernel;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Address;

#[AsMessageHandler]
class EmailSenderHandler extends AbstractController
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly Kernel $kernel,
        private readonly Packages $packages,
        private readonly string $senderEmail = 'contact@appolo.fr',
        private readonly string $senderName = 'Contact',
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(SendEmailMessage $sendEmailMessage): void {
        $logoPath = $this->kernel->getProjectDir().
            DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.
            $this->packages->getUrl('assets/images/logo.png')
        ;

        /** @var array{'recipients', 'object', 'template', 'parameters'} $emailMessage */
        $emailMessage = $sendEmailMessage->getMessage()->toIterable();

        $email = (new TemplatedEmail())
            ->from(new Address($this->senderEmail, $this->senderName))
            ->to(...$emailMessage['recipients'])
            ->subject($emailMessage['subject'])
            ->embedFromPath($logoPath, 'logo')
            ->htmlTemplate($emailMessage['template'])
            ->context($emailMessage['parameters'])
        ;

        $this->mailer->send($email);
    }
}
