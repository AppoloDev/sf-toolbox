<?php

namespace App\Http\__CAPITALIZED_AREA__\Controller\__ENTITY__;

use App\Domain\__DOMAIN__\Repository\__ENTITY__Repository;
use App\Http\__CAPITALIZED_AREA__\Voter\__ENTITY__Voter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '__ROUTE_PATH__s/exporter', name: '__PREFIX___export')]
#[IsGranted(__ENTITY__Voter::EXPORT)]
class Export__ENTITY__Controller extends AbstractController
{
    public function __invoke(
        Request $request,
        __ENTITY__Repository $repository,
        PaginatorInterface $paginator,
    ): Response {
      // TODO: Implements
      // TODO: Gérer le querySearch
    }
}
