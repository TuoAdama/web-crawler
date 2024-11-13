<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class TwilioService
{
    private string $token;
    private string $accountSID;

    private string $senderNumber;

    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly LoggerInterface                $smsLogger,
    )
    {
        $this->token = $this->parameterBag->get('twilio_auth_token');
        $this->accountSID = $this->parameterBag->get('twilio_account_sid');
        $this->senderNumber = $this->parameterBag->get('twilio_sender_number');
    }

    /**
     * @throws ConfigurationException
     */
    public function send(string $to, string $message): void
    {
        $twilio = new Client($this->accountSID, $this->token);
        try {
            $twilio->messages->create(
                "+33".$to,
                [
                    'From' => $this->senderNumber,
                    'Body' => $message,
                ]
            );
            $this->smsLogger->error("SMS Send to {number}, body: {body}", [
                'number' => $to,
                'body' => $message,
            ]);
        } catch (TwilioException $e) {
            $this->smsLogger->error($e->getMessage());
        }
    }
}