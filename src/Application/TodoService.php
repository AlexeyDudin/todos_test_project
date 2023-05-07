<?php

namespace App\Application;

use ApiPlatform\Elasticsearch\Exception\IndexNotFoundException;
use App\Entity\Todo;
use App\Entity\DTOs\TodoDto;
use App\Repository\TodoRepository;
use App\Converters\TodoMapper;

class TodoService
{
    public function addTodo(TodoRepository $todoRepository, TodoDto $todo):TodoDto {
        $todoRepository->add(TodoMapper::dtoToTodo($todo));
        return $todo;
    }
    public function getAll(TodoRepository $todoRepository):array {
        return TodoMapper::todosToDtoArray($todoRepository->getAll());
    }
    public function getArchive(TodoRepository $todoRepository):array {

        return TodoMapper::todosToDtoArray($todoRepository->getArchiveTodos());
    }
    public function getActive(TodoRepository $todoRepository):array {

        return TodoMapper::todosToDtoArray($todoRepository->getActiveTodos());
    }

    /**
     * @throws IndexNotFoundException
     */
    public function changeExecuted(TodoRepository $todoRepository, int $id):TodoDto {
        $todo = $todoRepository->findById($id);
        if (is_null($todo)) {
            throw new IndexNotFoundException("Todo с id = " . $id . " не найден");
        }
        $todo->setExecuted(!$todo->isExecuted());
        $todoRepository->commitChanges();
        return TodoMapper::todoToDto($todo);
    }

    /**
     * @throws IndexNotFoundException
     */
    public function delete(TodoRepository $todoRepository, int $id):TodoDto {
        $todo = $todoRepository->findById($id);
        if (is_null($todo)) {
            throw new IndexNotFoundException("Todo с id = " . $id . " не найден");
        }
        $todoReturned = TodoMapper::todoToDto($todo);
        $todoRepository->remove($todo);
        return $todoReturned;
    }
}