<?php


namespace App\Service;

use App\Entity\Contact;
use Swift_Mailer;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Environment;

class ContactService
{
    private Swift_Mailer $mailer;
    private Environment $renderer;
    private ParameterBagInterface $params;
    private array $senderEmails = [];

    /**
     * @param Swift_Mailer $mailer
     * @param Environment $renderer
     * @param ParameterBagInterface $params
     */
    public function __construct(Swift_Mailer $mailer, Environment $renderer,ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->mailer = $mailer;
        $this->renderer = $renderer;
        try {
            $this->senderEmails = $this->params->get('senderEmails');
        } catch (ParameterNotFoundException $e) {
            dump('error');
        }
    }

    public function sendMail(Contact $contact)
    {
        $message = (new \Swift_Message())
            ->setFrom($this->senderEmails['administration'])
            ->setTo($this->senderEmails['administration'])
            ->setReplyTo($contact->getEmail())
            ->setSubject($contact->getSubject())
            ->setBody($this->renderer->render('contact/email.html.twig', [
                'contact' => $contact
            ]),'text/html');
        $this->mailer->send($message);
    }
}