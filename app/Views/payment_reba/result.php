<div class="col-md-12">
  <h3 class="box-title">RESULTADO DE OPERACION</h3>
  <?php if ($status == APROBADO) { ?>
    <?= view('payment/success'); ?>
  <?php } ?>

  <?php if ($status == PENDIENTE) { ?>
    <?= view('payment/pending'); ?>
  <?php } ?>

  <?php if ($status == CANCELADO) { ?>
    <?= view('payment/failure'); ?>
  <?php } ?>

   <?php if ($status == "PAGO_COMISARIA") { ?>
     <?= view('payment/comisaria'); ?>
   <?php } ?>

</div>