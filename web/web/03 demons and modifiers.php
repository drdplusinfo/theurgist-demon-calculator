<?php /** @var \DrdPlus\Calculators\Theurgist\DemonWebPartsContainer $webPartsContainer */ ?>
<div class="row">
  <hr class="col">
</div>
<form id="configurator" action="" method="get">
  <input type="hidden" name="<? DrdPlus\Calculators\Theurgist\CurrentDemonValues::PREVIOUS_DEMON ?>"
         value="<?= $webPartsContainer->getCurrentDemonCode()->getValue() ?>">
    <?php require __DIR__ . '/../parts/demon/demon.php'; ?>
  <div class="row">
    <hr class="col">
  </div>
  <button type="submit">Vybrat</button>
</form>