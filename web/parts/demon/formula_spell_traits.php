<?php
/** @var \DrdPlus\Calculators\Theurgist\DemonWebPartsContainer $webPartsContainer */

$spellTraits = $webPartsContainer->getTables()->getFormulasTable()->getSpellTraits($webPartsContainer->getCurrentDemonCode());
if (count($spellTraits) > 0) {
    $selectedFormulaSpellTraitValues = $webPartsContainer->getCurrentDemonValues()->getCurrentFormulaSpellTraitValues();
    $spellTraitsTable = $webPartsContainer->getTables()->getSpellTraitsTable();
    ?>
  <div class="row">
    <div class="col">
      <strong>Rysy</strong>:
        <?php foreach ($spellTraits as $spellTrait) {
            $spellTraitCode = $spellTrait->getSpellTraitCode();
            ?>
          <div class="spell-trait">
            <label>
              <input type="checkbox" name="formula_spell_traits[]"
                     value="<?= $spellTraitCode->getValue() ?>"
                     <?php if (in_array($spellTraitCode->getValue(), $selectedFormulaSpellTraitValues, true)) : ?>checked<?php endif ?>>
                <?= $spellTraitCode->translateTo('cs') ?>
                <?php
                $spellTraitDifficulty = $spellTraitsTable->getDifficultyChange($spellTraitCode);
                echo '[' . ($spellTraitDifficulty->getValue() >= 0 ? '+' : '') . $spellTraitDifficulty->getValue() . ']' ?>
                <?php $trap = $spellTraitsTable->getTrap($spellTraitCode);
                if ($trap !== null) { ?>
                  <span class="trap">(<?php echo $trap->getValue();
                      echo " {$trap->getPropertyCode()->translateTo('cs', 1)} [{$trap->getAdditionByDifficulty()}]"; ?>
                    )</span>
                <?php } ?>
            </label>
          </div>
        <?php } ?>
    </div>
  </div>
<?php } ?>