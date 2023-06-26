<?php

namespace App\Http\__CAPITALIZED_AREA__\Controller\__ENTITY__;

use App\Domain\__DOMAIN__\Entity\__ENTITY__;
use App\Http\__CAPITALIZED_AREA__\Form\__ENTITY__\__ENTITY__FormType;
use App\Http\__CAPITALIZED_AREA__\Voter\__ENTITY__Voter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '__ROUTE_PATH__/ajouter', name: '__PREFIX___add')]
#[IsGranted(__ENTITY__Voter::ADD)]
class Add__ENTITY__Controller extends AbstractController
{
    public function __invoke(Request $request,EntityManagerInterface $entityManager): Response {
        $__ENTITYCAMEL__ = (new __ENTITY__());

        $form = $this->createForm(__ENTITY__FormType::class, $__ENTITYCAMEL__);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($__ENTITYCAMEL__);
            $entityManager->flush();

            $this->addFlash('success', 'Enregistré.');

            return $this->redirectToRoute('__LOWER_AREA_____PREFIX___list');
        }

        return $this->render('areas/__LOWER_AREA__/__PREFIX__/add.html.twig', [
            'form' => $form,
        ]);
    }
}
