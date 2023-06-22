<?php

namespace App\Twig\Extension;

use App\Entity\ClearanceDepartment;
use App\Entity\UserInfo;
use App\Twig\Runtime\AppExtensionRuntime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Component\HttpFoundation\RequestStack;

class AppExtension extends AbstractExtension
{
    private $entityManager;
    private $utils;
    private $security;
    private RequestStack $requestStack;

    public function __construct(EntityManagerInterface $entityManager,private \Twig\Environment $templating,Security $security,  RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;

        $this->security=$security;
        $this->requestStack=$requestStack;
      
        
    }
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [AppExtensionRuntime::class, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getAuth', [$this, 'getAuth']),
            new TwigFunction('authClearance', [$this, 'authClearance']),



        ];
    }

public function getAuth($user,$role){
    $hasAccess = in_array($role, $user->getRoles())  ;
       return $hasAccess;


    

}
public function authClearance($user){
    $userinfo=$this->entityManager->getRepository(UserInfo::class)->findOneBy(['user'=>$user]);
   $department=$userinfo->getDepartment();
   $clrdep=$this->entityManager->getRepository(ClearanceDepartment::class)->findOneBy(['department'=>$department]);
  if(!$clrdep && !$this->getAuth($user,"Department Head"))
  return false;
   return true;

    

}
}
