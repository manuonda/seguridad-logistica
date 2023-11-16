<div class="container" style="padding-top: 70px">
    <div class="col-md-12" <?php if($ua->isMobile()): ?>style="padding-left: 0px; padding-right: 0px;"<?php endif; ?>>
    	<div class="card card-outline-secondary">
			<div class="card-header text-center">
				<h4 class="mb-0">
                	<font color= "blue">
                	Alerta
                	</font>
                </h4>
			</div>
    		<div class="card-body text-center">
                <div class="alert alert-danger text-center">
                    <b><?php echo $mensaje; ?></b>
                </div>
            </div>
            <div class="text-center">
                <a href="<?php echo base_url().'/' ?>" class="btn btn-primary" type="button" id="btnVolver"><span class="oi oi-home"></span> Voler</a><br/><br/>
            </div>
        </div>
    </div>
</div>
<?php echo view('templates/frontend-base/footer.php'); ?>