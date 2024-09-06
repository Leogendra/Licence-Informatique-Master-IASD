<form action="<?php echo ph_get_route_link('validation/login.php'); ?>" target="_self" method="POST" autocomplete="on">
		<div class="form-floating mb-3">
			<input autofocus type="email" class="form-control" id="email" placeholder="name@example.com" name="email">
			<label for="email">Adresse e-mail</label>
		</div>
		<div class="form-floating mb-3">
			<input type="password" class="form-control" id="password" placeholder="Password" name="password">
			<label for="password">Mot de passe</label>
		</div>
	<button class="btn btn-primary" type="submit">Envoyer</button>
</form>