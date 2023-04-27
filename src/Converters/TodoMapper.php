<?php

namespace App\Converters;

use App\Entity\DTOs\TodoDto;
use App\Entity\Todo;

class TodoMapper
{
    public static function dtoToTodo(TodoDto $todoDto):Todo {
        return new Todo($todoDto->getText(), $todoDto->isExecuted(), $todoDto->getId());
    }

    public static function todoToDto(Todo $todo):TodoDto {
        return new TodoDto($todo->getId(), $todo->getText(), $todo->isExecuted());
    }

    public static function todosToDtoArray(array $todos): array {
        if (empty($todos) or is_null($todos)) {
            return [];
        }
        return array_map('todoToDto', $todos);
    }
}