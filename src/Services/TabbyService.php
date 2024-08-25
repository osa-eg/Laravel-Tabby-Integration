<?php

namespace Osama\TabbyIntegration\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

class TabbyService
{
    protected $client;
    protected $publicKey;
    protected $secretKey;
    protected $merchantCode;
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->client = new Client([
            'base_uri' => 'https://api.tabby.ai/',
            'timeout'  => 10.0,
        ]);

        $this->publicKey = config('tabby.public_key');
        $this->secretKey = config('tabby.secret_key');
        $this->merchantCode = config('tabby.merchant_code');
        $this->logger = $logger;
    }

    // Create a new checkout session
    public function createCheckoutSession(array $data)
    {
        $this->logger->info('Creating checkout session', ['data' => $data]);

        try {
            $response = $this->client->post('api/v2/checkout', [
                'headers' => [
                    'Authorization' => "Bearer {$this->publicKey}",
                    'Content-Type'  => 'application/json',
                ],
                'json' => $data,
            ]);

            $body = $response->getBody()->getContents();
            $this->logger->info('Checkout session created', ['response' => $body]);

            return json_decode($body, true);
        } catch (GuzzleException $e) {
            $this->logger->error('Error creating checkout session', ['error' => $e->getMessage()]);
            throw new \Exception('Error creating checkout session: ' . $e->getMessage());
        }
    }

    // Retrieve a payment by ID
    public function getPayment(string $paymentId)
    {
        $this->logger->info('Retrieving payment', ['paymentId' => $paymentId]);

        try {
            $response = $this->client->get("api/v2/payments/{$paymentId}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->secretKey}",
                ],
            ]);

            $body = $response->getBody()->getContents();
            $this->logger->info('Payment retrieved', ['response' => $body]);

            return json_decode($body, true);
        } catch (GuzzleException $e) {
            $this->logger->error('Error retrieving payment', ['error' => $e->getMessage()]);
            throw new \Exception('Error retrieving payment: ' . $e->getMessage());
        }
    }

    // Update a payment by ID
    public function updatePayment(string $paymentId, array $data)
    {
        $this->logger->info('Updating payment', ['paymentId' => $paymentId, 'data' => $data]);

        try {
            $response = $this->client->put("api/v2/payments/{$paymentId}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->secretKey}",
                    'Content-Type'  => 'application/json',
                ],
                'json' => $data,
            ]);

            $body = $response->getBody()->getContents();
            $this->logger->info('Payment updated', ['response' => $body]);

            return json_decode($body, true);
        } catch (GuzzleException $e) {
            $this->logger->error('Error updating payment', ['error' => $e->getMessage()]);
            throw new \Exception('Error updating payment: ' . $e->getMessage());
        }
    }

    // Capture a payment by ID
    public function capturePayment(string $paymentId, array $data)
    {
        $this->logger->info('Capturing payment', ['paymentId' => $paymentId, 'data' => $data]);

        try {
            $response = $this->client->post("api/v2/payments/{$paymentId}/captures", [
                'headers' => [
                    'Authorization' => "Bearer {$this->secretKey}",
                    'Content-Type'  => 'application/json',
                ],
                'json' => $data,
            ]);

            $body = $response->getBody()->getContents();
            $this->logger->info('Payment captured', ['response' => $body]);

            return json_decode($body, true);
        } catch (GuzzleException $e) {
            $this->logger->error('Error capturing payment', ['error' => $e->getMessage()]);
            throw new \Exception('Error capturing payment: ' . $e->getMessage());
        }
    }

    // Refund a payment by ID
    public function refundPayment(string $paymentId, array $data)
    {
        $this->logger->info('Refunding payment', ['paymentId' => $paymentId, 'data' => $data]);

        try {
            $response = $this->client->post("api/v2/payments/{$paymentId}/refunds", [
                'headers' => [
                    'Authorization' => "Bearer {$this->secretKey}",
                    'Content-Type'  => 'application/json',
                ],
                'json' => $data,
            ]);

            $body = $response->getBody()->getContents();
            $this->logger->info('Payment refunded', ['response' => $body]);

            return json_decode($body, true);
        } catch (GuzzleException $e) {
            $this->logger->error('Error refunding payment', ['error' => $e->getMessage()]);
            throw new \Exception('Error refunding payment: ' . $e->getMessage());
        }
    }

    // List all payments
    public function listPayments(array $params = [])
    {
        $this->logger->info('Listing payments', ['params' => $params]);

        try {
            $response = $this->client->get('api/v2/payments', [
                'headers' => [
                    'Authorization' => "Bearer {$this->secretKey}",
                ],
                'query' => $params,
            ]);

            $body = $response->getBody()->getContents();
            $this->logger->info('Payments listed', ['response' => $body]);

            return json_decode($body, true);
        } catch (GuzzleException $e) {
            $this->logger->error('Error listing payments', ['error' => $e->getMessage()]);
            throw new \Exception('Error listing payments: ' . $e->getMessage());
        }
    }

    // Register a new webhook
    public function registerWebhook(array $data)
    {
        $this->logger->info('Registering webhook', ['data' => $data]);

        try {
            $response = $this->client->post('api/v1/webhooks', [
                'headers' => [
                    'Authorization' => "Bearer {$this->secretKey}",
                    'X-Merchant-Code' => $this->merchantCode,
                    'Content-Type'  => 'application/json',
                ],
                'json' => $data,
            ]);

            $body = $response->getBody()->getContents();
            $this->logger->info('Webhook registered', ['response' => $body]);

            return json_decode($body, true);
        } catch (GuzzleException $e) {
            $this->logger->error('Error registering webhook', ['error' => $e->getMessage()]);
            throw new \Exception('Error registering webhook: ' . $e->getMessage());
        }
    }

    // Retrieve all webhooks
    public function listWebhooks()
    {
        $this->logger->info('Listing webhooks');

        try {
            $response = $this->client->get('api/v1/webhooks', [
                'headers' => [
                    'Authorization' => "Bearer {$this->secretKey}",
                    'X-Merchant-Code' => $this->merchantCode,
                ],
            ]);

            $body = $response->getBody()->getContents();
            $this->logger->info('Webhooks listed', ['response' => $body]);

            return json_decode($body, true);
        } catch (GuzzleException $e) {
            $this->logger->error('Error listing webhooks', ['error' => $e->getMessage()]);
            throw new \Exception('Error listing webhooks: ' . $e->getMessage());
        }
    }

    // Retrieve a specific webhook by ID
    public function getWebhook(string $webhookId)
    {
        $this->logger->info('Retrieving webhook', ['webhookId' => $webhookId]);

        try {
            $response = $this->client->get("api/v1/webhooks/{$webhookId}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->secretKey}",
                    'X-Merchant-Code' => $this->merchantCode,
                ],
            ]);

            $body = $response->getBody()->getContents();
            $this->logger->info('Webhook retrieved', ['response' => $body]);

            return json_decode($body, true);
        } catch (GuzzleException $e) {
            $this->logger->error('Error retrieving webhook', ['error' => $e->getMessage()]);
            throw new \Exception('Error retrieving webhook: ' . $e->getMessage());
        }
    }

    // Update a specific webhook by ID
    public function updateWebhook(string $webhookId, array $data)
    {
        $this->logger->info('Updating webhook', ['webhookId' => $webhookId, 'data' => $data]);

        try {
            $response = $this->client->put("api/v1/webhooks/{$webhookId}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->secretKey}",
                    'X-Merchant-Code' => $this->merchantCode,
                    'Content-Type'  => 'application/json',
                ],
                'json' => $data,
            ]);

            $body = $response->getBody()->getContents();
            $this->logger->info('Webhook updated', ['response' => $body]);

            return json_decode($body, true);
        } catch (GuzzleException $e) {
            $this->logger->error('Error updating webhook', ['error' => $e->getMessage()]);
            throw new \Exception('Error updating webhook: ' . $e->getMessage());
        }
    }

    // Remove a webhook by ID
    public function removeWebhook(string $webhookId)
    {
        $this->logger->info('Removing webhook', ['webhookId' => $webhookId]);

        try {
            $response = $this->client->delete("api/v1/webhooks/{$webhookId}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->secretKey}",
                    'X-Merchant-Code' => $this->merchantCode,
                ],
            ]);

            $body = $response->getBody()->getContents();
            $this->logger->info('Webhook removed', ['response' => $body]);

            return json_decode($body, true);
        } catch (GuzzleException $e) {
            $this->logger->error('Error removing webhook', ['error' => $e->getMessage()]);
            throw new \Exception('Error removing webhook: ' . $e->getMessage());
        }
    }
}
