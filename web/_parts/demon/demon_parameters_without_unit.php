<?php declare(strict_types=1);

namespace DrdPlus\Theurgist\Formulas;

use DrdPlus\Calculators\Theurgist\CurrentDemonValues;
use DrdPlus\Codes\Theurgist\DemonMutableParameterCode;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;
use Granam\String\StringTools;

/** @var \DrdPlus\Calculators\Theurgist\DemonWebPartsContainer $webPartsContainer */

$demonParametersWithoutUnit = [
    DemonMutableParameterCode::DEMON_CAPACITY,
    DemonMutableParameterCode::DEMON_ENDURANCE,
    DemonMutableParameterCode::DEMON_QUALITY,
    DemonMutableParameterCode::DEMON_INVISIBILITY,
    DemonMutableParameterCode::DEMON_ARMOR,
    DemonMutableParameterCode::DEMON_STRENGTH,
    DemonMutableParameterCode::DEMON_AGILITY,
    DemonMutableParameterCode::DEMON_KNACK,
];
$demonsTable = $webPartsContainer->getTables()->getDemonsTable();
$currentDemon = $webPartsContainer->getCurrentDemon();
foreach ($demonParametersWithoutUnit as $parameterName) {
    $getParameter = StringTools::assembleGetterForName($parameterName);
    /** @var CastingParameter $parameter */
    $parameter = $demonsTable->$getParameter($webPartsContainer->getCurrentDemonCode());
    if ($parameter === null) {
        continue;
    }
    $demonMutableParameterCode = DemonMutableParameterCode::getIt($parameterName);
    $disabled = ($currentDemon->hasUnlimitedEndurance() && $demonMutableParameterCode->is(DemonMutableParameterCode::DEMON_ENDURANCE))
        || ($currentDemon->hasUnlimitedCapacity() && $demonMutableParameterCode->is(DemonMutableParameterCode::DEMON_CAPACITY));
    ?>
  <div class="col">
    <label><?= $demonMutableParameterCode->translateTo('cs') ?>:
        <?php
        $parameterAdditionByDifficulty = $parameter->getAdditionByDifficulty();
        $additionStep = $parameterAdditionByDifficulty->getAdditionStep();
        $optionParameterValue = $parameter->getDefaultValue(); // from the lowest
        $parameterDifficultyChange = $parameterAdditionByDifficulty->getCurrentDifficultyIncrement();
        $optionParameterChange = 0;
        $previousOptionParameterValue = null;
        $selectedParameterValue = $webPartsContainer->getCurrentDemonValues()->getCurrentDemonParameterValues()[$parameterName] ?? false;
        ?>
      <select name="<?= CurrentDemonValues::DEMON_PARAMETERS ?>[<?= $parameterName ?>]" <?php if ($disabled): ?> disabled <?php endif ?>>
          <?php
          do {
              if ($previousOptionParameterValue === null || $previousOptionParameterValue < $optionParameterValue) { ?>
                <option value="<?= $optionParameterValue ?>"
                        <?php if ($selectedParameterValue !== false && (string)$selectedParameterValue === (string)$optionParameterValue){ ?>selected<?php } ?>>
                    <?= ($optionParameterValue >= 0 ? '+' : '')
                    . "{$optionParameterValue} [{$parameterDifficultyChange}]"; ?>
                </option>
              <?php }
              $previousOptionParameterValue = $optionParameterValue;
              $optionParameterValue++;
              $optionParameterChange++;
              /** @noinspection PhpUnhandledExceptionInspection */
              $parameter = $parameter->getWithAddition($optionParameterChange);
              $parameterAdditionByDifficulty = $parameter->getAdditionByDifficulty();
              $parameterDifficultyChange = $parameterAdditionByDifficulty->getCurrentDifficultyIncrement();
          } while ($additionStep > 0 /* at least once even if no addition is possible */ && $parameterDifficultyChange < 21) ?>
      </select>
    </label>
      <?php if ($disabled) { ?>
        <input type="hidden" name="<?= CurrentDemonValues::DEMON_PARAMETERS ?>[<?= $parameterName ?>]" value="<?= $selectedParameterValue ?>">
      <?php } ?>
  </div>
<?php } ?>