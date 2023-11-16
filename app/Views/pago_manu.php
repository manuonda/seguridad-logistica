  
<?= $this->extend('templates/public_template'); ?>
<?= $this->section('content'); ?>
<form action="<?php echo base_url();?>/pago" method="POST">

<div class="text-center">
  <h1>TOTAL</h1>
  <h4><span>$<?php echo $total_pago; ?></span></h4>
  <!--<?php echo $preference->id ;?>-->
  <a class="btn btn-secondary" href="<?php echo base_url().'/welcome'; ?>">Volver</a>
  <!--
  <script
   src="https://www.mercadopago.com.ar/integrations/v1/web-payment-checkout.js"
   data-preference-id="<?php echo $preference->id; ?>"> 
  </script>
  URL redirect => 
-->
   <a class="btn btn-primary" href="<?php echo $preference->init_point ;?>">Pagar</a>
  <!--<?php echo $preference->init_point ?> 
  <?php echo $preference->sandbox_init_point; ?>
  -->
  </div>  
  <?= $this->endSection('content'); ?>

<?= $this->section('script'); ?>
<script type="text/javascript">
  function checkInput(event) {
    if (event.checked) {
      document.getElementById(event.id).value = true;
    } else {
      document.getElementById(event.id).value = false;
    }
  }
</script>
<?= $this->endSection('script'); ?>
