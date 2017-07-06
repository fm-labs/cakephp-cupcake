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
     * @var array
     */
    protected $_columns = [];

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        if (isset($config['displayField'])) {
            $this->displayField($config['displayField']);
        }

        if (!isset($config['file']) || !is_file($config['file'])) {
            throw new \RuntimeException('CsvTable: No file given or file does not exist: ' . $config['file']);
        }

        if (!isset($config['columns'])) {
            $config['columns'] = [];
        }

        $this->_file = $config['file'];
        $this->_columns = $config['columns'];
        $this->_config = $config;

        $this->_behaviors = new BehaviorRegistry();
        $this->_behaviors->eventManager()->unsetEventList();

        $this->initialize();
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

        $file = fopen($this->_file, "r");
        if (!$file) {
            throw new \RuntimeException("Failed to open file $this->_file");
        }

        $header = $this->_columns;
        $rows = [];
        $i = 0;
        while (! feof($file)) {
            $line = fgetcsv($file, 1024, ";");
            if (!$line) {
                break;
            }

            // header
            $i++;
            if ($i == 1 && $header !== false && empty($header)) {
                $header = $line;
                $i--;

                // get rid of last element if it is empty
                if (empty($header[count($header) - 1])) {
                    unset($header[count($header) - 1]);
                }
                continue;
            }

            // r0w
            $row = [];
            for ($j = 0; $j < count($line); $j++) {
                $val = trim($line[$j]);

                $col = $j;
                if ($header !== false && isset($header[$j])) {
                    $col = $header[$j];
                }
                $row[$col] = $val;
            }
            $rows[$i-1] = $row;
        }

        fclose($file);

        return $rows;
    }
}
