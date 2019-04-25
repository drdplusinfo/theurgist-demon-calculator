<?php
namespace DrdPlus\Calculators\Theurgist\Web;

use DrdPlus\Calculators\Theurgist\CurrentDemonValues;
use DrdPlus\Codes\Theurgist\FormulaCode;

/** @var \DrdPlus\Calculators\Theurgist\DemonWebPartsContainer $webPartsContainer */
?>
  <div class="row">
    <div class="col">
      <label for="formula"><strong>Formule</strong>:
      </label>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <label>
        <select id="demon" name="<?= CurrentDemonValues::DEMON ?>">
            <?php foreach (FormulaCode::getPossibleValues() as $formulaValue) { ?>
              <option value="<?= $formulaValue ?>"
                      <?php if ($formulaValue === $webPartsContainer->getCurrentDemonCode()->getValue()){ ?>selected<?php } ?>>
                  <?= FormulaCode::getIt($formulaValue)->translateTo('cs') ?>
              </option>
            <?php } ?>
        </select>
      </label>
      <button type="submit">Vybrat</button>
        <?php $formulaDifficulty = $webPartsContainer->getTables()->getDemonsTable()->getDifficulty($webPartsContainer->getCurrentDemonCode()); ?>
      <span>[<?= $formulaDifficulty->getValue() ?>]</span>
    </div>
  </div>
    <?php
require __DIR__ . '/demon_parameters.php';
require __DIR__ . '/demon_traits.php';
