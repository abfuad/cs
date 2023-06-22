<?php

namespace App\Controller;

use App\Entity\Clearance;
use App\Entity\ClearanceStatus;
use App\Helper\PrintHelper;
use App\Helper\UserHelper;
use App\Repository\ClearanceDepartmentRepository;
use App\Repository\ClearanceRepository;
use App\Repository\ClearanceStatusRepository;
use App\Repository\DepartmentRepository;
use App\Repository\ReasonRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    use BaseControllerTrait;

    #[Route('/student', name: 'app_student')]
    public function index(ReasonRepository $reasonRepository, Request $request, ClearanceRepository $clearanceRepository, UserHelper $userHelper, ClearanceStatusRepository $clearanceStatusRepository, ClearanceDepartmentRepository $clearanceDepartmentRepository): Response
    {
        $news = $reasonRepository->getActive();
        $order = $clearanceDepartmentRepository->findAll();
        $clearance = $clearanceRepository->findOneBy(['student' => $this->getUser()->getUserInfo(), 'reason' => $news]);

        if (!$this->getUser()->isIsStudent())
            $this->denyAccessUnlessGranted("denied");

        if ($request->request->get('apply')) {
            $reasonid = $request->request->get('reason');
            $reason = $reasonRepository->find($reasonid);
            $clearance = $clearanceRepository->findOneBy(['student' => $this->getUser()->getUserInfo(), 'reason' => $reason]);
            // dd($reason);
            if (!$clearance) {
                $clearance = new Clearance();
                $clearance->setStudent($this->getUser()->getUserInfo());
                $clearance->setReason($reason);
                $clearance->setCompleted(0);
                $this->em->persist($clearance);
                $this->em->flush();
                $clearancestatus = $clearanceStatusRepository->findOneBy(['department' => $clearance->getStudent()->getDepartment(), 'clearance' => $clearance]);
                if (!$clearancestatus) {
                    $clearancestatus = new ClearanceStatus();
                    $clearancestatus->setDepartment($clearance->getStudent()->getDepartment());
                    $clearancestatus->setStatus(0);
                    $clearancestatus->setClearance($clearance);
                    $this->em->persist($clearancestatus);
                    $this->em->flush();
                }
                foreach ($order as $ord) {
                    $clearancestatus = $clearanceStatusRepository->findOneBy(['department' => $ord->getDepartment(), 'clearance' => $clearance]);
                    if (!$clearancestatus) {
                        $clearancestatus = new ClearanceStatus();
                        $clearancestatus->setDepartment($ord->getDepartment());
                        $clearancestatus->setStatus(0);
                        $clearancestatus->setClearance($clearance);
                        $this->em->persist($clearancestatus);
                        $this->em->flush();
                    }
                }
                // dd($this->getUser()->getUserInfo());
                $clearance = $clearanceRepository->findOneBy(['student' => $this->getUser()->getUserInfo(), 'reason' => $reason]);
                return $this->render('student/index.html.twig', [

                    'new' => $news,
                    'clearance' => $clearance
                ]);
            }
        }
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
            'new' => $news,
            'clearance' => $clearance

        ]);
    }
    #[Route('/student-check', name: 'app_student_check')]
    public function studCheck(UserInfoRepository $userInfoRepository, ReasonRepository $reasonRepository, Request $request, ClearanceRepository $clearanceRepository, UserHelper $userHelper, ClearanceStatusRepository $clearanceStatusRepository, ClearanceDepartmentRepository $clearanceDepartmentRepository): Response
    {
        $news = $reasonRepository->getActive();
        $order = $clearanceDepartmentRepository->findAll();
        //  $clearance=$clearanceRepository->findOneBy(['student'=>$this->getUser()->getUserInfo(),'reason'=>$news]);

    // if (!UserHelper::getAuth($this->getUser(),"Gate Keeper"))
    //         $this->denyAccessUnlessGranted("denied");

        if ($request->request->get('name')) {
            $reasonid = $request->request->get('reason');
            $reason = $reasonRepository->find($reasonid);
            $student = $userInfoRepository->findOneBy(['idNumber' => $request->request->get('name')]);

            if (!$student) {
                $this->addFlash('danger', "sorry! No Such Id");
                return $this->redirectToRoute('app_student_check', [], Response::HTTP_SEE_OTHER);
            }
            // dd($this->getUser()->getUserInfo());
            $clearance = $clearanceRepository->findOneBy(['student' => $student, 'reason' => $reason]);
            return $this->render('student/stud_check.html.twig', [

                'new' => $news,
                'clearance' => $clearance
            ]);
        }


        return $this->render('student/stud_check.html.twig', [
            'controller_name' => 'StudentController',
            'new' => $news,
            'clearance' => null

        ]);
    }
    #[Route('/student-list', name: 'app_student_index', methods: ['GET', 'POST'])]
    public function studindex(DepartmentRepository $departmentRepository, UserHelper $userHelper, PrintHelper $printHelper, UserRepository $userRepository, UserInfoRepository $userInfoRepository,  Request $request, PaginatorInterface $paginator): Response
    {
        // $this->denyAccessUnlessGranted('vw_stdnt_lst');

        if ($request->request->get('cred')) {
            $this->denyAccessUnlessGranted('prnt_usr_pswrd');
            $user = $userInfoRepository->find($request->request->get('cred'));
            $password = UserHelper::getPassword();
            $user->getUser()->setPassword($userHelper->getHashedPassWord($user->getUser(), $password));
            $this->em->flush();
            $printHelper->print('user_info/print.html.twig', ["user" => $user, 'password' => $password], 'user credential', 'portrait', 'A5');
        }

        if (in_array('rlspad', $this->getUser()->getRoles()) || in_array('System Admin', $this->getUser()->getRoles())) {
            $students = $userInfoRepository->findUser([], true);
        } elseif (in_array('Department Head', $this->getUser()->getRoles()))

            $students  = $userInfoRepository->findUser(['department' => $this->getUser()->getUserInfo()->getDepartment()], true);
        else
            $this->denyAccessUnlessGranted('denied');




        $data = $paginator->paginate(
            $students,
            $request->query->getInt('page', 1),
            18
        );

        return $this->render('user_info/student_index.html.twig', [
            'users' => $data,

        ]);
    }
}
