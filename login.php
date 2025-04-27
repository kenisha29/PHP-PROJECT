<?php
include('config.php');
if (isset($_POST["login"])) {
	$moorem = post_string("moorem");
	$password = post_string("password");

	$qry = "SELECT * FROM `users` WHERE `u_mobile` = '$moorem' OR `u_email` = '$moorem'";

	$res = mysqli_query($con, $qry);

	if (mysqli_num_rows($res) == 1) {
		$user = mysqli_fetch_assoc($res);
		if ($user["u_password"] == $password) {
			$_SESSION["user_id"] = $user["u_id"];
			$_SESSION["user_name"] = $user["u_name"];
			header("location:index.php?s=1");
			exit();
		}else{
			header("location:login.php?s=0");
		}
	} else {
		header("location:login.php?s=0");
	}

	exit($moorem . " - " . $password);
}
$page = "login";
include('header.php');
?>
<!-- Start Contact -->
<div id="contact" class="contact-box">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="title-box">
					<h2>Login</h2>
					<p>Start Your Session Here</p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 offset-md-4">
				<div class="contact-block">
					<form method="POST">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<input type="text" class="form-control" id="moorem" name="moorem" placeholder="Email id or Mobile Number" autocomplete="off">
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<input type="password" placeholder="Password" id="password" class="form-control" name="password" autocomplete="off">
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="submit-button text-center">
									<button class="btn btn-common" name="login" id="login" type="submit">Login</button>
									<div id="msgSubmit" class="h3 text-center hidden"></div>
									<div class="clearfix"></div>
								</div>
							</div>
							<div class="col-md-12 text-center pt-4 font-weight-bold">
								Or Register Using <br>
								<a href="register.php" class="text-primary">REGISTER</a>
							</div>
						</div>
					</form>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- End Contact -->
<?php
include('footer.php');
?>