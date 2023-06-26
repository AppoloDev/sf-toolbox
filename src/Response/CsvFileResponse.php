<?php

namespace AppoloDev\SFToolboxBundle\Response;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CsvFileResponse extends Response
{
    public function __construct(
        ?string $csvContent = '',
        string $fileName = 'export.csv',
        int $status = 200,
        array $headers = []
    ) {
        parent::__construct($csvContent, $status, $headers);

        $disposition = $this->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $fileName
        );
        $this->headers->set('Content-Type', 'text/csv');
        $this->headers->set('Content-Disposition', $disposition);
    }
}
