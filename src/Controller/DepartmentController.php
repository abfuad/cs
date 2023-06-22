<?php

namespace App\Controller;

use App\Entity\Department;
use App\Form\DepartmentType;
use App\Repository\DepartmentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/department')]
class DepartmentController extends AbstractController
{
   
    use BaseControllerTrait;
    #[Route('/', name: 'app_department_index', methods: ['GET','POST'])]
    public function index(DepartmentRepository $departmentRepository,Request $request, PaginatorInterface $paginator): Response
    {
        
        // $this->denyAccessUnlessGranted('vw_ds');
            if($request->request->get('edit')){
              

                $id=$request->request->get('edit');
                $department=$departmentRepository->findOneBy(['id'=>$id]);
                $form = $this->createForm(DepartmentType::class, $department);
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid()) {
                    // $this->denyAccessUnlessGranted('edt_ds');
                   
                    $this->em->flush();
                    $this->addFlash('success', "Updated Successfuly");

        
                    return $this->redirectToRoute('app_department_index');
                }
                $queryBuilder=$departmentRepository->findAll();
                $data=$paginator->paginate(
                    $queryBuilder,
                    $request->query->getInt('page',1),
                    18
                );
                return $this->render('department/index.html.twig', [
                    'datas' => $data,
                    'form' => $form,
                    'edit'=>$id,
                    'entity'=>'department'
                ]);
    
            }
            $department = new Department();
            $form = $this->createForm(DepartmentType::class, $department);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                //  $this->denyAccessUnlessGranted('ad_ds');

                
               $this->em->persist($department);
               $this->em->flush();
               $this->addFlash('success', "Registered Successfuly");

    
                return $this->redirectToRoute('app_department_index');
            }
            $queryBuilder=$departmentRepository->findAll();
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                18
            );
            return $this->render('department/index.html.twig', [
                'datas' => $data,
                'form' => $form,
                'edit'=>false,
                'entity'=>'department'
            ]);
        
       
    }
  

    #[Route('/{id}', name: 'app_department_show', methods: ['GET'])]
    public function show(Department $department): Response
    {
        return $this->render('department/show.html.twig', [
            'department' => $department,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_department_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Department $department, DepartmentRepository $departmentRepository): Response
    {
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $departmentRepository->save($department, true);

            return $this->redirectToRoute('app_department_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('department/edit.html.twig', [
            'department' => $department,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_department_delete', methods: ['POST'])]
    public function delete(Request $request, Department $department, DepartmentRepository $departmentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$department->getId(), $request->request->get('_token'))) {
            $departmentRepository->remove($department, true);
        }

        return $this->redirectToRoute('app_department_index', [], Response::HTTP_SEE_OTHER);
    }
}
