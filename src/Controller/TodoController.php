<?php

namespace App\Controller;

use ApiPlatform\Elasticsearch\Exception\IndexNotFoundException;
use ApiPlatform\Exception\InvalidArgumentException;
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
            $parameters = json_decode($request->getContent(), true);
            $text = $parameters['text'];
            if (is_null($text))
                throw new InvalidArgumentException("Отсутствует параметр text в запросе");
            //Можно не делать данную обработку - т.к. Doctrine вызовет Exception при попытке сохранить информацию в БД
            if (strlen($text) > 1000)
                throw new InvalidArgumentException("Длина текста превышает 1000 симоволов");
            $todo = new Todo($text, false);
            return $this->json($todoService->addTodo($todoRepository, $todo));
        }
        catch (InvalidArgumentException $ex) {
            return $this->json($ex->getMessage(), 400);
        }
        catch(\Exception $e) {
            return $this->json($e->getMessage(), 500);
        }
    }

    /**
     * @Route("/todo/getAll", name="todos_all")
     */
    public function getAll(TodoService $todoService, TodoRepository $todoRepository):JsonResponse {
        try {
            return $this->json($todoService->getAll($todoRepository));
        }
        catch(\Exception $e) {
            return $this->json($e->getMessage(), 500);
        }
    }

    /**
     * @Route("/todo/getArchive", name="archive_todos")
     */
    public function getArchive(TodoService $todoService, TodoRepository $todoRepository):JsonResponse {
        try {
            return $this->json($todoService->getArchive($todoRepository));
        }
        catch(\Exception $e) {
            return $this->json($e->getMessage(), 500);
        }
    }

    /**
     * @Route("/todo/getActive", name="active_todos")
     */
    public function getActive(TodoService $todoService, TodoRepository $todoRepository):JsonResponse {
        try {
            return $this->json($todoService->getActive($todoRepository));
        }
        catch(\Exception $e) {
            return $this->json($e->getMessage(), 500);
        }
    }

    /**
     * @Route("/todo/changeExecuted", name="todo_executed")
     */
    public function changeExecuted(TodoService $todoService, TodoRepository $todoRepository, Request $request):JsonResponse {
        try {
            $parameters = json_decode($request->getContent(), true);
            $id = $parameters['id'];
            if (!is_numeric($id))
                throw new InvalidArgumentException("Не корректный параметр id в запросе");
            return $this->json($todoService->changeExecuted($todoRepository, $id));
        }
        catch (InvalidArgumentException $ex) {
            return $this->json($ex->getMessage(), 400);
        }
        catch (IndexNotFoundException $ex) {
            return $this->json($ex->getMessage(), 404);
        }
        catch(\Exception $e) {
            return $this->json($e->getMessage(), 500);
        }
    }

    /**
     * @Route("/todo/delete", name="todo_delete")
     */
    public function delete(TodoService $todoService, TodoRepository $todoRepository, Request $request):JsonResponse {
        try {
            $parameters = json_decode($request->getContent(), true);
            $id = $parameters['id'];
            if (!is_numeric($id))
                throw new InvalidArgumentException("Не корректный параметр id в запросе");
            return $this->json($todoService->delete($todoRepository, $id));
        }catch (InvalidArgumentException $ex) {
            return $this->json($ex->getMessage(), 400);
        }
        catch (IndexNotFoundException $ex) {
            return $this->json($ex->getMessage(), 404);
        }
        catch(\Exception $e) {
            return $this->json($e->getMessage(), 500);
        }
    }
}