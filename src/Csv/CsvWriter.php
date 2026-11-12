<?php

namespace AppoloDev\SFToolboxBundle\Csv;

use League\Csv\Bom;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use League\Csv\Writer;

readonly class CsvWriter
{
    private Writer $csv;

    /**
     * @throws InvalidArgument
     */
    public function __construct()
    {
        $this->csv = Writer::createFromString();
        $this->csv->setDelimiter(';');
        $this->csv->setOutputBOM(Bom::Utf8);
    }

    /**
     * @throws InvalidArgument
     */
    public function setDelimiter(string $delimiter): void
    {
        $this->csv->setDelimiter($delimiter);
    }

    /**
     * @throws CannotInsertRecord
     * @throws Exception
     */
    public function setHeaders(array $headers): self
    {
        $this->csv->insertOne($headers);

        return $this;
    }

    /**
     * @throws CannotInsertRecord
     * @throws Exception
     */
    public function setRows(array $rows): self
    {
        $this->csv->insertAll($rows);

        return $this;
    }

    /**
     * @throws CannotInsertRecord
     * @throws Exception
     */
    public function setRow(array $row): self
    {
        $this->csv->insertOne($row);

        return $this;
    }

    /**
     * @throws Exception
     */
    public function getContent(): string
    {
        return $this->csv->toString();
    }
}
