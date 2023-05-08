<?php

namespace App\Tests\Controller;

use App\Entity\DTOs\TodoDto;
use App\Entity\Todo;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TodoControllerTest extends WebTestCase
{
    private function testAddTodo(): TodoDto {
        $todo = new Todo("HelloWorld", false);
        $client = static::createClient();
        $clientResult = $client->request('POST', '/todo/add', array($todo));

        $this->assertResponseIsSuccessful();
        return json_decode($clientResult->text());
    }

    public function testAll(): void {
        $todo = $this->testAddTodo();

    }
}
