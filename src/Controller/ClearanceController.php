<?php

namespace App\Controller;

use App\Entity\Clearance;
use App\Form\ClearanceType;
use App\Helper\UserHelper;
use App\Repository\ClearanceDepartmentRepository;
use App\Repository\ClearanceRepository;
use App\Repository\ClearanceStatusRepository;
use App\Repository\ReasonRepository;
use App\Repository\StudentProfileRepository;
use App\Repository\UserInfoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/clearance')]
class ClearanceController extends AbstractController
{
    use BaseControllerTrait;
    #[Route('/', name: 'app_clearance_index', methods: ['GET',"POSt"])]
    public function index(UserHelper $userHelper,ClearanceDepartmentRepository $clearanceDepartmentRepository,StudentProfileRepository $studentProfileRepository,Request $request,ReasonRepository $reasonRepository,ClearanceRepository $clearanceRepository,UserInfoRepository $userInfoRepository,ClearanceStatusRepository $clearanceStatusRepository): Response
    {
        // if( UserHelper::getAuth($this->getUser(),"Student"))
        // $this->denyAccessUnlessGranted("denied");

        $news=$reasonRepository->getActive();
        $user=$userInfoRepository->findOneBy(['user'=>$this->getUser()]);
        $clearance=$clearanceRepository->findBy(['reason'=>$news]);
        $department=$user->getDepartment();
        $clrDep=$clearanceDepartmentRepository->findOneBy(['department'=>$department]);
        if(!$clrDep && !UserHelper::getAuth($this->getUser(),"Department Head"))
        $this->denyAccessUnlessGranted("denied");
        if($clrDep && !UserHelper::getAuth($this->getUser(),$clrDep->getRole()))
        $this->denyAccessUnlessGranted("denied");

        $clearancestat=$clearanceStatusRepository->findBy(['clearance'=>$clearance,'department'=>$department]);
       if($request->request->get('approve')){
        $student=$userInfoRepository->find($request->request->get('approve'));
       $studentprof=$studentProfileRepository->findBy(['department'=>$department,'student'=>$student,'solved'=>false]);
       if($studentprof){
        $this->addFlash('danger',"sorry! there is issue related to this student");
        return $this->redirectToRoute('app_clearance_index', [], Response::HTTP_SEE_OTHER);

       }
       $appvcla=$clearanceStatusRepository->find($request->request->get('clearance'));
       $appvcla->setStatus(1);
       $clearanceStatusRepository->save($appvcla, true);
       $this->addFlash('success',"successfuly approved");
       return $this->redirectToRoute('app_clearance_index', [], Response::HTTP_SEE_OTHER);

       }
        return $this->render('clearance/index.html.twig', [
            'clearances' => $clearancestat,

        ]);
    }
    #[Route('-list', name: 'app_clearance_list', methods: ['GET',"POSt"])]
    public function list(UserHelper $userHelper,ClearanceDepartmentRepository $clearanceDepartmentRepository,StudentProfileRepository $studentProfileRepository,Request $request,ReasonRepository $reasonRepository,ClearanceRepository $clearanceRepository,UserInfoRepository $userInfoRepository,ClearanceStatusRepository $clearanceStatusRepository): Response
    {
        if(!UserHelper::getAuth($this->getUser(),"System Admin"))
        $this->denyAccessUnlessGranted("denied");

      $clearances=$clearanceRepository->findAll();

       
        return $this->render('clearance/list.html.twig', [
            'clearances' => $clearances,

        ]);
    }

    #[Route('/new', name: 'app_clearance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClearanceRepository $clearanceRepository): Response
    {
        $clearance = new Clearance();
        $form = $this->createForm(ClearanceType::class, $clearance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clearanceRepository->save($clearance, true);

            return $this->redirectToRoute('app_clearance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clearance/new.html.twig', [
            'clearance' => $clearance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clearance_show', methods: ['GET'])]
    public function show(Clearance $clearance): Response
    {
        return $this->render('clearance/show.html.twig', [
            'clearance' => $clearance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_clearance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Clearance $clearance, ClearanceRepository $clearanceRepository): Response
    {
        $form = $this->createForm(ClearanceType::class, $clearance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clearanceRepository->save($clearance, true);

            return $this->redirectToRoute('app_clearance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clearance/edit.html.twig', [
            'clearance' => $clearance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clearance_delete', methods: ['POST'])]
    public function delete(Request $request, Clearance $clearance, ClearanceRepository $clearanceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clearance->getId(), $request->request->get('_token'))) {
            $clearanceRepository->remove($clearance, true);
        }

        return $this->redirectToRoute('app_clearance_index', [], Response::HTTP_SEE_OTHER);
    }
}
