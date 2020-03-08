<?php

namespace Banana\Model\Behavior;

use Banana\Model\TableInputSchema;
use Cake\ORM\Behavior;
use Cake\Utility\Inflector;

class InputSchemaBehavior extends Behavior
{

    public static $typeMap = [
        'integer' => 'Number',
        'string' => 'Text',
        'text' => 'Textarea',
        'date' => 'Date',
        'datetime' => 'Datetime',
        'timestamp' => 'Text',
        'boolean' => 'Checkbox',
    ];

    /**
     * @var array
     */
    protected $_defaultConfig = [
        'implementedMethods' => [
            'inputs' => 'getInputs',
        ],
    ];

    /**
     * @var TableInputSchema
     */
    protected $_inputs;

    public function initialize(array $config = [])
    {
        $this->_inputs = new TableInputSchema();

        //$this->_table->associations();
        //debug($this->_table->associations());

        // init from table schema
        foreach ($this->_table->getSchema()->columns() as $colName) {
            $col = $this->_table->getSchema()->column($colName);
            $input = $this->_detectInputSchema($colName, $col);
            $this->_inputs->addField($colName, $input);
        }

        if (method_exists($this->_table, 'buildInputs')) {
            $this->_inputs = call_user_func([$this->_table, 'buildInputs'], $this->_inputs);
        }

        //@TODO Dispatch Model.buildInputs event.
    }

    /**
     * @return TableInputSchema
     */
    public function getInputs()
    {
        return $this->_inputs;
    }

    protected function _detectInputSchema($colName, $col)
    {
        $input = [
            '_dataType' => $col['type'],
            'type' => 'Text',
            'label' => Inflector::humanize($colName),
        ];

        // belongsTo association
        if (substr($colName, -3) === "_id") {
            $belongsTo = $this->_table->associations()->type('belongsTo');
            foreach ($belongsTo as $assoc) {
                if ($assoc->foreignKey() == $colName) {
                    $input['type'] = 'ChosenSelect'; // 'Select';
                    $input['options'] = $assoc->find('list')->toArray();
                    $input['placeholder'] = true; //@TODO Check default model validator, if field can be empty

                    return $input;
                }
            }
        }

        // guess by field name conventions
        if ($colName == 'id') {
            $input['type'] = 'Hidden';
        } elseif ($colName === "created" || $colName === "modified" || $colName === "updated") {
            $input['type'] = 'Hidden';
        } elseif (substr($colName, -5) === "_date") {
            $input['type'] = 'DatePicker'; // 'Date';
        } elseif (substr($colName, 0, 3) === "is_") {
            $input['type'] = 'Checkbox';
        } elseif (substr($colName, 0, 4) === "has_") {
            $input['type'] = 'Checkbox';
        } elseif (substr($colName, -5) === "_text") {
            $input['type'] = 'Textarea';
        } elseif (substr($colName, -5) === "_html") {
            $input['type'] = 'Html';
        } elseif ($this->_table->hasBehavior('Tree') && in_array($colName, ['lft', 'rght', 'level'])) {
            $input['type'] = 'Hidden';
        } // guess by column type
        elseif (isset(self::$typeMap[$col['type']])) {
            $input['type'] = self::$typeMap[$col['type']];
        }

        return $input;
    }
}
