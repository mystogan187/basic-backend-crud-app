<?php

namespace App\Todo\Infrastructure\Repository;

use App\Todo\Domain\Todo;
use App\Todo\Domain\TodoRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class DoctrineTodoRepository implements TodoRepositoryInterface
{
    private EntityManagerInterface $entityManager;
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Todo::class);
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function findById(int $id): ?Todo
    {
        return $this->repository->find($id);
    }

    public function save(Todo $todo): void
    {
        $this->entityManager->persist($todo);
        $this->entityManager->flush();
    }

    public function delete(Todo $todo): void
    {
        $this->entityManager->remove($todo);
        $this->entityManager->flush();
    }
}