<?php

namespace App\Controller;

use App\Form\TodoType;
use App\Todo\Application\TodoService;
use App\Todo\Domain\Todo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    private TodoService $todoService;

    public function __construct(TodoService $todoService)
    {
        $this->todoService = $todoService;
    }

    #[Route('/todo', name: 'todo_index', methods: ['GET'])]
    public function index(): Response
    {
        $todos = $this->todoService->getAllTodos();

        return $this->render('todo/index.html.twig', [
            'todos' => $todos,
        ]);
    }

    #[Route('/todo/new', name: 'todo_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $todo = new Todo('');
        $form = $this->createForm(TodoType::class, $todo);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $titulo = $form->get('titulo')->getData();
            $completada = $form->get('completada')->getData();

            $this->todoService->createTodo($titulo, $completada);

            return $this->redirectToRoute('todo_index');
        }

        return $this->render('todo/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/todo/{id}', name: 'todo_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $todo = $this->todoService->getTodoById($id);

        if (!$todo) {
            throw $this->createNotFoundException('Tarea no encontrada.');
        }

        return $this->render('todo/show.html.twig', [
            'todo' => $todo,
        ]);
    }

    #[Route('/todo/{id}/edit', name: 'todo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id): Response
    {
        $todo = $this->todoService->getTodoById($id);

        if (!$todo) {
            throw $this->createNotFoundException('Tarea no encontrada.');
        }

        $form = $this->createForm(TodoType::class, $todo);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->todoService->updateTodo($todo);

            return $this->redirectToRoute('todo_index');
        }

        return $this->render('todo/edit.html.twig', [
            'form' => $form->createView(),
            'todo' => $todo,
        ]);
    }

    #[Route('/todo/{id}', name: 'todo_delete', methods: ['DELETE'])]
    public function delete(Request $request, int $id): Response
    {
        $todo = $this->todoService->getTodoById($id);

        if (!$todo) {
            throw $this->createNotFoundException('Tarea no encontrada.');
        }

        if ($this->isCsrfTokenValid('delete' . $todo->getId(), $request->request->get('_token'))) {
            $this->todoService->deleteTodo($todo);
        }

        return $this->redirectToRoute('todo_index');
    }

    #[Route('/todo/{id}/complete', name: 'todo_complete', methods: ['POST'])]
    public function complete(int $id): Response
    {
        $todo = $this->todoService->getTodoById($id);

        if (!$todo) {
            throw $this->createNotFoundException('Tarea no encontrada.');
        }

        $this->todoService->completeTodo($todo);

        return $this->redirectToRoute('todo_index');
    }
}