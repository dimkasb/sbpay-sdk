<?php

namespace SBPay;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use SBPay\Contract\ISBPayPayments;
use SBPay\Contract\ISBPaySubscriptions;
use SBPay\Exceptions\AccessDeniedException;
use SBPay\Exceptions\BadRequestException;
use SBPay\Exceptions\NotFoundException;
use SBPay\Exceptions\UnauthorizedException;
use SBPay\Exceptions\UnexpectedException;

class SBPayClient implements Contract\ISBPayClient
{

    /**
     * SBPay token
     *
     * @var string
     */
    private string $token;

    /**
     * SBPay secret
     *
     * @var string
     */
    private string $secret;

    /**
     * SBPay merchant
     *
     * @var string
     */
    private string $merchant;

    /**
     * SBPay server
     *
     * @var string
     */
    private string $server;

    /**
     * SBPay signature algorithm
     *
     * @var string
     */
    private string $signatureAlgorithm;

    /**
     * Payments service instance
     *
     * @var ISBPayPayments|null
     */
    private ?ISBPayPayments $paymentsService = null;

    /**
     * Subscriptions service instance
     *
     * @var ISBPaySubscriptions|null
     */
    private ?ISBPaySubscriptions $subscriptionsService = null;

    /**
     * HTTP Client instance
     *
     * @var ClientInterface|null
     */
    private static ?ClientInterface $httpClient = null;

    /**
     * @param string $token
     * @param string $secret
     * @param string $merchant
     * @param string $server
     * @param string $signatureAlgorithm
     */
    public function __construct(
        string $token, string $secret, string $merchant,
        string $server = 'https://app.sbpay.me/', string $signatureAlgorithm = 'sha256'
    )
    {
        $this->token = $token;
        $this->secret = $secret;
        $this->merchant = $merchant;
        $this->server = $server;
        $this->signatureAlgorithm = $signatureAlgorithm;
    }

    /**
     * Return payments service
     *
     * @return ISBPayPayments
     */
    public function payments(): ISBPayPayments
    {
        if (!$this->paymentsService) {
            $this->paymentsService = new SBPayPayments($this);
        }
        return $this->paymentsService;
    }

    /**
     * Return subscriptions service
     *
     * @return ISBPaySubscriptions
     */
    public function subscriptions(): ISBPaySubscriptions
    {
        if (!$this->subscriptionsService) {
            $this->subscriptionsService = new SBPaySubscriptions($this);
        }
        return $this->subscriptionsService;
    }

    /**
     * Make request to SBPay API
     *
     * @param string $method
     * @param string $path
     * @param string|null $body
     * @param string|null $signature
     * @param string|null $timestamp
     * @return array|null
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UnexpectedException
     */
    public function request(
        string $method, string $path, ?string $body = null, ?string $signature = null, ?string $timestamp = null
    ): ?array
    {
        $uri = rtrim($this->server, '/') . '/' . ltrim($path, '/');
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Auth-Token' => $this->token,
            'X-Merchant' => $this->merchant
        ];
        if ($signature) {
            $headers['X-Signature'] = $signature;
        }
        if ($timestamp) {
            $headers['X-Timestamp'] = $timestamp;
        }

        try {
            $response = $this->httpClient()->sendRequest(
                new Request($method, $uri, $headers, $body)
            );

            $content = $response->getBody()->getContents();
            $contentData = null;
            if ($content) {
                $contentData = json_decode($content, true);
            }
            switch ($response->getStatusCode()) {
                case 200:
                    return $contentData;
                case 400:
                    throw new Exceptions\BadRequestException(
                        $contentData['message'] ?? 'Bad request',
                        $contentData['errors'] ?? null
                    );
                case 401:
                    throw new Exceptions\UnauthorizedException(
                        $contentData['message'] ?? 'Unauthorized'
                    );
                case 403:
                    throw new Exceptions\AccessDeniedException(
                        $contentData['message'] ?? 'Forbidden'
                    );
                case 404:
                    throw new Exceptions\NotFoundException(
                        $contentData['message'] ?? 'Not found'
                    );
                case 405:
                    throw new Exceptions\BadRequestException(
                        $contentData['message'] ?? 'Method not allowed', null
                    );
                case 422:
                    throw new Exceptions\BadRequestException(
                        $contentData['message'] ?? 'Unprocessable entity',
                        $contentData['errors'] ?? null
                    );
                case 500:
                    throw new Exceptions\UnexpectedException(
                        $contentData['message'] ?? 'Internal server error'
                    );
                default:
                    return null;
            }
        } catch (ClientExceptionInterface $e) {
            throw new UnexpectedException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Make signed request to SBPay API
     *
     * @param string $method
     * @param string $path
     * @param array $data
     * @return array|null
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UnexpectedException
     */
    private function signedRequest(string $method, string $path, array $data = []): ?array
    {
        $body = json_encode(array_merge($data, [
            'timestamp' => date('c'),
            'algo' => $this->signatureAlgorithm
        ]));

        $signature = $this->sign($body);

        return $this->request($method, $path, $body, $signature);
    }

    /**
     * Make signed POST request
     *
     * @param string $path
     * @param array $data
     * @return array|null
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UnexpectedException
     */
    public function signedPost(string $path, array $data = []): ?array
    {
        return $this->signedRequest('POST', $path, $data);
    }

    /**
     * Make signed PUT request
     *
     * @param string $path
     * @param array $data
     * @return array|null
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UnexpectedException
     */
    public function signedPut(string $path, array $data = []): ?array
    {
        return $this->signedRequest('PUT', $path, $data);
    }

    /**
     * Make signed DELETE request
     *
     * @param string $path
     * @param array $data
     * @return array|null
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UnexpectedException
     */
    public function signedDelete(string $path, array $data = []): ?array
    {
        return $this->signedRequest('DELETE', $path, $data);
    }

    /**
     * Make signed GET request
     *
     * @param string $path
     * @param string $dataToSign
     * @return array|null
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UnexpectedException
     */
    public function signedGet(
        string $path, string $dataToSign
    ): ?array
    {
        $time = time();
        $signature = $this->sign($dataToSign . '|' . $time);

        return $this->request('GET', $path, null, $signature, $time);
    }

    /**
     * Set HTTP client. You can set your implementation of HTTP Client,
     * otherwise GuzzleHttp\Client will be used.
     *
     * @param ClientInterface $client
     * @return void
     */
    public static function setHttpClient(ClientInterface $client): void
    {
        self::$httpClient = $client;
    }

    /**
     * Return token
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Return secret key
     *
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * Return merchant
     *
     * @return string
     */
    public function getMerchant(): string
    {
        return $this->merchant;
    }

    /**
     * Returns HTTP Client instance
     *
     * @return ClientInterface
     */
    protected function httpClient(): ClientInterface
    {
        if (!self::$httpClient) {
            self::$httpClient = SBPayHttpClientFactory::create();
        }
        return self::$httpClient;
    }

    /**
     * Make signature for request
     *
     * @param string $requestData
     * @return string
     */
    private function sign(string $requestData): string
    {
        return hash_hmac($this->signatureAlgorithm, $requestData, $this->secret);
    }

}