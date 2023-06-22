<?php

namespace App\Controller;

use App\Entity\StudentProfile;
use App\Form\StudentProfileType;
use App\Repository\StudentProfileRepository;
use App\Repository\UserInfoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/student/profile')]
class StudentProfileController extends AbstractController
{
    #[Route('/', name: 'app_student_profile_index', methods: ['GET'])]
    public function index(StudentProfileRepository $studentProfileRepository): Response
    {
        return $this->render('student_profile/index.html.twig', [
            'student_profiles' => $studentProfileRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_student_profile_new', methods: ['GET', 'POST'])]
    public function new(UserInfoRepository $userInfoRepository,Request $request, StudentProfileRepository $studentProfileRepository): Response
    {
        $user=$userInfoRepository->findOneBy(['user'=>$this->getUser()]);
        $department=$user->getDepartment();
        $studentProfile = new StudentProfile();
        $form = $this->createForm(StudentProfileType::class, $studentProfile,[

        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $studentProfile->setDepartment($department);
            $studentProfile->setSolved(false);
            $studentProfileRepository->save($studentProfile, true);

            return $this->redirectToRoute('app_student_profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('student_profile/new.html.twig', [
            'student_profile' => $studentProfile,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_student_profile_show', methods: ['GET'])]
    public function show(StudentProfile $studentProfile): Response
    {
        return $this->render('student_profile/show.html.twig', [
            'student_profile' => $studentProfile,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_student_profile_edit', methods: ['GET', 'POST'])]
    public function edit(UserInfoRepository $userInfoRepository,Request $request, StudentProfile $studentProfile, StudentProfileRepository $studentProfileRepository): Response
    {
        $user=$userInfoRepository->findOneBy(['user'=>$this->getUser()]);
        $department=$user->getDepartment();
        $form = $this->createForm(StudentProfileType::class, $studentProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $studentProfile->setDepartment($department);

            $studentProfileRepository->save($studentProfile, true);

            return $this->redirectToRoute('app_student_profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student_profile/edit.html.twig', [
            'student_profile' => $studentProfile,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_student_profile_delete', methods: ['POST'])]
    public function delete(Request $request, StudentProfile $studentProfile, StudentProfileRepository $studentProfileRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$studentProfile->getId(), $request->request->get('_token'))) {
            $studentProfileRepository->remove($studentProfile, true);
        }

        return $this->redirectToRoute('app_student_profile_index', [], Response::HTTP_SEE_OTHER);
    }
}
