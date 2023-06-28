<?php

namespace App\Http\##AREA##\Controller\##ENTITY##;

use App\Domain\##DOMAIN##\Repository\##ENTITY##Repository;
use App\Http\##AREA##\Voter\##ENTITY##Voter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '__ROUTE_PATH__s/exporter', name: '##PREFIX##_export')]
#[IsGranted(##ENTITY##Voter::EXPORT)]
class Export##ENTITY##Controller extends AbstractController
{
    public function __invoke(
        Request $request,
        ##ENTITY##Repository $repository,
        PaginatorInterface $paginator,
    ): Response {
      // TODO: Implements
      // TODO: Gérer le querySearch
    }
}
