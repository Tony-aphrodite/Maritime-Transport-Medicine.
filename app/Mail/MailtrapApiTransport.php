<?php

namespace App\Mail;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;
use Symfony\Component\Mime\Email;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MailtrapApiTransport extends AbstractTransport
{
    private string $apiToken;

    public function __construct(string $apiToken)
    {
        parent::__construct();
        $this->apiToken = $apiToken;
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $from = $email->getFrom()[0] ?? null;
        $to = $email->getTo();

        $payload = [
            'from' => [
                'email' => $from ? $from->getAddress() : 'hello@demomailtrap.co',
                'name' => $from ? ($from->getName() ?: 'Maritime Transport Medicine') : 'Maritime Transport Medicine',
            ],
            'to' => array_map(function($address) {
                return [
                    'email' => $address->getAddress(),
                    'name' => $address->getName() ?: null,
                ];
            }, $to),
            'subject' => $email->getSubject(),
            'html' => $email->getHtmlBody(),
            'text' => $email->getTextBody(),
            'category' => 'Email Verification',
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiToken,
            'Content-Type' => 'application/json',
        ])->post('https://send.api.mailtrap.io/api/send', $payload);

        if (!$response->successful()) {
            Log::error('Mailtrap API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Failed to send email via Mailtrap API: ' . $response->body());
        }

        Log::info('Email sent via Mailtrap API', [
            'to' => array_map(fn($a) => $a->getAddress(), $to),
            'subject' => $email->getSubject(),
        ]);
    }

    public function __toString(): string
    {
        return 'mailtrap+api://send.api.mailtrap.io';
    }
}
