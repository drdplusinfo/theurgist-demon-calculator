<?php declare(strict_types=1);

namespace DrdPlus\Calculators\Theurgist;

use DrdPlus\CalculatorSkeleton\CalculatorServicesContainer;
use DrdPlus\RulesSkeleton\Web\WebFiles;
use DrdPlus\RulesSkeleton\Web\WebPartsContainer;
use DrdPlus\Tables\Tables;

class DemonServicesContainer extends CalculatorServicesContainer
{
    /** @var DemonWebPartsContainer */
    private $routedDemonWebPartsContainer;
    /** @var DemonWebPartsContainer */
    private $rootDemonWebPartsContainer;
    /** @var CurrentDemonValues */
    private $currentDemonValues;

    /**
     * @return WebPartsContainer|DemonWebPartsContainer
     */
    public function getRoutedWebPartsContainer(): WebPartsContainer
    {
        if ($this->routedDemonWebPartsContainer === null) {
            $this->routedDemonWebPartsContainer = $this->createDemonWebPartsContainer($this->getRoutedWebFiles());
        }
        return $this->routedDemonWebPartsContainer;
    }

    private function createDemonWebPartsContainer(WebFiles $webFiles): DemonWebPartsContainer
    {
        return new DemonWebPartsContainer(
            $this->getPass(),
            $webFiles,
            $this->getDirs(),
            $this->getHtmlHelper(),
            $this->getRequest(),
            $this->getCurrentDemonValues(),
            $this->getTables()
        );
    }

    /**
     * @return WebPartsContainer|DemonWebPartsContainer
     */
    public function getRootWebPartsContainer(): WebPartsContainer
    {
        if ($this->rootDemonWebPartsContainer === null) {
            $this->rootDemonWebPartsContainer = $this->createDemonWebPartsContainer($this->getRootWebFiles());
        }
        return $this->rootDemonWebPartsContainer;
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