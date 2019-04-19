<?php
/** @var \DrdPlus\Calculators\Theurgist\DemonWebPartsContainer $webPartsContainer */

$epicenterShift = $webPartsContainer->getTables()->getFormulasTable()->getEpicenterShift($webPartsContainer->getCurrentDemonValues()->getCurrentFormulaCode());
if ($epicenterShift === null) {
    return;
}
// formula itself can not shift epicenter so no options here
$epicenterShiftDistance = $epicenterShift->getDistance();
$epicenterShiftUnitInCzech = $epicenterShiftDistance->getUnitCode()->translateTo('cs', $epicenterShiftDistance->getValue());
?>
<div class="col">
  posun transpozicí:
    <?= ($epicenterShift->getValue() >= 0 ? '+' : '') .
    "{$epicenterShift->getValue()} ({$epicenterShiftDistance->getValue()} {$epicenterShiftUnitInCzech})" ?>
</div>