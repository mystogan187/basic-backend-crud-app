<?php

namespace App\Todo\Application;

use App\Todo\Domain\Todo;
use App\Todo\Domain\TodoRepositoryInterface;
use App\Audit\Application\AuditService;

class TodoService
{
    private TodoRepositoryInterface $todoRepository;
    private AuditService $auditService;

    public function __construct(TodoRepositoryInterface $todoRepository, AuditService $auditService)
    {
        $this->todoRepository = $todoRepository;
        $this->auditService = $auditService;
    }

    public function createTodo(string $titulo, bool $completada = false): Todo
    {
        $todo = new Todo($titulo, $completada);
        $this->todoRepository->save($todo);

        $this->auditService->logAction(
            $todo->getId(),
            'crear',
            sprintf('Creada la tarea %d con título "%s" a las %s', $todo->getId(), $todo->getTitulo(), $todo->getFechaCreacion()->format('H:i'))
        );

        return $todo;
    }


    public function updateTodo(Todo $todo): void
    {
        $tituloAnterior = $todo->getTitulo();
        $completadaAnterior = $todo->isCompletada();

        $nuevoTitulo = $todo->getTitulo();
        $nuevaCompletada = $todo->isCompletada();

        $this->todoRepository->save($todo);

        if ($tituloAnterior !== $nuevoTitulo) {
            $this->auditService->logAction(
                $todo->getId(),
                'editar',
                sprintf(
                    'Cambiado el título de la tarea %d a "%s" a las %s',
                    $todo->getId(),
                    $nuevoTitulo,
                    (new \DateTime())->format('H:i')
                )
            );
        }

        if ($completadaAnterior !== $nuevaCompletada) {
            if ($nuevaCompletada) {
                $this->auditService->logAction(
                    $todo->getId(),
                    'completar',
                    sprintf(
                        'Completada la tarea %d a las %s',
                        $todo->getId(),
                        (new \DateTime())->format('H:i')
                    )
                );
            } else {
                $this->auditService->logAction(
                    $todo->getId(),
                    'descompletar',
                    sprintf(
                        'Desmarcada la tarea %d como completada a las %s',
                        $todo->getId(),
                        (new \DateTime())->format('H:i')
                    )
                );
            }
        }
    }

    public function deleteTodo(Todo $todo): void
    {
        $todoId = $todo->getId();
        $this->todoRepository->delete($todo);

        $this->auditService->logAction(
            $todoId,
            'borrar',
            sprintf('Borrada la tarea %d a las %s', $todoId, (new \DateTime())->format('H:i'))
        );
    }

    public function completeTodo(Todo $todo): void
    {
        $todo->setCompletada(true);
        $this->todoRepository->save($todo);

        $this->auditService->logAction(
            $todo->getId(),
            'completar',
            sprintf('Completada la tarea %d a las %s', $todo->getId(), (new \DateTime())->format('H:i'))
        );
    }

    public function getAllTodos(): array
    {
        return $this->todoRepository->findAll();
    }

    public function getTodoById(int $id): ?Todo
    {
        return $this->todoRepository->findById($id);
    }
}