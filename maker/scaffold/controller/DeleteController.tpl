<?php

namespace App\Http\##AREA##\Controller\##ENTITY##;

use App\Domain\##DOMAIN##\Entity\##ENTITY##;
use App\Http\##AREA##\Voter\##ENTITY##Voter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '__ROUTE_PATH__/supprimer', name: '##PREFIX##_delete')]
#[IsGranted(##ENTITY##Voter::DELETE, '##ENTITYCAMEL##')]
class Delete##ENTITY##Controller extends AbstractController
{
    public function __invoke(##ENTITY## $##ENTITYCAMEL##,EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($##ENTITYCAMEL##);
        $entityManager->flush();

        $this->addFlash('success', 'Supprimé.');

        return $this->redirectToRoute('##AREALOWER##_##PREFIX##_list');
    }
}
