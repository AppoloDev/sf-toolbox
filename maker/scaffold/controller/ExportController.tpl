<?php

namespace App\Http\##AREA##\Controller\##ENTITY##;

use App\Domain\##DOMAIN##\Entity\##ENTITY##;
use App\Domain\##DOMAIN##\Repository\##ENTITY##Repository;
use App\Http\##AREA##\Voter\##ENTITY##Voter;
use AppoloDev\SFToolboxBundle\Csv\CsvWriter;
use AppoloDev\SFToolboxBundle\Response\CsvFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '##ROUTEPATH##s/exporter', name: '##PREFIX##_export')]
#[IsGranted(##ENTITY##Voter::EXPORT)]
class Export##ENTITY##Controller extends AbstractController
{
    public function __invoke(
        Request $request,
        ##ENTITY##Repository $repository,
        TranslatorInterface $translator,
        #[MapQueryParameter] ?string $q,
    ): Response {
         $##ENTITYCAMEL##s = $repository
            ->getQB()
            ->querySearch($q)
            ->order('updatedAt', 'DESC')
            ->getResults()
        ;

        $csv = new CsvWriter();
        $csv->setHeaders([$translator->trans('Id')]); // TODO: Implements
        $csv->setRows(array_map(function (##ENTITY## $##ENTITYCAMEL##) {
            return [
                $##ENTITYCAMEL##->getId(), // TODO: Implements
            ];
        }, $##ENTITYCAMEL##s));

        // TODO: Translation
        return new CsvFileResponse($csv->getContent(), (new AsciiSlugger())->slug($translator->trans('list_of_##ROUTEPATH##s').'.csv');
    }
}
