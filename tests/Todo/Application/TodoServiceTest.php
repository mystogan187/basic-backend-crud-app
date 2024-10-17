<?php

namespace App\Tests\Todo\Application;

use App\Todo\Application\TodoService;
use App\Todo\Domain\Todo;
use App\Todo\Domain\TodoRepositoryInterface;
use App\Audit\Application\AuditService;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class TodoServiceTest extends TestCase
{
    /** @var TodoRepositoryInterface|MockObject */
    private $todoRepository;

    /** @var AuditService|MockObject */
    private $auditService;

    /** @var TodoService */
    private $todoService;

    protected function setUp(): void
    {
        $this->todoRepository = $this->createMock(TodoRepositoryInterface::class);
        $this->auditService = $this->createMock(AuditService::class);
        $this->todoService = new TodoService($this->todoRepository, $this->auditService);
    }

    public function testCreateTodo(): void
    {
        $titulo = 'Nueva Tarea';
        $completada = false;

        $this->todoRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (Todo $todo) {
                $todo->setId(1);
            });

        $this->auditService->expects($this->once())
            ->method('logAction')
            ->with(
                1,
                'crear',
                $this->stringContains('Creada la tarea 1 con título "Nueva Tarea"')
            );

        $todo = $this->todoService->createTodo($titulo, $completada);

        $this->assertInstanceOf(Todo::class, $todo);
        $this->assertEquals($titulo, $todo->getTitulo());
        $this->assertEquals($completada, $todo->isCompletada());
        $this->assertEquals(1, $todo->getId());
    }

    public function testUpdateTodoTituloCambiado(): void
    {
        $todo = $this->createMock(Todo::class);
        $todo->method('getId')->willReturn(2);

        $todo->expects($this->exactly(2))
            ->method('getTitulo')
            ->willReturnOnConsecutiveCalls('Título Original', 'Título Modificado');

        $todo->method('isCompletada')->willReturn(false);

        $this->todoRepository->expects($this->once())
            ->method('save')
            ->with($todo);

        $this->auditService->expects($this->once())
            ->method('logAction')
            ->with(
                2,
                'editar',
                $this->stringContains('Cambiado el título de la tarea 2 a "Título Modificado"')
            );

        $this->todoService->updateTodo($todo);
    }

    public function testUpdateTodoCompletadaCambiada(): void
    {
        $todo = $this->createMock(Todo::class);
        $todo->method('getId')->willReturn(3);

        $todo->method('getTitulo')->willReturn('Tarea');

        $todo->expects($this->exactly(2))
            ->method('isCompletada')
            ->willReturnOnConsecutiveCalls(false, true);

        $this->todoRepository->expects($this->once())
            ->method('save')
            ->with($todo);

        $this->auditService->expects($this->once())
            ->method('logAction')
            ->with(
                3,
                'completar',
                $this->stringContains('Completada la tarea 3 a las')
            );

        $this->todoService->updateTodo($todo);
    }

    public function testDeleteTodo(): void
    {
        $todo = new Todo('Tarea a Eliminar', false);
        $todo->setId(4);

        $this->todoRepository->expects($this->once())
            ->method('delete')
            ->with($todo);

        $this->auditService->expects($this->once())
            ->method('logAction')
            ->with(
                4,
                'borrar',
                $this->stringContains('Borrada la tarea 4 a las')
            );

        $this->todoService->deleteTodo($todo);
    }

    public function testCompleteTodo(): void
    {
        $todo = new Todo('Tarea a Completar', false);
        $todo->setId(5);

        $this->todoRepository->expects($this->once())
            ->method('save')
            ->with($todo);

        $this->auditService->expects($this->once())
            ->method('logAction')
            ->with(
                5,
                'completar',
                $this->stringContains('Completada la tarea 5 a las')
            );

        $this->todoService->completeTodo($todo);

        $this->assertTrue($todo->isCompletada());
    }

    public function testGetAllTodos(): void
    {
        $todo1 = new Todo('Tarea 1', false);
        $todo1->setId(6);

        $todo2 = new Todo('Tarea 2', true);
        $todo2->setId(7);

        $todos = [$todo1, $todo2];

        $this->todoRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($todos);

        $result = $this->todoService->getAllTodos();

        $this->assertEquals($todos, $result);
    }

    public function testGetTodoById(): void
    {
        $todo = new Todo('Tarea específica', false);
        $todo->setId(8);

        $this->todoRepository->expects($this->once())
            ->method('findById')
            ->with(8)
            ->willReturn($todo);

        $result = $this->todoService->getTodoById(8);

        $this->assertEquals($todo, $result);
    }
}
