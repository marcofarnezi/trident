<?php
namespace Exports;

use App\Contracts\ExportsInterface;
use App\Exports\ProductCounterExports;
use Illuminate\Database\Query\Builder;

/**
 * Class ProductCounterExportsTest
 * @package Exports
 */
class ProductCounterExportsTest extends \TestCase
{
    private $productCounterExports;

    /**
     * ProductCounterExportsTest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->productCounterExports = new ProductCounterExports();
    }

    public function test_instanceof_exports_interface()
    {
        $this->assertInstanceOf(ExportsInterface::class, $this->productCounterExports);
    }

    public function test_product_counter_exports_header()
    {
        $expectedHeader = ['user', 'title', '#items'];
        $header = $this->productCounterExports->headColuns();

        $this->assertEquals($expectedHeader, $header);
    }

    public function test_product_counter_exports_data_type()
    {
        $builder = $this->productCounterExports->getData();

        $this->assertInstanceOf(Builder::class, $builder);
    }
}
