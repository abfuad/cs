<?php

namespace App\Controller;

use App\Entity\ClearanceOrder;
use App\Form\ClearanceOrderType;
use App\Repository\ClearanceOrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/clearance-order')]
class ClearanceOrderController extends AbstractController
{
    #[Route('/', name: 'app_clearance_order_index', methods: ['GET'])]
    public function index(ClearanceOrderRepository $clearanceOrderRepository): Response
    {
        return $this->render('clearance_order/index.html.twig', [
            'clearance_orders' => $clearanceOrderRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_clearance_order_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClearanceOrderRepository $clearanceOrderRepository): Response
    {
        $clearanceOrder = new ClearanceOrder();
        $form = $this->createForm(ClearanceOrderType::class, $clearanceOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clearanceOrderRepository->save($clearanceOrder, true);

            return $this->redirectToRoute('app_clearance_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clearance_order/new.html.twig', [
            'clearance_order' => $clearanceOrder,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clearance_order_show', methods: ['GET'])]
    public function show(ClearanceOrder $clearanceOrder): Response
    {
        return $this->render('clearance_order/show.html.twig', [
            'clearance_order' => $clearanceOrder,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_clearance_order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ClearanceOrder $clearanceOrder, ClearanceOrderRepository $clearanceOrderRepository): Response
    {
        $form = $this->createForm(ClearanceOrderType::class, $clearanceOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clearanceOrderRepository->save($clearanceOrder, true);

            return $this->redirectToRoute('app_clearance_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clearance_order/edit.html.twig', [
            'clearance_order' => $clearanceOrder,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clearance_order_delete', methods: ['POST'])]
    public function delete(Request $request, ClearanceOrder $clearanceOrder, ClearanceOrderRepository $clearanceOrderRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clearanceOrder->getId(), $request->request->get('_token'))) {
            $clearanceOrderRepository->remove($clearanceOrder, true);
        }

        return $this->redirectToRoute('app_clearance_order_index', [], Response::HTTP_SEE_OTHER);
    }
}
