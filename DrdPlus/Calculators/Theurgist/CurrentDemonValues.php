<?php declare(strict_types=1);

namespace DrdPlus\Calculators\Theurgist;

use DrdPlus\CalculatorSkeleton\CurrentValues;
use DrdPlus\Codes\Theurgist\DemonCode;
use DrdPlus\Codes\Theurgist\DemonTraitCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\Demon;
use DrdPlus\Tables\Theurgist\Demons\DemonTrait;
use Granam\Integer\Tools\ToInteger;
use Granam\Number\NumberInterface;
use Granam\Strict\Object\StrictObject;

class CurrentDemonValues extends StrictObject
{
    public const DEMON = 'demon';
    public const PREVIOUS_DEMON = 'previous_demon';
    public const DEMON_PARAMETERS = 'demon_parameters';
    public const DEMON_TRAITS = 'demon_traits';

    /** @var CurrentValues */
    private $currentValues;
    /** @var DemonCode */
    private $currentDemonCode;
    /** @var Tables */
    private $tables;
    /** @var Demon|null */
    private $currentDemon;
    /** @var array|DemonTrait[]|null */
    private $currentDemonTraits;
    /** @var array|string[]|null */
    private $currentDemonParameterValues;
    /** @var array|int[]|null */
    private $currentDemonTraitValues;

    public function __construct(CurrentValues $currentValues, Tables $tables)
    {
        $this->currentValues = $currentValues;
        $this->tables = $tables;
    }

    public function getCurrentDemonCode(): DemonCode
    {
        if ($this->currentDemonCode === null) {
            $this->currentDemonCode = DemonCode::findIt($this->currentValues->getCurrentValue(self::DEMON));
        }
        return $this->currentDemonCode;
    }

    private function isDemonChanged(): bool
    {
        return $this->currentDemonCode->getValue() !== $this->getPreviousDemonValue();
    }

    private function getPreviousDemonValue(): ?string
    {
        return $this->currentValues->getCurrentValue(self::PREVIOUS_DEMON);
    }

    /**
     * @return array|string[]
     */
    public function getCurrentDemonParameterValues(): array
    {
        if ($this->currentDemonParameterValues === null) {
            $demonParameterValues = $this->currentValues->getCurrentValue(self::DEMON_PARAMETERS);
            if ($demonParameterValues === null || $this->isDemonChanged()) {
                $demonParameterValues = [];
            }
            $this->currentDemonParameterValues = array_map(
                static function ($demonParameter) {
                    return ToInteger::toInteger($demonParameter);
                },
                (array)$demonParameterValues
            );
        }
        return $this->currentDemonParameterValues;
    }

    /**
     * @return array|string[]
     */
    public function getCurrentDemonTraitValues(): array
    {
        if ($this->currentDemonTraitValues === null) {
            $demonTraitValues = $this->currentValues->getCurrentValue(self::DEMON_TRAITS);
            if ($demonTraitValues === null || $this->isDemonChanged()) {
                $demonTraitValues = [];
            }
            $this->currentDemonTraitValues = (array)$demonTraitValues;
        }
        return $this->currentDemonTraitValues;
    }

    /**
     * @return array|DemonTrait[]
     */
    public function getCurrentDemonTraits(): array
    {
        if ($this->currentDemonTraits === null) {
            $this->currentDemonTraits = array_map(
                function (string $demonTraitValue) {
                    return new DemonTrait(DemonTraitCode::getIt($demonTraitValue), $this->tables);
                },
                $this->getCurrentDemonTraitValues()
            );
        }
        return $this->currentDemonTraits;
    }

    public function getCurrentDemon(): Demon
    {
        if ($this->currentDemon === null) {
            $this->currentDemon = new Demon(
                $this->getCurrentDemonCode(),
                $this->tables,
                $this->getCurrentDemonParameterValues(),
                $this->getCurrentDemonTraits()
            );
        }
        return $this->currentDemon;
    }

    public function formatNumber(NumberInterface $number): string
    {
        return $number->getValue() >= 0
            ? '+' . $number->getValue()
            : (string)$number->getValue();
    }
}