<?php

namespace App\Tests\Audit\Application;


use App\Audit\Application\AuditService;
use App\Audit\Domain\Audit;
use App\Audit\Domain\AuditRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AuditServiceTest extends TestCase
{
    private AuditRepositoryInterface|MockObject $auditRepository;
    private AuditService $auditService;

    protected function setUp(): void
    {
        $this->auditRepository = $this->createMock(AuditRepositoryInterface::class);
        $this->auditService = new AuditService($this->auditRepository);
    }

    public function testLogAction(): void
    {
        $todoId = 1;
        $accion = 'crear';
        $descripcion = 'Descripción de la acción';

        $this->auditRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($audit) use ($todoId, $accion, $descripcion) {
                $this->assertInstanceOf(Audit::class, $audit);
                $this->assertEquals($todoId, $audit->getTodoId());
                $this->assertEquals($accion, $audit->getAccion());
                $this->assertEquals($descripcion, $audit->getDescripcion());
                return true;
            }));

        $this->auditService->logAction($todoId, $accion, $descripcion);
    }

    public function testGetAllAudits(): void
    {
        $audit1 = $this->createMock(Audit::class);
        $audit1->method('getId')->willReturn(1);
        $audit1->method('getTodoId')->willReturn(1);
        $audit1->method('getAccion')->willReturn('crear');
        $audit1->method('getDescripcion')->willReturn('Audit 1');

        $audit2 = $this->createMock(Audit::class);
        $audit2->method('getId')->willReturn(2);
        $audit2->method('getTodoId')->willReturn(2);
        $audit2->method('getAccion')->willReturn('editar');
        $audit2->method('getDescripcion')->willReturn('Audit 2');

        $audits = [$audit1, $audit2];

        $this->auditRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($audits);

        $result = $this->auditService->getAllAudits();

        $this->assertCount(2, $result);
        $this->assertEquals($audits, $result);
    }

    public function testGetAuditsByTodoId(): void
    {
        $todoId = 1;

        $audit1 = $this->createMock(Audit::class);
        $audit1->method('getId')->willReturn(3);
        $audit1->method('getTodoId')->willReturn($todoId);
        $audit1->method('getAccion')->willReturn('crear');
        $audit1->method('getDescripcion')->willReturn('Audit 1');

        $audit2 = $this->createMock(Audit::class);
        $audit2->method('getId')->willReturn(4);
        $audit2->method('getTodoId')->willReturn($todoId);
        $audit2->method('getAccion')->willReturn('editar');
        $audit2->method('getDescripcion')->willReturn('Audit 2');

        $audits = [$audit1, $audit2];

        $this->auditRepository->expects($this->once())
            ->method('findByTodoId')
            ->with($todoId)
            ->willReturn($audits);

        $result = $this->auditService->getAuditsByTodoId($todoId);

        $this->assertCount(2, $result);
        $this->assertEquals($audits, $result);
    }
}