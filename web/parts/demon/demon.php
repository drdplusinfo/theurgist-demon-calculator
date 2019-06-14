<?php
namespace DrdPlus\Calculators\Theurgist\Web;

use DrdPlus\Calculators\Theurgist\CurrentDemonValues;
use DrdPlus\Codes\Theurgist\DemonCode;

/** @var \DrdPlus\Calculators\Theurgist\DemonWebPartsContainer $webPartsContainer */
?>
  <div class="row">
    <div class="col">
      <label for="demon"><strong>Démon</strong>:
      </label>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <label>
        <select id="demon" name="<?= CurrentDemonValues::DEMON ?>">
            <?php foreach (DemonCode::getPossibleValues() as $demonValue) { ?>
              <option value="<?= $demonValue ?>"
                      <?php if ($demonValue === $webPartsContainer->getCurrentDemonCode()->getValue()){ ?>selected<?php } ?>>
                  <?= DemonCode::getIt($demonValue)->translateTo('cs') ?>
              </option>
            <?php } ?>
        </select>
      </label>
      <button type="submit">Vybrat</button>
        <?php $demonDifficulty = $webPartsContainer->getTables()->getDemonsTable()->getDifficulty($webPartsContainer->getCurrentDemonCode()); ?>
      <span>[<?= $demonDifficulty->getValue() ?>]</span>
    </div>
  </div>
    <?php
require __DIR__ . '/demon_parameters.php';
require __DIR__ . '/demon_traits.php';
