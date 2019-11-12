<?php
/**
 *
 * PHP version >= 7.0
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
namespace App\Console\Commands;

use App\Exports\ProductCounterExports;
use App\Services\CsvService;
use Exception;
use Illuminate\Console\Command;

/**
 * Class ProductsCounterCsvCommand
 * @package App\Console\Commands
 */
class ProductsCounterCsvCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "csv:productsCounter {path=products.csv}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generate product counter list in csv";

    private $csv;

    /**
     * ProductsCounterCsvCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $export = new ProductCounterExports();
        $csv = new CsvService();
        $csv->setExport($export);
        $csv->loadHeader();
        $csv->loadData();
        $this->csv = $csv;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $path = $this->argument('path');
            $return = $this->csv->generate($path);
            if ($return) {
                $this->info('Csv generate in '. $path);
                return;
            }
            $this->error("An error occurred");
        } catch (Exception $e) {
            $this->error("An error occurred");
        }
    }
}
