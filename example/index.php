<?php
require dirname(__DIR__)."/bootstrap.php";
require "Form.php";

$start = microtime(true);
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>HtmlNode Form Example</title>
		<link rel="stylesheet" href="http://twitter.github.com/bootstrap/assets/css/bootstrap.css" type="text/css">
	</head>
	<body>
		<section class="container">
			<fieldset>
			
				<legend>Registration</legend>
				
				<div>
					<label>Username</label>
					<?= Form::input("username") ?>
				</div>
				
				<div>
					<label>Email</label>
					<?= Form::email("email", null, ["placeholder" => "A valid email address"]) ?>
				</div>

				<div>
					<label>password</label>
					<?= Form::password("password")->attr("required", true) ?>
				</div>
				
				<div>
					<label>Birthday Year</label>
					<div>
						<?= Form::select("birthday", 10, range(1990, 2000))->data("selection", ["date" => true, "year" => true]) ?>
				</div>

				<div>
					<?= Form::checkboxLabel("newsletter", "Subscribe to our newsletter")->addClass("checkbox") ?>
				</div>

				<div class="form-actions">
					<?= Form::submit("submit", "Submit", ["class" => "btn"])->css(["color" => "gray"]) ?>
				</div>
				
				<hr />
				
				<label>Page generated in <code><?= round(microtime(true) - $start, 4) ?>s</code></label>
			
			</fieldset>
		</section>
	</body>
</html>

