<?php

declare(strict_types=1);

namespace App\Common\Client;

use App\Common\Exception\BadPayloadException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractHttpClient
{
    protected readonly Serializer $serializer;

    public function __construct(
        private readonly HttpClientInterface $client,
        #[Autowire('%api.secret.key%')]
        private readonly string $apiSecretKey,
    ) {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    protected function sendServiceRequest(
        string $uri,
        array $query = [],
        array $requestBody = [],
        string $method = Request::METHOD_GET
    ): ResponseInterface {
        foreach ([$query, $requestBody] as $payload) {
            $this->validatePayload($payload);
        }

        return $this->client->request(
            $method,
            'http://nginx/api/'.$this->getServiceName().$uri,
            [
                'query' => $query + ['XDEBUG_TRIGGER' => '1'],
                'body' => $this->serializer->serialize($requestBody, JsonEncoder::FORMAT),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Api-Secret' => $this->apiSecretKey,
                ],
            ]
        );
    }

    private function validatePayload($data): void
    {
        foreach ($data as $item) {
            if (is_array($item)) {
                $this->validatePayload($item);
            } elseif (!is_scalar($item) && !is_null($item)) {
                throw new BadPayloadException();
            }
        }
    }

    abstract protected function getServiceName(): string;
}
