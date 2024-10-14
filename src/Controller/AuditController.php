<?php

namespace App\Controller;

use App\Audit\Application\AuditService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuditController extends AbstractController
{
    private AuditService $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    #[Route('/audit', name: 'audit_index', methods: ['GET'])]
    public function index(): Response
    {
        $audits = $this->auditService->getAllAudits();

        return $this->render('audit/index.html.twig', [
            'audits' => $audits,
        ]);
    }

    #[Route('/audit/{todoId}', name: 'audit_show', methods: ['GET'])]
    public function show(int $todoId): Response
    {
        $audits = $this->auditService->getAuditsByTodoId($todoId);

        return $this->render('audit/show.html.twig', [
            'audits' => $audits,
            'todoId' => $todoId,
        ]);
    }
}