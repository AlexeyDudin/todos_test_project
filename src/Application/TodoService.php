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
        $todoRepository->add(TodoMapper::dtoToTodo($todo), true);
        return $todo;
    }
    public function getAll(TodoRepository $todoRepository):array {
        return TodoMapper::todosToDtoArray($todoRepository->getAll());
    }
    public function getArchive(TodoRepository $todoRepository):array {
        $qb = $todoRepository->createQueryBuilder('t')
            ->where('t.executed = TRUE');
        return TodoMapper::todosToDtoArray($todoRepository->findByQueryBuilder($qb));
    }
    public function getActive(TodoRepository $todoRepository):array {
        $qb = $todoRepository->createQueryBuilder('t')
            ->where('t.executed = FALSE');
        return TodoMapper::todosToDtoArray($todoRepository->findByQueryBuilder($qb));
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
        $todoReturned = clone $todo;
        $todoRepository->remove($todo, true);
        return TodoMapper::todoToDto($todoReturned);
    }
}