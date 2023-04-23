<?php

namespace App\Controller;

use ApiPlatform\Elasticsearch\Exception\IndexNotFoundException;
use ApiPlatform\OpenApi\Model\Response;
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
    public function __constructor(TodoRepository $todoRepository, EntityManagerInterface $entityManager)
    {
        $this->todoRepository = $todoRepository;
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/todo/add", name="todo_add")
     */
    public function addTodo(TodoRepository $todoRepository, Request $request):JsonResponse
    {
        try {
            //$text = $request->request->get("text");
            $parameters = json_decode($request->getContent(), true);
            $text = $parameters['text'];
            //$text = $request->query->get("text");
            $todo = new Todo($text, false);
            $todoRepository->add($todo, true);
            return $this->json($todo);
        }
        catch (Exception $ex)
        {
            return $this->json($ex);
        }
    }

    /**
     * @Route("/todo/getAll", name="todos_all")
     */
    public function getAll(TodoRepository $todoRepository):JsonResponse
    {
        return $this->json($todoRepository->getAll());
    }

    /**
     * @Route("/todo/getArchive", name="archive_todos")
     */
    public function getArchive(TodoRepository $todoRepository):JsonResponse
    {
        try {
            $qb = $todoRepository->createQueryBuilder('t')
                ->where('t.executed = TRUE');
            $result = $todoRepository->findByQueryBuilder($qb);

            return $this->json($result);
        }
        catch (Exception $ex)
        {
            return $this->json($ex);
        }
    }

    /**
     * @Route("/todo/getActive", name="active_todos")
     */
    public function getActive(TodoRepository $todoRepository):JsonResponse
    {
        try {
            $qb = $todoRepository->createQueryBuilder('t')
                ->where('t.executed = FALSE');
            $result = $todoRepository->findByQueryBuilder($qb);

            return $this->json($result);
        }
        catch (Exception $ex)
        {
            return $this->json($ex);
        }
    }

    /**
     * @Route("/todo/changeExecuted", name="todo_executed")
     * @throws IndexNotFoundException
     */
    public function changeExecuted(TodoRepository $todoRepository, Request $request):JsonResponse
    {
        try {
            $parameters = json_decode($request->getContent(), true);
            $id = $parameters['id'];
            //$id = $request->query->get("id");
            $todo = $todoRepository->findById($id);
            if (is_null($todo)) {
                throw new IndexNotFoundException("Todo с id = " . $id . " не найден");
            }
            $todo->setExecuted(!$todo->isExecuted());
            $todoRepository->commitChanges();
            return $this->json($todo);
        }
        catch(\Exception $e){
            $errorMessage = $e->getMessage();
        }
    }

    /**
     * @Route("/todo/delete", name="todo_delete")
     * @throws IndexNotFoundException
     */
    public function delete(TodoRepository $todoRepository, Request $request):JsonResponse
    {
        try {
            $parameters = json_decode($request->getContent(), true);
            $id = $parameters['id'];
            //$id = $request->query->get("id");
            $todo = $todoRepository->findById($id);
            $todoReturned = clone $todo;
            if (is_null($todo)) {
                throw new IndexNotFoundException("Todo с id = " . $id . " не найден");
            }
            $todoRepository->remove($todo, true);
            return $this->json($todoReturned);
        }
        catch (\Exception $ex)
        {
            return $this->json($ex);
        }
    }
}
