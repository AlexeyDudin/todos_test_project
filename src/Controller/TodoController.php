<?php

namespace App\Controller;

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
//            $text = $request->request->get("text");
            $text = $request->query->get("text");
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
     * @Route("/todo/getAll", name="getAllTodos")
     */
    public function getAll(TodoRepository $todoRepository):JsonResponse
    {
        return $this->json($todoRepository->findAll());
    }

    /**
     * @Route("/todo/getArchive", name="getArchiveTodos")
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
}
