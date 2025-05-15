<?php
declare(strict_types=1);

namespace Cupcake\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\EntityInterface;

/**
 * SetupAttributes command.
 *
 * @deprecated
 */
class SetupAttributesCommand extends Command
{
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);
        $parser->addArgument('modelName', [
            'help' => 'Model class name',
            'required' => true,
        ]);
        $parser->addOption('connection', [
            'help' => 'Connection name',
            'short' => 'c'
        ]);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $modelName = $args->getArgument('modelName');

        /** @var \Cake\ORM\Table $Model */
        $Model = $this->loadModel($modelName);

        if ($args->getOption('connection')) {
            $connection = ConnectionManager::get($args->getOption('connection'));
            $Model->setConnection($connection);
        }

        if (!$Model->hasBehavior('Attributes')) {
            $Model->addBehavior('Cupcake.Attributes');
        }

        // get registered attributes
        /** @var array $attributes */
        $attributes = $Model->getAttributesSchema();

        foreach ($attributes as $aName => $aConfig) {
            $aConfig += ['type' => null, 'default' => null, 'required' => null];
            $aType = $aConfig['type'] ?? 'string';
            $aDefault = $aConfig['default'] ?? '';
            $aReq = $aConfig['required'] ?? false;
            $io->out(sprintf("Attribute [%s] '%s'\t(Type: '%s',\t Default: '%s', \t Required: '%s')", $modelName, $aName, $aType, $aDefault, $aReq));
        }

        // check if all records have given attribute
        $count = $Model->find()->count();
        $io->info(sprintf("Found %d records in model %s", $count, $modelName));

        $limit = 100;
        $pages = intval(ceil($count / $limit));
        for ($i = 0; $i < $pages; $i++) {
            $records = $Model->find()
                ->offset($i * $limit)
                ->limit($limit)
                ->all();

            $records->each(function(EntityInterface $entity) use ($attributes, $io) {
                foreach ($attributes as $aName => $aConfig) {
                    $aConfig += ['type' => null, 'default' => null, 'required' => null];
                    $aType = $aConfig['type'] ?? 'string';
                    $aDefault = $aConfig['default'] ?? '';
                    $aReq = $aConfig['required'] ?? false;

                    if ($entity->has($aName)) {
                        $msg = "Found attribute";
                        $type = "success";
                    } else {
                        $msg = "NOT FOUND";
                        $type = "warning";
                        if ($aReq) {
                            $type = "error";
                        }
                    }
                    $io->$type(sprintf("[%s] %s '%s' \t (Type: '%s',\t Default: '%s', \t Required: '%s')", $msg, $entity->id, $aName, $aType, $aDefault, $aReq));
                }
                $io->out("---");
            });
        }
    }
}
