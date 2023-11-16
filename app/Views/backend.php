<?php echo view('templates/backend-base/header.php'); ?>
<?php echo view('templates/backend-base/navbar.php'); ?>
<?php echo view($contenido); ?>
<?php echo view('templates/backend-base/footer.php'); ?>
<script type="text/javascript">
 const base_url = '<?php echo base_url(); ?>';
</script>
