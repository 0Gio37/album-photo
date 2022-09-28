<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class Mailer
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig){
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendMail(string $from, string $to, string $subject, string $template, array $parameter):void{
        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->html(
                $this->twig->render($template, $parameter),
                'text/html'
            );
        $this->mailer->send($email);
    }
}