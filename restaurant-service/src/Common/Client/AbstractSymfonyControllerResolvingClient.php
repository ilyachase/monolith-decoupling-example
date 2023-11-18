<?php

declare(strict_types=1);

namespace App\Common\Client;

use App\Common\Exception\BadPayloadException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class AbstractSymfonyControllerResolvingClient
{
    public const IS_INTERNAL_REQUEST_ATTRIBUTE_KEY = 'is-internal-request';

    protected readonly Serializer $serializer;

    public function __construct(
        private readonly HttpKernelInterface $httpKernel,
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
    ): Response {
        foreach ([$query, $requestBody] as $payload) {
            $this->validatePayload($payload);
        }

        $request = new Request(
            query: $query,
            request: $requestBody,
            content: json_encode($requestBody, JSON_THROW_ON_ERROR),
        );

        $request->setMethod($method);
        $request->server->set('REQUEST_URI', $uri);
        $request->attributes->set(self::IS_INTERNAL_REQUEST_ATTRIBUTE_KEY, true);

        return $this->httpKernel->handle($request, HttpKernelInterface::SUB_REQUEST);
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
}
