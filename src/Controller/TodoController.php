<?php

namespace App\Controller;

use ApiPlatform\Elasticsearch\Exception\IndexNotFoundException;
use ApiPlatform\OpenApi\Model\Response;
use App\Application\TodoService;
use App\Entity\Todo;
use App\Repository\TodoRepository;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class TodoController extends AbstractController
{
    /**
     * @Route("/todo/add", name="todo_add")
     */
    public function addTodo(TodoService $todoService, TodoRepository $todoRepository, Request $request):JsonResponse {
        try {
            return $todoService->addTodo($todoRepository, $request);
        }
        catch(\Exception $e) {
            return $this->json($e->getMessage(), 404);
        }
    }

    /**
     * @Route("/todo/getAll", name="todos_all")
     */
    public function getAll(TodoService $todoService, TodoRepository $todoRepository):JsonResponse {
        try {
            return $todoService->getAll($todoRepository);
        }
        catch(\Exception $e) {
            return $this->json($e->getMessage(), 404);
        }
    }

    /**
     * @Route("/todo/getArchive", name="archive_todos")
     */
    public function getArchive(TodoService $todoService, TodoRepository $todoRepository):JsonResponse {
        try {
            return $todoService->getArchive($todoRepository);
        }
        catch(\Exception $e) {
            return $this->json($e->getMessage(), 404);
        }
    }

    /**
     * @Route("/todo/getActive", name="active_todos")
     */
    public function getActive(TodoService $todoService, TodoRepository $todoRepository):JsonResponse {
        try {
            return $todoService->getActive($todoRepository);
        }
        catch(\Exception $e) {
            return $this->json($e->getMessage(), 404);
        }
    }

    /**
     * @Route("/todo/changeExecuted", name="todo_executed")
     */
    public function changeExecuted(TodoService $todoService, TodoRepository $todoRepository, Request $request):JsonResponse {
        try {
            return $todoService->changeExecuted($todoRepository, $request);
        }
        catch(\Exception $e) {
            return $this->json($e->getMessage(), 404);
        }
    }

    /**
     * @Route("/todo/delete", name="todo_delete")
     */
    public function delete(TodoService $todoService, TodoRepository $todoRepository, Request $request):JsonResponse {
        try {
            return $todoService->delete($todoRepository, $request);
        }
        catch(\Exception $e) {
            return $this->json($e->getMessage(), 404);
        }
    }
}
