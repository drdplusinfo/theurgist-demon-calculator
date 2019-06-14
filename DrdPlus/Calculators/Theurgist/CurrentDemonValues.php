<?php declare(strict_types=1);

namespace DrdPlus\Calculators\Theurgist;

use DrdPlus\CalculatorSkeleton\CurrentValues;
use DrdPlus\Codes\Theurgist\DemonCode;
use DrdPlus\Codes\Theurgist\DemonTraitCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\Demon;
use DrdPlus\Tables\Theurgist\Demons\DemonTrait;
use Granam\Number\NumberInterface;
use Granam\Strict\Object\StrictObject;

class CurrentDemonValues extends StrictObject
{
    public const DEMON = 'demon';
    public const PREVIOUS_DEMON = 'previous_demon';
    public const DEMON_TRAITS = 'demon_traits';

    /** @var CurrentValues */
    private $currentValues;
    /** @var DemonCode */
    private $currentDemonCode;
    /** @var Tables */
    private $tables;

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
    public function getCurrentDemonTraitValues(): array
    {
        $formulaSpellTraits = $this->currentValues->getCurrentValue(self::DEMON_TRAITS);
        if ($formulaSpellTraits === null || $this->isDemonChanged()) {
            return [];
        }

        return (array)$formulaSpellTraits;
    }

    /**
     * @return array|DemonTrait[]
     */
    public function getCurrentDemonTraits(): array
    {
        return array_map(
            function (string $demonTraitValue) {
                return new DemonTrait(DemonTraitCode::getIt($demonTraitValue), $this->tables);
            },
            $this->getCurrentDemonTraitValues()
        );
    }

    public function getCurrentDemon(): Demon
    {
        return new Demon(
            $this->getCurrentDemonCode(),
            $this->getCurrentDemonTraits()
        );
    }

    public function formatNumber(NumberInterface $number): string
    {
        return $number->getValue() >= 0
            ? '+' . $number->getValue()
            : (string)$number->getValue();
    }
}