<?php

namespace App\Controller;

use App\Service\EmailType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Message;

class EmailController extends AbstractController
{
    private ParameterBagInterface $params;
    private MailerInterface $mailer;
    private array $senderEmails = [];

    /**
     * EmailController constructor.
     * @param ParameterBagInterface $params
     * @param MailerInterface $mailer
     */
    public function __construct(ParameterBagInterface $params, MailerInterface $mailer)
    {
        $this->params = $params;
        $this->mailer = $mailer;
        try {
            $this->senderEmails = $this->params->get('senderEmails');
        } catch (ParameterNotFoundException $e) {
            dump('error');
        }
    }

    /**
     * @param string $sender
     * @param string $receiver
     * @param EmailType $emailType
     * @param array $twigContext
     * @throws TransportExceptionInterface
     */
    private function sendEmail(string $sender, string $receiver, EmailType $emailType, array $twigContext)
    {
        $email = (new TemplatedEmail())
            ->from($sender)
            ->to($receiver)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('reply.to@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($emailType->getSubject())
            ->htmlTemplate($emailType->getTwigFilePath())
            ->context($twigContext);

        $this->mailer->send($email);
    }

    /**
     * @param string $receiver
     * @param EmailType $emailType
     * @param array $twigContext
     * @throws TransportExceptionInterface
     */
    public function sendNoReplyEmail(string $receiver, EmailType $emailType, array $twigContext)
    {
        $this->sendEmail($this->senderEmails['noreply'], $receiver, $emailType, $twigContext);
    }

    /**
     * @param $data
     * @throws TransportExceptionInterface
     */
    public function sendTestEmail($data)
    {
        $email = (new TemplatedEmail())
            ->from($this->senderEmails['noreply'])
            ->to('ambio.gaming@outlook.be')
            ->subject('test')
            ->htmlTemplate('email/test.html.twig')
            ->context([
                'data' => $data
            ]);
//            ->embedFromPath('resources/images/hdm-shop/logo.png', 'hdm-shop-logo');

        $this->mailer->send($email);
    }
}
