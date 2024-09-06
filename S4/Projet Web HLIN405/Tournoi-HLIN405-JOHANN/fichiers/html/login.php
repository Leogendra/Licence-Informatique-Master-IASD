<?php 
ph_set_redirect();
$form = new PH\Templates\BootstrapForm(ph_include('forms/login.php'), ph_get_validation_result()); 
?>

<div class="row align-items-center">
	<div class="col align-self-center">	
		<div class="row">
			<h2>Connexion</h2>
		</div>
		<?php $form->render(); ?>
		<div class="row mb-3">
			<div class="d-flex justify-content-center links">
				<p>Pas encore de compte ? &nbsp; </p><a href="<?php echo ph_get_route_link('register.php'); ?>">Inscrivez-vous</a>
			</div>
			<div class="d-flex justify-content-center">
				<a href="#">Mot de passe oublié ?</a>
			</div>
		</div>
	</div>
</div>