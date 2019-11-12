<?php
namespace App\Services;

use App\Contracts\CsvInterface;
use App\Contracts\ExportsInterface;

/**
 * Class CsvService
 * @package App\Services
 */
class CsvService implements CsvInterface
{
    /**
     * @var ExportsInterface
     */
    private $export;
    private $header = [];
    private $data = [];

    /**
     * @param ExportsInterface $export
     */
    public function setExport(ExportsInterface $export)
    {
        $this->export = $export;
    }

    /**
     * @return array
     */
    public function loadHeader() : array
    {
        $this->header = $this->export->headColuns();
        return $this->header;
    }

    /**
     * @return array
     */
    public function loadData() : array
    {
        $objData = $this->export->getData();
        $this->data = $objData->get();
        return $this->data->toArray();
    }

    /**
     * @param string $path
     * @return bool
     */
    public function generate($path = 'list.csv') : bool
    {
        $list = $this->unionData();

        $fp = fopen($path, 'w');
        foreach ($list as $fields) {
            fputcsv($fp, $fields);
        }

        return fclose($fp);
    }

    /**
     * @return array
     */
    public function unionData()
    {
        $list[] = $this->header;
        foreach ($this->data as $value) {
            $list[] = array_values((array)$value);
        }

        return $list;
    }
}
