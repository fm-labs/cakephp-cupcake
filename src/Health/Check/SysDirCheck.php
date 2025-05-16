<?php
declare(strict_types=1);

namespace Cupcake\Health\Check;

use Cupcake\Cupcake;
use Cupcake\Health\HealthCheckGeneratorInterface;
use Cupcake\Health\HealthStatus;
use Generator;

class SysDirCheck implements HealthCheckGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function getHealthStatus(): Generator
    {
        $dirs = Cupcake::getSysDirs();
        $fail = $found = 0;
        foreach ($dirs as $dir) {
            $isDir = file_exists($dir) && is_dir($dir);
            //$isWritable = is_writable($dir);
            if (!$isDir) {
                $fail++;
                yield HealthStatus::crit(__d('admin', 'Directory `{0}` NOTFOUND', $dir));
            } else {
                $found++;
                yield HealthStatus::ok(__d('admin', 'Directory `{0}` exists', $dir));
            }
        }

        if ($fail > 0) {
            yield HealthStatus::crit(__d('admin', '{0} directories not found', $fail));
        }
        //else {
        //    yield HealthStatus::ok(__d('admin', 'All system directories exist'));
        //}
    }
}
