<?php

namespace App\Http\##AREA##\Controller\##ENTITY##;

use App\Domain\##DOMAIN##\Entity\##ENTITY##;
use App\Http\##AREA##\Form\##ENTITY##\##ENTITY##FormType;
use App\Http\##AREA##\Voter\##ENTITY##Voter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '##ROUTEPATH##/modifier', name: '##PREFIX##_edit')]
#[IsGranted(##ENTITY##Voter::EDIT, '##ENTITYCAMEL##')]
class Edit##ENTITY##Controller extends AbstractController
{
    public function __invoke(
        ##ENTITY## $##ENTITYCAMEL##,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(##ENTITY##FormType::class, $##ENTITYCAMEL##);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Modifié.');

            return $this->redirectToRoute('##AREALOWER##_##PREFIX##_list');
        }

        return $this->render('areas/##AREALOWER##/##PREFIX##/edit.html.twig', [
            'form' => $form,
            '##ENTITYCAMEL##' => $##ENTITYCAMEL##
        ]);
    }
}
