<?php

namespace App\Http\__CAPITALIZED_AREA__\Controller\__ENTITY__;

use App\Domain\__DOMAIN__\Entity\__ENTITY__;
use App\Http\__CAPITALIZED_AREA__\Voter\__ENTITY__Voter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '__ROUTE_PATH__/supprimer', name: '__PREFIX___delete')]
#[IsGranted(__ENTITY__Voter::DELETE, '__ENTITYCAMEL__')]
class Delete__ENTITY__Controller extends AbstractController
{
    public function __invoke(__ENTITY__ $__ENTITYCAMEL__,EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($__ENTITYCAMEL__);
        $entityManager->flush();

        $this->addFlash('success', 'Supprimé.');

        return $this->redirectToRoute('__LOWER_AREA_____PREFIX___list');
    }
}
