<?php
$title = 'Administration | Ajouter un article';
ob_start();
?>
<div class="row no-gutters">
	<div class="col-12 col-lg-8">
		<h1 class="admin-section-title mt-3 mt-lg-5 mx-3">Rediger un chapitre</h1>
		<hr class="mx-3 my-0"/>
		<div class="row no-gutters">
			<form method="post" class="article-form mx-3 ml-3 mr-3 mr-lg-0 ml-lg-3" action="admin/addArticle">
				<div class="input-group mb-3 mt-3">
					<span class="input-group-text">Titre</span>
					<input type="text" class="form-control" name="title" required>
				</div>
				<textarea class="ml-lg-3" id="tinymce" name="content"></textarea><br />
				<div class="text-right">
					<button type="submit" class="btn btn-dark">Valider</button>
				</div>		
			</div>
		</div>
		<aside class="d-none d-lg-block col-4 pl-5">
			<h1 class="admin-section-title mt-5 mx-3">STATUT DE L'ARTICLE</h1>
			<hr class="mx-3 my-0"/>
			<div class="row mx-2 mt-3 pr-3">
				<label class="class-form-label col-4" for="status"><b>Cet article doit être</b></label>
				<select class="form-control col-8" name="status">
					<option value="published">publié</option>
					<option value="draft">un brouillon</option>
				</select>
			</div>
			<div class="text-right pr-4 mt-3">
				<button type="submit" class="btn btn-dark">Valider</button>
			</div>
		</form>
	</aside>
</div>

<?php

$content = ob_get_clean();
require_once('template.php');

