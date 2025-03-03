<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    #[Route('/send-email', name: 'send_email', methods: ['GET'])]
    public function sendEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('bedouimeriem55@gmail.com') // Replace with your Gmail address
            ->to('meriembedoui1@gmail.com') // Replace with the recipient's email
            ->subject('Test Email')
            ->text('This is a test email sent using Gmail SMTP.');

        // Send the email
        $mailer->send($email);

        // Return a response to confirm email has been sent
        return $this->json([
            'status' => 'Email sent successfully!',
        ]);
    }
}
