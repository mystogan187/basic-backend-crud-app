<?php

namespace App\Audit\Domain;

interface AuditRepositoryInterface
{
    public function findAll(): array;
    public function findByTodoId(int $todoId): array;
    public function save(Audit $audit): void;
}