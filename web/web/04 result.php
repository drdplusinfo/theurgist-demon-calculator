<?php
namespace DrdPlus\TheurgistCalculator\Formulas;

use DrdPlus\Codes\Theurgist\DemonMutableParameterCode;
use DrdPlus\Codes\Units\TimeUnitCode;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\RealmsAffection;
use Granam\String\StringTools;

/** @var \DrdPlus\Calculators\Theurgist\DemonWebPartsContainer $webPartsContainer */

$currentDemon = $webPartsContainer->getCurrentDemon();
$currentDemonValues = $webPartsContainer->getCurrentDemonValues();
$resultParts = [];

if ($currentDemon->getRequiredRealm()->getValue() === $currentDemon->getEffectiveRealm()->getValue()) {
// Roman numerals are created by browser using ordered list with upper Roman list style type
    $resultParts[] = <<<HTML
sféra: {<ol class="realm font-weight-bold" start="{$currentDemon->getRequiredRealm()}"><li></ol>}
HTML;
} else {
    // Roman numerals are created by browser using ordered list with upper Roman list style type
    $resultParts[] = <<<HTML
sféra <a href="https://theurg.drdplus.info/#parametry_a_rysy_demona">původu a moci démona</a>: {<ol class="realm font-weight-bold" start="{$currentDemon->getEffectiveRealm()}"><li></ol>}
HTML;
    $resultParts[] = <<<HTML
sféra pro <a href="https://theurg.drdplus.info/#parametry_a_rysy_demona">vyvolání démona</a>: {<ol class="realm font-weight-bold" start="{$currentDemon->getRequiredRealm()}"><li></ol>}
HTML;
}

$resultParts[] = <<<HTML
náročnost: [<strong>{$currentDemon->getCurrentDifficulty()->getValue()}</strong>]
HTML;

$realmsAffections = $currentDemon->getCurrentRealmsAffections();
$realmsAffectionsInCzech = [];
/** @var RealmsAffection $realmsAffection */
foreach ($realmsAffections as $realmsAffection) {
    $realmsAffectionsInCzech[] = $realmsAffection->getAffectionPeriodCode()->translateTo('cs') . ' ' . $realmsAffection->getValue();
}
$realmAffectionName = count($realmsAffections) > 1
    ? 'náklonnosti'
    : 'náklonnost';
$realmsAffectionsResult = implode(', ', $realmsAffectionsInCzech);
$resultParts[] = <<<HTML
{$realmAffectionName}: <strong>{$realmsAffectionsResult}</strong>
HTML;

$evocation = $currentDemon->getCurrentEvocation();
$evocationTime = $evocation->getEvocationTimeBonus()->getTime();
$evocationUnitInCzech = $evocationTime->getUnitCode()->translateTo('cs', $evocationTime->getValue());
$evocationTimeResult = ($evocation->getValue() >= 0 ? '+' : '') . $evocation->getValue();
$evocationTimeResult .= " ({$evocationTime->getValue()} {$evocationUnitInCzech}";
if (($evocationTimeInMinutes = $evocationTime->findMinutes()) && $evocationTime->getUnitCode()->getValue() === TimeUnitCode::ROUND) {
    $evocationInMinutesUnitInCzech = $evocationTimeInMinutes->getUnitCode()->translateTo('cs', $evocationTimeInMinutes->getValue());
    $evocationTimeResult .= '; ' . $evocationTimeInMinutes->getValue() . ' ' . $evocationInMinutesUnitInCzech;
}
$evocationTimeResult .= ')';
$resultParts[] = <<<HTML
vyvolání démona: <strong>{$evocationTimeResult}</strong>
HTML;

$duration = $currentDemon->getCurrentDemonActivationDuration();
if ($duration !== null) {
    $durationTime = $duration->getDurationTimeBonus()->getTime();
    $durationUnitInCzech = $durationTime->getUnitCode()->translateTo('cs', $durationTime->getValue());
    $durationResult = ($duration->getValue() >= 0 ? '+' : '') . "{$duration->getValue()} ({$durationTime->getValue()} {$durationUnitInCzech})";
    $resultParts[] = <<<HTML
doba trvání: <strong>{$durationResult}</strong>
HTML;
}

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
foreach ($demonParametersWithoutUnit as $demonParameterName) {
    $parameterGetter = StringTools::assembleGetterForName($demonParameterName, 'getCurrent');
    /** @var CastingParameter $parameter */
    $parameter = $currentDemon->$parameterGetter();
    if ($parameter !== null) {
        $demonParameterCode = DemonMutableParameterCode::getIt($demonParameterName);
        if ($currentDemon->hasUnlimitedEndurance() && $demonParameterCode->is(DemonMutableParameterCode::DEMON_ENDURANCE)) {
            $parameterValueString = 'neomezená';
        } else {
            $parameterValueString = ($parameter->getValue() >= 0 ? '+' : '') . $parameter->getValue();
        }
        $resultParts[] = <<<HTML
{$demonParameterCode->translateTo('cs')}: <strong>{$parameterValueString}</strong>
HTML;
    }
}

$will = $currentDemon->getCurrentDemonWill();
if ($will !== null) {
    $willResult = ($will->getValue() >= 0 ? '+' : '') . $will->getValue();
    $resultParts[] = <<<HTML
{$will->getWill()->getCode()->translateTo('cs')}: <strong>{$willResult}</strong>
HTML;
}

$radius = $currentDemon->getCurrentDemonRadius();
if ($radius !== null) {
    $radiusNameInCzech = DemonMutableParameterCode::getIt(DemonMutableParameterCode::SPELL_RADIUS)->translateTo('cs');
    $radiusDistance = $radius->getDistanceBonus()->getDistance();
    $radiusUnitInCzech = $radiusDistance->getUnitCode()->translateTo('cs', $radiusDistance->getValue());
    $radiusResult = ($radius->getValue() >= 0 ? '+' : '') . "{$radius->getValue()} ({$radiusDistance->getValue()}
            {$radiusUnitInCzech})";
    $resultParts[] = <<<HTML
{$radiusNameInCzech}: <strong>{$radiusResult}</strong>
HTML;
}

$area = $currentDemon->getCurrentDemonArea();
if ($area !== null) {
    $areaNameInCzech = DemonMutableParameterCode::getIt(DemonMutableParameterCode::DEMON_AREA)->translateTo('cs');
    $areaDistance = $area->getDistanceBonus()->getDistance();
    $areaUnitInCzech = $areaDistance->getUnitCode()->translateTo('cs', $areaDistance->getValue());
    $areaResult = ($area->getValue() >= 0 ? '+' : '') . "{$area->getValue()} ({$areaDistance->getValue()}
            {$areaUnitInCzech})";
    $resultParts[] = <<<HTML
          {$areaNameInCzech}: <strong>{$areaResult}</strong>
HTML;
}

$spellSpeed = $currentDemon->getCurrentSpellSpeed();
if ($spellSpeed !== null) {
    $speed = $spellSpeed->getSpeedBonus()->getSpeed();
    $spellSpeedUnitInCzech = $speed->getUnitCode()->translateTo('cs', $speed->getValue());
    $resultParts[] = <<<HTML
rychlost: <strong>{$currentDemonValues->formatNumber($spellSpeed)} ({$speed->getValue()} {$spellSpeedUnitInCzech})</strong>
HTML;
}

$activationDuration = $currentDemon->getCurrentDemonActivationDuration();
if ($activationDuration !== null) {
    $activationDurationNameInCzech = DemonMutableParameterCode::getIt(DemonMutableParameterCode::DEMON_ACTIVATION_DURATION)->translateTo('cs');
    $duration = $activationDuration->getDurationTimeBonus()->getTime();
    $activationDurationUnitInCzech = $duration->getUnitCode()->translateTo('cs', $duration->getValue());
    $resultParts[] = <<<HTML
{$activationDurationNameInCzech}: <strong>{$currentDemonValues->formatNumber($activationDuration)} ({$duration->getValue()} {$activationDurationUnitInCzech})</strong>
HTML;
}

$usedDemonTraitNames = [];
foreach ($currentDemon->getDemonTraits() as $demonTrait) {
    $usedDemonTraitNames[] = $demonTrait->getDemonTraitCode()->translateTo('cs');
}
if ($usedDemonTraitNames) {
    $usedDemonTraitNamesString = implode(', ', $usedDemonTraitNames);
    $resultParts[] = <<<HTML
rysy: <strong>{$usedDemonTraitNamesString}</strong>
HTML;
}

$resultParts[] = <<<HTML
tělo: <strong>{$currentDemon->getDemonBodyCode()->translateTo('cs')}</strong>
HTML;

$resultParts[] = <<<HTML
druh: <strong>{$currentDemon->getDemonKindCode()->translateTo('cs')}</strong>
HTML;
?>

<div id="result">
  <div class="row">
      <?php
      $columnCount = 0;
      foreach ($resultParts as $resultPart) {
          if ($columnCount > 0 && $columnCount % 3 === 0) { ?>
            <div class="row">
          <?php } ?>
        <div class="col-sm-4"><?= $resultPart ?></div>
          <?php if (($columnCount + 1) % 3 === 0) { ?>
          </div>
          <?php }
          $columnCount++;
      }
      unset($columnCount);
      ?>
  </div>
</div>