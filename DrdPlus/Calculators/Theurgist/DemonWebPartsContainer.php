<?php declare(strict_types=1);

namespace DrdPlus\Calculators\Theurgist;

use DrdPlus\CalculatorSkeleton\Web\CalculatorWebPartsContainer;
use DrdPlus\Codes\Theurgist\DemonCode;
use DrdPlus\RulesSkeleton\Dirs;
use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\Request;
use DrdPlus\RulesSkeleton\Web\Pass;
use DrdPlus\RulesSkeleton\Web\WebFiles;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\Demon;

class DemonWebPartsContainer extends CalculatorWebPartsContainer
{
    /**
     * @var CurrentDemonValues
     */
    private $currentDemonValues;
    /**
     * @var Tables
     */
    private $tables;

    public function __construct(
        Pass $pass,
        WebFiles $webFiles,
        Dirs $dirs,
        HtmlHelper $htmlHelper,
        Request $request,
        CurrentDemonValues $currentDemonValues,
        Tables $tables
    )
    {
        parent::__construct($pass, $webFiles, $dirs, $htmlHelper, $request);
        $this->currentDemonValues = $currentDemonValues;
        $this->tables = $tables;
    }

    public function getCurrentDemonCode(): DemonCode
    {
        return $this->getCurrentDemonValues()->getCurrentDemonCode();
    }

    public function getCurrentDemon(): Demon
    {
        return $this->getCurrentDemonValues()->getCurrentDemon();
    }

    public function getCurrentDemonValues(): CurrentDemonValues
    {
        return $this->currentDemonValues;
    }

    public function getTables(): Tables
    {
        return $this->tables;
    }
}