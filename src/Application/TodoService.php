<?php

namespace App\Application;

use ApiPlatform\Elasticsearch\Exception\IndexNotFoundException;
use App\Entity\Todo;
use App\Repository\TodoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TodoService extends AbstractController
{
    public function addTodo(TodoRepository $todoRepository, Request $request):JsonResponse {
        $parameters = json_decode($request->getContent(), true);
        $text = $parameters['text'];
        $todo = new Todo($text, false);
        $todoRepository->add($todo, true);
        return $this->json($todo);
    }
    public function getAll(TodoRepository $todoRepository):JsonResponse {
        return $this->json($todoRepository->getAll());
    }
    public function getArchive(TodoRepository $todoRepository):JsonResponse {
        $qb = $todoRepository->createQueryBuilder('t')
            ->where('t.executed = TRUE');
        $result = $todoRepository->findByQueryBuilder($qb);

        return $this->json($result);
    }
    public function getActive(TodoRepository $todoRepository):JsonResponse {
        $qb = $todoRepository->createQueryBuilder('t')
            ->where('t.executed = FALSE');
        $result = $todoRepository->findByQueryBuilder($qb);
        return $this->json($result);
    }
    public function changeExecuted(TodoRepository $todoRepository, Request $request):JsonResponse {
        $parameters = json_decode($request->getContent(), true);
        $id = $parameters['id'];
        $todo = $todoRepository->findById($id);
        if (is_null($todo)) {
            throw new IndexNotFoundException("Todo с id = " . $id . " не найден");
        }
        $todo->setExecuted(!$todo->isExecuted());
        $todoRepository->commitChanges();
        return $this->json($todo);
    }
    public function delete(TodoRepository $todoRepository, Request $request):JsonResponse {
        $parameters = json_decode($request->getContent(), true);
        $id = $parameters['id'];
        $todo = $todoRepository->findById($id);
        if (is_null($todo)) {
            throw new IndexNotFoundException("Todo с id = " . $id . " не найден");
        }
        $todoReturned = clone $todo;
        $todoRepository->remove($todo, true);
        return $this->json($todoReturned);
    }
}