<?php

namespace AppoloDev\SFToolboxBundle\Csv;

use League\Csv\AbstractCsv;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\Writer;

class CsvWriter
{
    private AbstractCsv $csv;

    public function __construct()
    {
        $this->csv = Writer::createFromString();
        $this->csv->setDelimiter(';');
        $this->csv->setOutputBOM(Reader::BOM_UTF8);
    }

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

    public function getContent(): string
    {
        return $this->csv->toString();
    }
}