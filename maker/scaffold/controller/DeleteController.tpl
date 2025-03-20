<?php

namespace App\Http\##AREA##\Controller\##ENTITY##;

use App\Domain\##DOMAIN##\Entity\##ENTITY##;
use App\Http\##AREA##\Voter\##ENTITY##Voter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '##ROUTEPATH##/{id}/supprimer', name: '##PREFIX##_delete')]
#[IsGranted(##ENTITY##Voter::DELETE, '##ENTITYCAMEL##')]
class Delete##ENTITY##Controller extends AbstractController
{
    public function __invoke(
        ##ENTITY## $##ENTITYCAMEL##,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator
    ): Response
    {
        $entityManager->remove($##ENTITYCAMEL##);
        $entityManager->flush();

        $this->addFlash('success', $translator->trans('the_item_has_been_deleted'));

        return $this->redirectToRoute('##AREALOWER##_##PREFIX##_list');
    }
}
