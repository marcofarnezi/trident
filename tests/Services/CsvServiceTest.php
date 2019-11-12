<?php
namespace Services;

use App\Exports\ProductCounterExports;
use App\Services\CsvService;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;

/**
 * Class CsvServiceTest
 * @package Services
 */
class CsvServiceTest extends \TestCase
{
    private $csvService;

    /**
     * CsvServiceTest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->csvService = new CsvService();
    }

    public function test_csv_service_load_header()
    {
        $expectedHeader = ['name header', 'title header', 'items header'];
        $exportMock = $this->createExportMock();
        $this->csvService->setExport($exportMock);
        $header = $this->csvService->loadHeader();

        $this->assertEquals($expectedHeader, $header);
    }

    public function test_csv_service_load_data()
    {
        $expectedData = [
            [
                'name' => 'teste name',
                'title' => 'teste title',
                'items' => 5,
            ]
        ];
        $exportMock = $this->createExportMock();
        $this->csvService->setExport($exportMock);
        $data = $this->csvService->loadData();

        $this->assertEquals($expectedData, $data);
    }

    public function test_generate_csv()
    {
        $pathCsv = './storage/app/test.csv';

        $exportMock = $this->createExportMock();
        $this->csvService->setExport($exportMock);
        $this->csvService->loadHeader();
        $this->csvService->loadData();
        $this->csvService->generate($pathCsv);

        $content = file_get_contents($pathCsv);

        $this->assertTrue(strpos($content, 'name heade') !== false);
        $this->assertTrue(strpos($content, 'title header') !== false);
        $this->assertTrue(strpos($content, 'items header') !== false);
        $this->assertTrue(strpos($content, 'teste name') !== false);
        $this->assertTrue(strpos($content, 'teste title') !== false);
        $this->assertTrue(strpos($content, '5') !== false);
    }

    /**
     * @return ProductCounterExports|LegacyMockInterface|MockInterface
     */
    private function createExportMock()
    {
        $exportMock = \Mockery::mock(ProductCounterExports::class);

        $buidMock = $this->createBiuldMock();
        $exportMock->shouldReceive('getData')
            ->andReturn($buidMock);

        $exportMock->shouldReceive('headColuns')
            ->andReturn(['name header', 'title header', 'items header']);

        return $exportMock;
    }

    /**
     * @return Builder|LegacyMockInterface|MockInterface
     */
    private function createBiuldMock()
    {
        $collection = new Collection();
        $collection->add(
            [
                'name' => 'teste name',
                'title' => 'teste title',
                'items' => 5,
            ],
        );

        $buildMock = \Mockery::mock(Builder::class);
        $buildMock->shouldReceive('get')
            ->andReturn($collection);

        return $buildMock;
    }
}
