<?php

namespace App\Controller;

use App\Entity\ClearanceDepartment;
use App\Form\ClearanceDepartmentType;
use App\Repository\ClearanceDepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/clearance-dep')]
class ClearanceDepartmentController extends AbstractController
{
    #[Route('/', name: 'app_clearance_department_index', methods: ['GET'])]
    public function index(ClearanceDepartmentRepository $clearanceDepartmentRepository): Response
    {
        return $this->render('clearance_department/index.html.twig', [
            'clearance_departments' => $clearanceDepartmentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_clearance_department_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClearanceDepartmentRepository $clearanceDepartmentRepository): Response
    {
        $clearanceDepartment = new ClearanceDepartment();
        $form = $this->createForm(ClearanceDepartmentType::class, $clearanceDepartment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clearanceDepartmentRepository->save($clearanceDepartment, true);

            return $this->redirectToRoute('app_clearance_department_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clearance_department/new.html.twig', [
            'clearance_department' => $clearanceDepartment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clearance_department_show', methods: ['GET'])]
    public function show(ClearanceDepartment $clearanceDepartment): Response
    {
        return $this->render('clearance_department/show.html.twig', [
            'clearance_department' => $clearanceDepartment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_clearance_department_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ClearanceDepartment $clearanceDepartment, ClearanceDepartmentRepository $clearanceDepartmentRepository): Response
    {
        $form = $this->createForm(ClearanceDepartmentType::class, $clearanceDepartment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clearanceDepartmentRepository->save($clearanceDepartment, true);

            return $this->redirectToRoute('app_clearance_department_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clearance_department/edit.html.twig', [
            'clearance_department' => $clearanceDepartment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clearance_department_delete', methods: ['POST'])]
    public function delete(Request $request, ClearanceDepartment $clearanceDepartment, ClearanceDepartmentRepository $clearanceDepartmentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clearanceDepartment->getId(), $request->request->get('_token'))) {
            $clearanceDepartmentRepository->remove($clearanceDepartment, true);
        }

        return $this->redirectToRoute('app_clearance_department_index', [], Response::HTTP_SEE_OTHER);
    }
}
