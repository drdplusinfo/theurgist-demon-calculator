<?php
namespace DrdPlus\Theurgist\Formulas;

use DrdPlus\Calculators\Theurgist\CurrentDemonValues;
use DrdPlus\Codes\Theurgist\DemonMutableParameterCode;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;
use Granam\String\StringTools;

/** @var \DrdPlus\Calculators\Theurgist\DemonWebPartsContainer $webPartsContainer */

$formulaParametersWithoutUnit = [
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
foreach ($formulaParametersWithoutUnit as $parameterName) {
    $getParameter = StringTools::assembleGetterForName($parameterName);
    /** @var CastingParameter $parameter */
    $parameter = $demonsTable->$getParameter($webPartsContainer->getCurrentDemonCode());
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
        $optionParameterValue = $parameter->getDefaultValue(); // from the lowest
        $parameterDifficultyChange = $parameterAdditionByDifficulty->getCurrentDifficultyIncrement();
        $optionParameterChange = 0;
        $previousOptionParameterValue = null;
        $selectedParameterValue = $webPartsContainer->getCurrentDemonValues()->getCurrentDemonParameterValues()[$parameterName] ?? false;
        ?>
      <select name="<?= CurrentDemonValues::DEMON_PARAMETERS ?>[<?= $parameterName ?>]">
          <?php
          do {
              if ($previousOptionParameterValue === null || $previousOptionParameterValue < $optionParameterValue) { ?>
                <option value="<?= $optionParameterValue ?>"
                        <?php if ($selectedParameterValue !== false && $selectedParameterValue === $optionParameterValue){ ?>selected<?php } ?>>
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
          } while ($additionStep > 0 /* at least once even on no addition possible */ && $parameterDifficultyChange < 21) ?>
      </select>
    </label>
  </div>
<?php } ?>