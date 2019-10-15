<?php declare(strict_types=1);

namespace DrdPlus\Calculators\Theurgist;

use DrdPlus\CalculatorSkeleton\CalculatorServicesContainer;
use DrdPlus\RulesSkeleton\Web\WebPartsContainer;
use DrdPlus\Tables\Tables;

class DemonServicesContainer extends CalculatorServicesContainer
{
    /** @var DemonWebPartsContainer */
    private $demonWebPartsContainer;
    /** @var CurrentDemonValues */
    private $currentDemonValues;

    /**
     * @return WebPartsContainer|DemonWebPartsContainer
     */
    public function getRoutedWebPartsContainer(): WebPartsContainer
    {
        if ($this->demonWebPartsContainer === null) {
            $this->demonWebPartsContainer = new DemonWebPartsContainer(
                $this->getPass(),
                $this->getRoutedWebFiles(),
                $this->getDirs(),
                $this->getHtmlHelper(),
                $this->getRequest(),
                $this->getCurrentDemonValues(),
                $this->getTables()
            );
        }
        return $this->demonWebPartsContainer;
    }

    public function getCurrentDemonValues(): CurrentDemonValues
    {
        if ($this->currentDemonValues === null) {
            $this->currentDemonValues = new CurrentDemonValues(
                $this->getCurrentValues(),
                $this->getTables()
            );
        }

        return $this->currentDemonValues;
    }

    public function getTables(): Tables
    {
        return Tables::getIt();
    }
}