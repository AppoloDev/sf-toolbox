<?php

namespace App\Http\##AREA##\Controller\##ENTITY##;

use App\Domain\##DOMAIN##\Entity\##ENTITY##;
use App\Http\##AREA##\Form\##ENTITY##\##ENTITY##FormType;
use App\Http\##AREA##\Voter\##ENTITY##Voter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '##ROUTEPATH##/ajouter', name: '##PREFIX##_add')]
#[IsGranted(##ENTITY##Voter::ADD)]
class Add##ENTITY##Controller extends AbstractController
{
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator
    ): Response {
        $##ENTITYCAMEL## = (new ##ENTITY##());

        $form = $this->createForm(##ENTITY##FormType::class, $##ENTITYCAMEL##);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($##ENTITYCAMEL##);
            $entityManager->flush();

            $this->addFlash('success', $translator->trans('the_item_has_been_saved'));

            return $this->redirectToRoute('##AREALOWER##_##PREFIX##_list');
        }

        return $this->render('areas/##AREALOWER##/##PREFIX##/add.html.twig', [
            'form' => $form,
        ]);
    }
}
