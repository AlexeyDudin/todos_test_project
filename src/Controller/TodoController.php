<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Repository\TodoRepository;
use Doctrine\DBAL\Driver\Exception;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class TodoController extends AbstractController
{
    /**
     * @Route("/todo", name="app_todo")
     */
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TodoController.php',
        ]);
    }
    /**
     * @Route("/todo/add", name="app_todo")
     */
    public function addTodo(Todo $todoe):JsonResponse
    {
        try {
            //TODO разобраться с подключением Repo!
            $todoRepo = new TodoRepository(new ManagerRegistry("Todo", env()));
            $todoRepo->add($todoe);
        }
        catch (Exception $ex)
        {
            return $this->json($ex);
        }
        return $this->json($todoe);
    }

    /**
     * @Route("/todo/getAll", name="app_todo")
     */
    public function getAll():JsonResponse
    {
        return $this->json($this->get);
    }
}
