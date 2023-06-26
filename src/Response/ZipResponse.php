<?php

namespace AppoloDev\SFToolboxBundle\Response;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use ZipArchive;

class ZipResponse extends BinaryFileResponse
{
// TODO: au lieu de $files de type array -> File[] ?
// TODO: Refactor
    public function __construct(
        string $fileName = 'export.zip',
        array  $files = [],
        int    $status = 200,
        array  $headers = [],
        bool   $public = true,
        string $contentDisposition = ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        bool   $autoEtag = false,
        bool   $autoLastModified = true
    ){
        $zipPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid();

        $zip = new ZipArchive();
        $res = $zip->open($zipPath, ZipArchive::CREATE);

        if ($res !== true) {
            throw new FileException($res);
        }

        foreach ($files as $file) {
            $zip->addFile($file['path'], $file['name']);
        }

        $zip->close();

        parent::__construct($zipPath, $status, $headers, $public, $contentDisposition, $autoEtag, $autoLastModified);

        $this->setContentDisposition($contentDisposition, $fileName);
    }
}