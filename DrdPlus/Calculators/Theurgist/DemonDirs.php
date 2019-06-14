<?php declare(strict_types=1);

namespace DrdPlus\Calculators\Theurgist;

use DrdPlus\RulesSkeleton\Dirs;

class DemonDirs extends Dirs
{
    public function getWebRoot(): string
    {
        return $this->getProjectRoot() . '/web/web/';
    }

}