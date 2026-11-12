<?php

namespace AppoloDev\SFToolboxBundle\Domain\Entity\Concern\FileUpload;

use Symfony\Component\HttpFoundation\File\File;

interface FileUploadInterface
{
    public function getFilename(): ?string;

    public function getFile(): ?File;

    public function getFilePath(): ?string;
}
