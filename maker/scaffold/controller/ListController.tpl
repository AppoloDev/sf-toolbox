<?php

namespace App\Http\##AREA##\Controller\##ENTITY##;

use App\Domain\##DOMAIN##\Repository\##ENTITY##Repository;
use App\Http\##AREA##\Voter\##ENTITY##Voter;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '__ROUTE_PATH__s', name: '##PREFIX##_list')]
#[IsGranted(##ENTITY##Voter::LIST)]
class List##ENTITY##Controller extends AbstractController
{
    public function __invoke(
        Request $request,
        ##ENTITY##Repository $repository,
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
                'defaultSortFieldName' => '##ENTITYLOWER##.updatedAt',
                'defaultSortDirection' => 'desc',
            ]
        );

        return $this->render('areas/##AREALOWER##/##PREFIX##/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
