<?php

namespace AppoloDev\SFToolboxBundle\Response;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ZipResponse extends BinaryFileResponse
{
    /**
     * @param File[] $files
     */
    public function __construct(
        array $files,
        string $zipName = 'export.zip',
        int $status = 200,
        array $headers = [],
        bool $public = true,
        string $contentDisposition = ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        bool $autoEtag = false,
        bool $autoLastModified = true
    ) {
        $zipPath = sys_get_temp_dir().DIRECTORY_SEPARATOR.uniqid();

        $zip = new \ZipArchive();
        $isOpen = $zip->open($zipPath, \ZipArchive::CREATE);

        if (true !== $isOpen) {
            throw new FileException('Zip file can\'t be create');
        }

        foreach ($files as $file) {
            $filePath = $file->getRealPath();
            if (is_string($filePath)) {
                $zip->addFile($filePath, $file->getFilename());
            }
        }

        $zip->close();

        parent::__construct($zipPath, $status, $headers, $public, $contentDisposition, $autoEtag, $autoLastModified);

        $this->setContentDisposition($contentDisposition, $zipName);
    }
}
