<?php

/**
 * PHP version 7.0
 *
 * Upload file Test
 *
 * @package  RetailCrm\Mg\Bot\Tests
 */

namespace RetailCrm\Mg\Bot\Tests;

use RetailCrm\Mg\Bot\Model\Response\FullFileResponse;
use RetailCrm\Mg\Bot\Model\Response\UploadFileResponse;
use RetailCrm\Mg\Bot\Test\TestCase;

/**
 * PHP version 7.0
 *
 * Class UploadFileTest
 *
 * @package  RetailCrm\Mg\Bot\Tests
 */
class FileTest extends TestCase
{
    /**
     * @group("upload")
     * @throws \Exception
     */
    public function testUploadFileByUrlEmpty()
    {
        $client = self::getApiClient(
            null,
            null,
            false,
            $this->getErrorsResponse(400, 'Something is not quite right.')
        );

        self::expectException(\InvalidArgumentException::class);
        $client->uploadFileByUrl('');
    }
    /**
     * @group("upload")
     * @throws \Exception
     */
    public function testUploadFileByUrlInvalid()
    {
        $client = self::getApiClient(
            null,
            null,
            false,
            $this->getErrorsResponse(400, 'Something is not quite right.')
        );

        self::expectException(\InvalidArgumentException::class);
        $client->uploadFileByUrl('rar');
    }

    /**
     * @group("upload")
     * @throws \Exception
     */
    public function testUploadFileByUrl()
    {
        $client = self::getApiClient(
            null,
            null,
            false,
            $this->getResponse('{"id":"881712bb-4062-4973-9e23-3373135836e2","type":"image","size":3773}')
        );

        $response = $client->uploadFileByUrl('https://2ip.ru/images/logo.gif');

        self::assertTrue($response->isSuccessful());
        self::assertEquals('881712bb-4062-4973-9e23-3373135836e2', $response->getId());
        self::assertEquals('image', $response->getType());
        self::assertEquals('3773', $response->getSize());
    }

    /**
     * @group("upload")
     * @throws \Exception
     */
    public function testUploadFileViaForm()
    {
        $client = self::getApiClient(
            null,
            null,
            false,
            $this->getResponse('{"id":"b2bdba90-166c-4e0a-829d-69f26a09fd2a","type":"file","size":214}')
        );

        $response = $client->uploadFile(__FILE__);

        self::assertInstanceOf(UploadFileResponse::class, $response);
        self::assertEquals('b2bdba90-166c-4e0a-829d-69f26a09fd2a', $response->getId());
        self::assertEquals('file', $response->getType());
        self::assertEquals(214, $response->getSize());
    }

    /**
     * @group("upload")
     * @throws \Exception
     */
    public function testGetFileById()
    {
        $fileId = 'b2bdba90-166c-4e0a-829d-69f26a09fd2a';
        $client = self::getApiClient(
            null,
            null,
            false,
            $this->getJsonResponse('getFile')
        );

        $response = $client->getFileById($fileId);

        self::assertInstanceOf(FullFileResponse::class, $response);
        self::assertEquals($fileId, $response->getId());

        $fileUrl = 'https://s3.eu-central-1.amazonaws.com/mg-node-files/files/21/b2bdba90-166c-4e0a-829d-69f26a09fd2a';
        self::assertEquals($fileUrl, $response->getUrl());
    }
}
