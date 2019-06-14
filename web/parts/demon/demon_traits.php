<?php
use DrdPlus\Calculators\Theurgist\CurrentDemonValues;

/** @var \DrdPlus\Calculators\Theurgist\DemonWebPartsContainer $webPartsContainer */
$demonTraits = $webPartsContainer->getTables()->getDemonsTable()->getDemonTraits($webPartsContainer->getCurrentDemonCode());
if (!$demonTraits) {
    return '';
}
$currentDemonTraitValues = $webPartsContainer->getCurrentDemonValues()->getCurrentDemonTraitValues();
$demonTraitsTable = $webPartsContainer->getTables()->getDemonTraitsTable();
?>
<div class="row">
  <div class="col">
    <strong>Rysy</strong>:
      <?php foreach ($demonTraits as $demonTrait) {
          $demonTraitCode = $demonTrait->getDemonTraitCode();
          ?>
        <div class="spell-trait">
          <label>
            <input type="checkbox" name="<?= CurrentDemonValues::DEMON_TRAITS ?>[]"
                   value="<?= $demonTraitCode->getValue() ?>"
                   <?php if (in_array($demonTraitCode->getValue(), $currentDemonTraitValues, true)) : ?>checked<?php endif ?>>
              <?= $demonTraitCode->translateTo('cs') ?> {<ol class="realm" start="<?= $demonTrait->getRequiredRealm()->getValue() ?>"><li></ol>}
          </label>
        </div>
      <?php } ?>
  </div>
</div>