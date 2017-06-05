<?php
namespace Banana\Model;

use Cake\ORM\BehaviorRegistry;

/**
 * Class CsvTable
 *
 * @package Banana\Model
 */
class CsvTable extends ArrayTable
{

    /**
     * @var string CSV file path
     */
    protected $_file;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        if (isset($config['displayField'])) {
            $this->displayField($config['displayField']);
        }

        if (!isset($config['file']) || !is_file($config['file'])) {
            throw new \RuntimeException('CsvTable: No file given or file does not exist');
        }

        $this->_file = $config['file'];
        $this->_config = $config;

        $this->_behaviors = new BehaviorRegistry();
        $this->_behaviors->eventManager()->unsetEventList();

        $this->intialize();
    }

    /**
     * @return string
     */
    public function table()
    {
        return basename($this->_file, '.csv');
    }

    /**
     * Return array table data
     *
     * @return array
     */
    public function getItems()
    {

        $file = fopen($this->_file,"r");
        if (!$file) {
            throw new \RuntimeException("Failed to open file $this->_file");
        }

        $header = [];
        $rows = [];
        $columns = [];
        $i = 0;
        while(! feof($file)) {
            $line = fgetcsv($file, 1024, ";");
            if (!$line) {
                break;
            }

            // header
            if ($i++ == 0) {
                $header = $line;

                // get rid of last element if it is empty
                if (empty($header[count($header) - 1])) {
                    unset($header[count($header) - 1]);
                }
                continue;
            }


            // r0w
            $id = $i - 1;
            $row = ['id' => $id];
            for ($j = 0; $j < count($header); $j++) {
                $row[$header[$j]] = trim($line[$j]);
            }
            $rows[$id] = $row;
        }

        fclose($file);

        return $rows;
    }
}
