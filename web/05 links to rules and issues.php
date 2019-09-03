<?php declare(strict_types=1);

use Granam\String\StringTools;

?>

<div class="row">
  <hr class="col">
</div>
<div class="row">
  <div class="col">
      <?php
      /** @var \DrdPlus\Calculators\Theurgist\DemonWebPartsContainer $webPartsContainer */
      $demonCzechName = $webPartsContainer->getCurrentDemon()->getDemonCode()->translateTo('cs');
      $demon_hash = StringTools::toSnakeCaseId($demonCzechName);
      if (strpos($demon_hash, 'demon') !== 0) {
          $demon_hash = 'demon_' . $demon_hash;
      }
      ?>
    <div class="name">
      <a href="https://theurg.drdplus.info/?trial=1#<?= $demon_hash ?>"><?= $demonCzechName ?></a>
    </div>
    <div>
      <a href="https://theurg.drdplus.info/?trial=1#abecedni_seznam_demonu">Abecední seznam démonů</a>
    </div>
  </div>
  <div class="col">
      <?= /** @var \DrdPlus\Calculators\Theurgist\DemonWebPartsContainer $webPartsContainer */
      $webPartsContainer->getCalculatorDebugContactsBody(); ?>
  </div>
</div>