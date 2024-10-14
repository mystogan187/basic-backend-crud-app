<?php

namespace App\Audit\Infrastructure\Repository;

use App\Audit\Domain\Audit;
use App\Audit\Domain\AuditRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class DoctrineAuditRepository implements AuditRepositoryInterface
{
    private EntityManagerInterface $entityManager;
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Audit::class);
    }

    public function findAll(): array
    {
        return $this->repository->findBy([], ['fechaAccion' => 'ASC']);
    }

    public function findByTodoId(int $todoId): array
    {
        return $this->repository->findBy(['todoId' => $todoId], ['fechaAccion' => 'ASC']);
    }

    public function save(Audit $audit): void
    {
        $this->entityManager->persist($audit);
        $this->entityManager->flush();
    }
}