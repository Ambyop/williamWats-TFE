<?php


namespace App\Service;

use App\Entity\Contact;
use Swift_Mailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ContactService
{
    private MailerInterface $mailer;
    private Environment $renderer;
    private ParameterBagInterface $params;
    private array $senderEmails = [];

    /**
     * @param MailerInterface $mailer
     * @param Environment $renderer
     * @param ParameterBagInterface $params
     */
    public function __construct(MailerInterface $mailer, Environment $renderer,ParameterBagInterface $params)
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

    /**
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function sendMail(Contact $contact)
    {
        $message = (new TemplatedEmail())
            ->from($this->senderEmails['administration'])
            ->to($this->senderEmails['administration'])
            ->replyTo($contact->getEmail())
            ->subject($contact->getSubject())
            ->htmlTemplate('contact/email.html.twig')
            ->context([
                'contact' => $contact
            ])
            ->embedFromPath('img/laVilette.jpg', 'villette-logo');
        $this->mailer->send($message);
    }
}