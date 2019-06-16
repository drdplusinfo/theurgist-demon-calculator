<?php
namespace DrdPlus\Theurgist\Demons;

use DrdPlus\Calculators\Theurgist\CurrentDemonValues;
use DrdPlus\Codes\Theurgist\DemonMutableParameterCode;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Measurement;
use DrdPlus\Tables\Measurements\Partials\Exceptions\UnknownBonus;
use DrdPlus\Tables\Measurements\Speed\Speed;
use DrdPlus\Tables\Measurements\Speed\SpeedBonus;
use DrdPlus\Tables\Measurements\Time\Time;
use DrdPlus\Tables\Measurements\Time\TimeBonus;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;
use Granam\String\StringTools;

/** @var \DrdPlus\Calculators\Theurgist\DemonWebPartsContainer $webPartsContainer */

$demonParametersWithUnit = [
    DemonMutableParameterCode::DEMON_ACTIVATION_DURATION => function ($activationDurationValue) {
        return (new TimeBonus($activationDurationValue, Tables::getIt()->getTimeTable()))->getTime();
    },
    DemonMutableParameterCode::DEMON_AREA => function ($areaValue) {
        return (new DistanceBonus($areaValue, Tables::getIt()->getDistanceTable()))->getDistance();
    },
    DemonMutableParameterCode::DEMON_RADIUS => function ($radiusValue) {
        return (new DistanceBonus($radiusValue, Tables::getIt()->getDistanceTable()))->getDistance();
    },
    DemonMutableParameterCode::SPELL_SPEED => function ($speedValue) {
        return (new SpeedBonus($speedValue, Tables::getIt()->getSpeedTable()))->getSpeed();
    },
];
foreach ($demonParametersWithUnit as $parameterName => $unitFactory) {
    $getParameter = StringTools::assembleGetterForName($parameterName);
    /** @var CastingParameter $parameter */
    $parameter = $webPartsContainer->getTables()->getDemonsTable()->$getParameter($webPartsContainer->getCurrentDemonCode());
    if ($parameter === null) {
        continue;
    }
    $parameterCode = DemonMutableParameterCode::getIt($parameterName);
    ?>
  <div class="col">
    <label><?= $parameterCode->translateTo('cs') ?>:
        <?php
        $parameterAdditionByDifficulty = $parameter->getAdditionByDifficulty();
        $additionStep = $parameterAdditionByDifficulty->getAdditionStep();
        $optionParameterChange = 0;
        $parameterDifficultyChange = $parameterAdditionByDifficulty->getCurrentDifficultyIncrement();
        /** @var Measurement $previousOptionParameterValueWithUnit */
        $previousOptionParameterValueWithUnit = null;
        $selectedParameterValue = $webPartsContainer->getCurrentDemonValues()->getCurrentDemonParameterValues()[$parameterName] ?? false;
        ?>
      <select name="<?= CurrentDemonValues::DEMON_PARAMETERS ?>[<?= $parameterName ?>]">
          <?php
          do {
              $optionParameterValue = $parameter->getValue(); // from the lowest
              /** @var Distance|Time|Speed $optionValueWithUnit */
              try {
                  $optionValueWithUnit = $unitFactory($optionParameterValue);
              } catch (UnknownBonus $unknownBonus) {
                  break; // we have reached the limit
              }
              if (!$previousOptionParameterValueWithUnit
                  || $previousOptionParameterValueWithUnit->getUnit() !== $optionValueWithUnit->getUnit()
                  || $previousOptionParameterValueWithUnit->getValue() < $optionValueWithUnit->getValue()
              ) {
                  $optionUnitInCzech = $optionValueWithUnit->getUnitCode()->translateTo('cs', $optionValueWithUnit->getValue());
                  ?>
                <option value="<?= $optionParameterValue ?>"
                        <?php if ($selectedParameterValue !== false && (string)$selectedParameterValue === (string)$optionParameterValue){ ?>selected<?php } ?>>
                    <?= ($optionParameterValue >= 0 ? '+' : '')
                    . "{$optionParameterValue} ({$optionValueWithUnit->getValue()} {$optionUnitInCzech}) [{$parameterDifficultyChange}]"; ?>
                </option>
              <?php }
              $previousOptionParameterValueWithUnit = $optionValueWithUnit;
              $optionParameterChange++;
              /** @noinspection PhpUnhandledExceptionInspection */
              $parameter = $parameter->getWithAddition($optionParameterChange);
              $parameterAdditionByDifficulty = $parameter->getAdditionByDifficulty();
              $parameterDifficultyChange = $parameterAdditionByDifficulty->getCurrentDifficultyIncrement();
          } while ($additionStep > 0 /* at least once even on no addition possible */ && $parameterDifficultyChange < 21) ?>
      </select>
    </label>
  </div>
<?php } ?>