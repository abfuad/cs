<?php

namespace App\Controller;

use App\Entity\Reason;
use App\Form\ReasonType;
use App\Repository\ReasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reason')]
class ReasonController extends AbstractController
{
    #[Route('/', name: 'app_reason_index', methods: ['GET'])]
    public function index(ReasonRepository $reasonRepository): Response
    {
        return $this->render('reason/index.html.twig', [
            'reasons' => $reasonRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reason_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReasonRepository $reasonRepository): Response
    {
        $reason = new Reason();
        $form = $this->createForm(ReasonType::class, $reason);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reasonRepository->save($reason, true);

            return $this->redirectToRoute('app_reason_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reason/new.html.twig', [
            'reason' => $reason,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reason_show', methods: ['GET'])]
    public function show(Reason $reason): Response
    {
        return $this->render('reason/show.html.twig', [
            'reason' => $reason,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reason_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reason $reason, ReasonRepository $reasonRepository): Response
    {
        $form = $this->createForm(ReasonType::class, $reason);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reasonRepository->save($reason, true);

            return $this->redirectToRoute('app_reason_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reason/edit.html.twig', [
            'reason' => $reason,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reason_delete', methods: ['POST'])]
    public function delete(Request $request, Reason $reason, ReasonRepository $reasonRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reason->getId(), $request->request->get('_token'))) {
            $reasonRepository->remove($reason, true);
        }

        return $this->redirectToRoute('app_reason_index', [], Response::HTTP_SEE_OTHER);
    }
}
