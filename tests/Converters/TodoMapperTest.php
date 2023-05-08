<?php

namespace App\Tests\Converters;

use App\Converters\TodoMapper;
use App\Entity\DTOs\TodoDto;
use App\Entity\Todo;
use PHPUnit\Framework\TestCase;

class TodoMapperTest extends TestCase
{
    public function testDtoToTodo(): void {
        $todoId = 1;
        $todoText = "HelloWorld";
        $todoExecuted = false;

        $dto = new TodoDto($todoId, $todoText, $todoExecuted);

        $expected = new Todo($todoText, $todoExecuted, $todoId);

        $this->assertEquals($expected, TodoMapper::dtoToTodo($dto));
    }

    public function testTodoToDto(): void {
        $todoId = 1;
        $todoText = "HelloWorld";
        $todoExecuted = false;

        $todo = new Todo($todoText, $todoExecuted, $todoId);

        $expected = new TodoDto($todoId, $todoText, $todoExecuted);

        $this->assertEquals($expected, TodoMapper::todoToDto($todo));
    }

    public function testTodosToDtoArray(): void {
        $firstTodoId = 1;
        $firstTodoText = "HelloWorld";
        $firstTodoExecuted = false;

        $secondTodoId = 1;
        $secondTodoText = "HelloWorld";
        $secondTodoExecuted = false;

        $firstTodo = new Todo($firstTodoText, $firstTodoExecuted, $firstTodoId);
        $secondTodo = new Todo($secondTodoText, $secondTodoExecuted, $secondTodoId);

        $todoList = array($firstTodo, $secondTodo);

        $expected = array(TodoMapper::todoToDto($firstTodo), TodoMapper::todoToDto($secondTodo));

        $this->assertEquals($expected, TodoMapper::todosToDtoArray($todoList));
    }

    public function testEmptyTodosToDtoArray(): void {
        $todoArray = array();
        $expected = $todoArray;
        $this->assertEquals($expected, TodoMapper::todosToDtoArray($todoArray));
    }
}
