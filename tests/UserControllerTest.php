<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private static ?int $userId = null;
    private static ?string $jwtToken = null;

    private static function getHeaders(): array
    {
        return [
            'HTTP_AUTHORIZATION' => 'Bearer ' . self::$jwtToken,
            'CONTENT_TYPE' => 'application/json'
        ];
    }

    public function testJWTTocken(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            "email" => "mail1@example.com",
            "password" => "XCrudUserPassword123!"
        ]));

        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(200);
        $responseContent = $response->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertIsArray($responseData, 'Response is not an array');
        $this->assertArrayHasKey('token', $responseData, 'Response does not contain token.');

        if (isset($responseData['token'])) {
            self::$jwtToken = $responseData['token'];

            echo PHP_EOL . "===== JWT Token  =====" . PHP_EOL;
            echo json_encode(json_decode($responseContent), JSON_PRETTY_PRINT) . PHP_EOL;
        }
    }


    public function testNewAndAddSalaries(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/user/new', [], [], self::getHeaders(), json_encode([
            "name" => "Test",
            "surname" => "Testuser",
            "email" => "test12345@example.com",
            "plainPassword" => "Password123!Y@90",
        ]));

        $this->assertResponseStatusCodeSame(201);
        $responseContent = $client->getResponse()->getContent();
        $this->assertJson($responseContent);

        $responseData = json_decode($responseContent, true);
        if (isset($responseData['id'])) {
            self::$userId = $responseData['id'];
        }
        $this->assertNotNull(self::$userId);

        # add-salary
        $months = getenv('MOUNT') ?: 2;
        for ($i = 0; $i < $months; $i++) {
            $salaryAmount = number_format(mt_rand(1000, 5000), 2, '.', '');
            $paymentDate = (new \DateTimeImmutable())->modify("-{$i} month")->format('Y-m-d');

            $client->request('POST', "/api/user/" . self::$userId . "/add-salary", [], [], self::getHeaders(), json_encode([
                "amount" => $salaryAmount,
                "paymentDate" => $paymentDate,
            ]));
            $responseContentSalary = $client->getResponse()->getContent();
            $this->assertResponseStatusCodeSame(201);
        }

        echo PHP_EOL . "===== New User with Salaries =====" . PHP_EOL;
        echo json_encode(json_decode($responseContent), JSON_PRETTY_PRINT) . PHP_EOL;
        echo json_encode(json_decode($responseContentSalary), JSON_PRETTY_PRINT) . PHP_EOL;
    }


    public function testShowAll(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/user/show/all', [], [], self::getHeaders());

        $this->assertResponseIsSuccessful();
        $responseContent = $client->getResponse()->getContent();
        $this->assertJson($client->getResponse()->getContent());

        echo PHP_EOL . "===== All Users =====" . PHP_EOL;
        echo json_encode(json_decode($responseContent), JSON_PRETTY_PRINT) . PHP_EOL;
    }


    public function testShow(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/user/' . self::$userId, [], [], self::getHeaders());

        $this->assertResponseIsSuccessful();
        $responseContent = $client->getResponse()->getContent();
        $this->assertJson($responseContent);

        echo PHP_EOL . "===== Show User =====" . PHP_EOL;
        echo json_encode(json_decode($responseContent), JSON_PRETTY_PRINT) . PHP_EOL;
    }

    public function testEdit(): void
    {
        $client = static::createClient();
        $client->request('PUT', '/api/user/' . self::$userId . '/edit', [], [], self::getHeaders(), json_encode([
            "name" => "TestRenamed",
            "surname" => "TestuserRenamed",
            "email" => "test12345Renamed@example.com",
            "plainPassword" => "Renamed123!Y@90",
        ]));

        $this->assertResponseIsSuccessful();
        $responseContent = $client->getResponse()->getContent();
        $this->assertJson($responseContent);

        echo PHP_EOL . "===== Edit User =====" . PHP_EOL;
        echo json_encode(json_decode($responseContent), JSON_PRETTY_PRINT) . PHP_EOL;
    }

    public function testDelete(): void
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/user/' . self::$userId, [], [], self::getHeaders());

        $this->assertResponseStatusCodeSame(200);
        $responseContent = $client->getResponse()->getContent();
        $this->assertJson($responseContent);

        echo PHP_EOL . "===== Delete User =====" . PHP_EOL;
        echo json_encode(json_decode($responseContent), JSON_PRETTY_PRINT) . PHP_EOL;
    }


}
