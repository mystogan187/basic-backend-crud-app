<?php

namespace App\Audit\Application;

use App\Audit\Domain\Audit;
use App\Audit\Domain\AuditRepositoryInterface;

class AuditService
{
    private AuditRepositoryInterface $auditRepository;

    public function __construct(AuditRepositoryInterface $auditRepository)
    {
        $this->auditRepository = $auditRepository;
    }

    public function logAction(int $todoId, string $accion, string $descripcion): void
    {
        $audit = new Audit($todoId, $accion, $descripcion);
        $this->auditRepository->save($audit);
    }

    public function getAllAudits(): array
    {
        return $this->auditRepository->findAll();
    }

    public function getAuditsByTodoId(int $todoId): array
    {
        return $this->auditRepository->findByTodoId($todoId);
    }
}