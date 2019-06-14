<?php
namespace DrdPlus\TheurgistCalculator\Formulas;

use DrdPlus\Codes\Theurgist\ModifierMutableParameterCode;
use DrdPlus\Codes\Units\TimeUnitCode;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\RealmsAffection;

/** @var \DrdPlus\Calculators\Theurgist\DemonWebPartsContainer $webPartsContainer */

$currentDemon = $webPartsContainer->getCurrentDemon();
$currentDemonValues = $webPartsContainer->getCurrentDemonValues();
$resultParts = [];

// Roman numerals are created by browser using ordered list with upper Roman list style type
$resultParts[] = <<<HTML
sféra: <ol class="realm font-weight-bold" start="{$currentDemon->getRequiredRealm()}">
        <li>
      </ol>
HTML;

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
vyvolání (příprava démona): <strong>{$evocationTimeResult}</strong>
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

$strength = $currentDemon->getCurrentDemonStrength();
if ($strength !== null) {
    $strengthResult = ($strength->getValue() >= 0 ? '+' : '') . $strength->getValue();
    $resultParts[] = <<<HTML
{$strength->getStrength()->getCode()->translateTo('cs')}: <strong>{$strengthResult}</strong>
HTML;
}

$agility = $currentDemon->getCurrentDemonAgility();
if ($agility !== null) {
    $agilityResult = ($agility->getValue() >= 0 ? '+' : '') . $agility->getValue();
    $resultParts[] = <<<HTML
{$strength->getStrength()->getCode()->translateTo('cs')}: <strong>{$agilityResult}</strong>
HTML;
}

$knack = $currentDemon->getCurrentDemonKnack();
if ($knack !== null) {
    $knackResult = ($knack->getValue() >= 0 ? '+' : '') . $knack->getValue();
    $resultParts[] = <<<HTML
{$knack->getKnack()->getCode()->translateTo('cs')}: <strong>{$knackResult}</strong>
HTML;
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
    $radiusNameInCzech = ModifierMutableParameterCode::getIt(ModifierMutableParameterCode::SPELL_RADIUS)->translateTo('cs');
    $radiusDistance = $radius->getDistanceBonus()->getDistance();
    $radiusUnitInCzech = $radiusDistance->getUnitCode()->translateTo('cs', $radiusDistance->getValue());
    $radiusResult = ($radius->getValue() >= 0 ? '+' : '') . "{$radius->getValue()} ({$radiusDistance->getValue()}
            {$radiusUnitInCzech})";
    $resultParts[] = <<<HTML
          {$radiusNameInCzech}: <strong>{$radiusResult}</strong>
HTML;
}

$spellSpeed = $currentDemon->getCurrentSpellSpeed();
if ($spellSpeed !== null) {
    $speed = $spellSpeed->getSpeedBonus()->getSpeed();
    $spellSpeedUnitInCzech = $speed->getUnitCode()->translateTo('cs', $speed->getValue());
    $resultParts[] = <<<HTML
rychlost: {$currentDemonValues->formatNumber($spellSpeed)} ({$speed->getValue()} {$spellSpeedUnitInCzech})
HTML;
}