<?php

// src/EventSubscriber/ClaimSubscriber.php

namespace App\EventSubscriber;

use App\Event\NewClaimEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;





class ClaimSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            NewClaimEvent::class => 'sendClaimNotificationEmail',
        ];
    }

    public function sendClaimNotificationEmail(NewClaimEvent $event)
    {
        $claim = $event->getClaim(); // Récupérer la réclamation depuis l'événement
        $email = (new Email())
            ->from('malekfeki18@gmail.com')
            ->to('inesdkhl@gmail.com')
            ->subject('Nouvelle réclamation ajoutée')
            ->text('Une nouvelle réclamation a été ajoutée: ' . $claim->getDescription()); // Le contenu du mail dépend de votre implémentation
        $this->mailer->send($email);
    }
    
}
