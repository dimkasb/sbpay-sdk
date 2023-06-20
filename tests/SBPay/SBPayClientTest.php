<?php

namespace SBPay;

use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use SBPay\Contract\ISBPayPayments;
use SBPay\Contract\ISBPaySubscriptions;

class SBPayClientTest extends TestCase
{

    public function testGetters()
    {
        // test that it will return token passed in constructor
        $client = new SBPayClient('token', 'secret', 'merchant');
        $this->assertEquals('token', $client->getToken());
        $this->assertEquals('secret', $client->getSecret());
        $this->assertEquals('merchant', $client->getMerchant());
    }

    /**
     * Test signed PUT request
     *
     * @return void
     * @throws Exceptions\AccessDeniedException
     * @throws Exceptions\BadRequestException
     * @throws Exceptions\NotFoundException
     * @throws Exceptions\UnauthorizedException
     * @throws Exceptions\UnexpectedException
     */
    public function testSignedPut()
    {
        $client = new SBPayClient('token', 'secret', 'merchant');
        $httpClient = $this->getHttpClientMock();
        $client->setHttpClient($httpClient);
        $httpClient->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function ($request) {
                $this->assertEquals('PUT', $request->getMethod());
                $this->assertEquals('https://app.sbpay.me/api/order/1', $request->getUri()->__toString());
                $this->assertEquals('merchant', $request->getHeader('X-Merchant')[0]);
                $this->assertNotEmpty($request->getHeader('X-Signature')[0]);

                $data = json_decode($request->getBody()->getContents(), true);

                $this->assertArrayHasKey('test', $data);
                $this->assertArrayHasKey('timestamp', $data);
                $this->assertArrayHasKey('algo', $data);

                return true;
            }));
        $client->signedPut('/order/1', ['test' => 'test']);
    }

    /**
     * Test signed POST request
     *
     * @return void
     * @throws Exceptions\AccessDeniedException
     * @throws Exceptions\BadRequestException
     * @throws Exceptions\NotFoundException
     * @throws Exceptions\UnauthorizedException
     * @throws Exceptions\UnexpectedException
     */
    public function testSignedPost()
    {
        $client = new SBPayClient('token', 'secret', 'merchant');
        $httpClient = $this->getHttpClientMock();
        $client->setHttpClient($httpClient);
        $httpClient->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function ($request) {
                $this->assertEquals('POST', $request->getMethod());
                $this->assertEquals('https://app.sbpay.me/api/order/1', $request->getUri()->__toString());
                $this->assertEquals('merchant', $request->getHeader('X-Merchant')[0]);
                $this->assertNotEmpty($request->getHeader('X-Signature')[0]);

                $data = json_decode($request->getBody()->getContents(), true);

                $this->assertArrayHasKey('test', $data);
                $this->assertArrayHasKey('timestamp', $data);
                $this->assertArrayHasKey('algo', $data);

                return true;
            }));
        $client->signedPost('/order/1', ['test' => 'test']);
    }

    /**
     * Test signed DELETE request
     *
     * @return void
     * @throws Exceptions\AccessDeniedException
     * @throws Exceptions\BadRequestException
     * @throws Exceptions\NotFoundException
     * @throws Exceptions\UnauthorizedException
     * @throws Exceptions\UnexpectedException
     */
    public function testSignedDelete()
    {
        $client = new SBPayClient('token', 'secret', 'merchant');
        $httpClient = $this->getHttpClientMock();
        $client->setHttpClient($httpClient);
        $httpClient->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function ($request) {
                $this->assertEquals('DELETE', $request->getMethod());
                $this->assertEquals('https://app.sbpay.me/api/order/1', $request->getUri()->__toString());
                $this->assertEquals('merchant', $request->getHeader('X-Merchant')[0]);
                $this->assertNotEmpty($request->getHeader('X-Signature')[0]);

                $data = json_decode($request->getBody()->getContents(), true);

                $this->assertArrayHasKey('test', $data);
                $this->assertArrayHasKey('timestamp', $data);
                $this->assertArrayHasKey('algo', $data);

                return true;
            }));
        $client->signedDelete('/order/1', ['test' => 'test']);
    }

    /**
     * Test that correct instance returned
     *
     * @return void
     */
    public function testSubscriptions()
    {
        $client = new SBPayClient('token', 'secret', 'merchant');
        $this->assertInstanceOf(ISBPaySubscriptions::class, $client->subscriptions());
    }

    /**
     * Test that correct instance returned
     *
     * @return void
     */
    public function testPayments()
    {
        $client = new SBPayClient('token', 'secret', 'merchant');
        $this->assertInstanceOf(ISBPayPayments::class, $client->payments());
    }

    /**
     * Test that correct http client is used
     *
     * @return void
     * @throws Exceptions\AccessDeniedException
     * @throws Exceptions\BadRequestException
     * @throws Exceptions\NotFoundException
     * @throws Exceptions\UnauthorizedException
     * @throws Exceptions\UnexpectedException
     */
    public function testSetHttpClient()
    {
        $client = new SBPayClient('token', 'secret', 'merchant');
        $httpClient = $this->getHttpClientMock();
        $client->setHttpClient($httpClient);
        $httpClient
            ->expects($this->once())
            ->method('sendRequest');

        $client->signedPut('/order/1', ['test' => 'test']);
    }

    /**
     * Test that signed GET request will be called
     *
     * @return void
     * @throws Exceptions\AccessDeniedException
     * @throws Exceptions\BadRequestException
     * @throws Exceptions\NotFoundException
     * @throws Exceptions\UnauthorizedException
     * @throws Exceptions\UnexpectedException
     */
    public function testSignedGet()
    {
        $client = new SBPayClient('token', 'secret', 'merchant');
        $httpClient = $this->getHttpClientMock();
        $client->setHttpClient($httpClient);
        $httpClient->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function ($request) {
                $this->assertEquals('GET', $request->getMethod());
                $this->assertEquals('https://app.sbpay.me/api/order/1', $request->getUri()->__toString());
                $this->assertEquals('merchant', $request->getHeader('X-Merchant')[0]);
                $this->assertNotEmpty($request->getHeader('X-Signature')[0]);
                $this->assertNotEmpty($request->getHeader('X-Timestamp')[0]);

                return true;
            }));
        $client->signedGet('/order/1', '1');
    }

    /**
     * Mock HTTP client
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|(ClientInterface&\PHPUnit\Framework\MockObject\MockObject)
     */
    private function getHttpClientMock()
    {
        return $this->getMockBuilder(ClientInterface::class)
            ->getMock();
    }

}
