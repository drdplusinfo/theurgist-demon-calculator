<?php declare(strict_types=1);

namespace DrdPlus\Tests\Calculators\Theurgist;

use DrdPlus\Calculators\Theurgist\CurrentDemonValues;
use DrdPlus\Calculators\Theurgist\DemonServicesContainer;
use DrdPlus\CalculatorSkeleton\CurrentValues;
use DrdPlus\CalculatorSkeleton\Memory;
use DrdPlus\Codes\Theurgist\DemonCode;
use DrdPlus\RulesSkeleton\Configuration;
use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\ServicesContainer;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\CalculatorSkeleton\Partials\AbstractCalculatorContentTest;
use Granam\Number\NumberObject;
use Mockery\MockInterface;

class CurrentDemonValuesTest extends AbstractCalculatorContentTest
{
    /**
     * @test
     */
    public function I_can_get_current_demon_code()
    {
        $currentDemonValues = new CurrentDemonValues($this->createCurrentValues([]), Tables::getIt());
        $defaultDemonCode = DemonCode::findIt('nonsense');
        self::assertSame($defaultDemonCode, $currentDemonValues->getCurrentDemonCode());
        $currentDemonValues = new CurrentDemonValues(
            $this->createCurrentValues([CurrentDemonValues::DEMON => DemonCode::IMP]),
            Tables::getIt()
        );
        self::assertNotSame($defaultDemonCode, DemonCode::getIt(DemonCode::IMP));
        self::assertSame(DemonCode::getIt(DemonCode::IMP), $currentDemonValues->getCurrentDemonCode());
    }

    /**
     * @param array $values
     * @return CurrentValues|MockInterface
     */
    private function createCurrentValues(array $values): CurrentValues
    {
        $currentValues = $this->mockery(CurrentValues::class);
        $currentValues->shouldReceive('getCurrentValue')
            ->andReturnUsing(function (string $name) use ($values) {
                return $values[$name] ?? null;
            });
        return $currentValues;
    }

    /**
     * @test
     */
    public function I_can_format_number(): void
    {
        $currentDemonValues = $this->createCurrentDemonValues();
        self::assertSame('+123', $currentDemonValues->formatNumber(new NumberObject(123)));
        self::assertSame('-456', $currentDemonValues->formatNumber(new NumberObject(-456)));
        self::assertSame('+0', $currentDemonValues->formatNumber(new NumberObject(0)));
    }

    private function createCurrentDemonValues(): CurrentDemonValues
    {
        return new CurrentDemonValues(new CurrentValues([], $this->createMemory()), Tables::getIt());
    }

    /**
     * @return Memory|MockInterface
     */
    private function createMemory(): Memory
    {
        return $this->mockery(Memory::class);
    }

    /**
     * @param Configuration|null $configuration
     * @param HtmlHelper|null $htmlHelper
     * @return ServicesContainer|DemonServicesContainer
     */
    protected function createServicesContainer(Configuration $configuration = null, HtmlHelper $htmlHelper = null): ServicesContainer
    {
        return new DemonServicesContainer(
            $configuration ?? $this->getConfiguration(),
            $htmlHelper ?? $this->createHtmlHelper($this->getDirs())
        );
    }
}