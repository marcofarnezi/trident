<?php
namespace App\Contracts;

interface CsvInterface
{
    public function setExport(ExportsInterface $export);

    public function loadHeader();

    public function loadData();

    public function generate();

}
