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

#[Route(path: '__ROUTE_PATH__/modifier', name: '__PREFIX___edit')]
#[IsGranted(__ENTITY__Voter::EDIT, '__ENTITYCAMEL__')]
class Edit__ENTITY__Controller extends AbstractController
{
    public function __invoke(
        __ENTITY__ $__ENTITYCAMEL__,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(__ENTITY__FormType::class, $__ENTITYCAMEL__);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Modifié.');

            return $this->redirectToRoute('__LOWER_AREA_____PREFIX___list');
        }

        return $this->render('areas/__LOWER_AREA__/__PREFIX__/edit.html.twig', [
            'form' => $form,
            '__ENTITYCAMEL__' => $__ENTITYCAMEL__
        ]);
    }
}
