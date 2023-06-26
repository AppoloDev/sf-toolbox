<?php

namespace App\Http\__CAPITALIZED_AREA__\Controller\__ENTITY__;

use App\Domain\__DOMAIN__\Repository\__ENTITY__Repository;
use App\Http\__CAPITALIZED_AREA__\Voter\__ENTITY__Voter;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '__ROUTE_PATH__s', name: '__PREFIX___list')]
#[IsGranted(__ENTITY__Voter::LIST)]
class List__ENTITY__Controller extends AbstractController
{
    public function __invoke(
        Request $request,
        __ENTITY__Repository $repository,
        PaginatorInterface $paginator,
    ): Response {
        $pagination = $paginator->paginate(
            $repository
            ->getQB()
            //->querySearch((string) $request->query->get('q'))
            ->order('updatedAt', 'DESC')
            ->getBuilder(),
            $request->query->getInt('page', 1),
            12,
            [
                'defaultSortFieldName' => '__ALIAS__.updatedAt',
                'defaultSortDirection' => 'desc',
            ]
        );

        return $this->render('areas/__LOWER_AREA__/__PREFIX__/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
