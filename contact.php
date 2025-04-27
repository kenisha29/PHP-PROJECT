<?php
include('config.php');
if (isset($_POST["send"])) {
	$name = mysqli_real_escape_string($con, post_string("name"));
	$email = mysqli_real_escape_string($con, post_string("email"));
	$mobile = mysqli_real_escape_string($con, post_string("mobile"));
	$message = mysqli_real_escape_string($con, post_string("message"));
	$qry = "INSERT INTO `contacts`(`c_name`, `c_mobile`, `c_email`, `c_message`) VALUES ('$name', '$mobile', '$email', '$message')";
	if (mysqli_query($con, $qry)) {
		header("location:index.php?s=1");
	} else {
		header("location:contact.php?s=0");
	}
	exit();
}
$page = "contact";
$name = "";
$email = "";
$mobile = "";
if (isset($_SESSION["user_id"])) {
	$row = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `users` WHERE `u_id` = " . $_SESSION["user_id"]));
	$name = $row["u_name"];
	$email = $row["u_email"];
	$mobile = $row["u_mobile"];
}
include('header.php');
?>
<!-- Start Contact -->
<div id="contact" class="contact-box">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="title-box">
					<h2>Reviews </h2>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-xs-12">
				<div class="contact-block">
					<form method="POST">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<input type="text" class="form-control" id="name" name="name" placeholder="Your Name" value="<?php echo $name; ?>" required data-error="Please enter your name">
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<input type="text" placeholder="Your Email" id="email" class="form-control" name="email" value="<?php echo $email; ?>" required data-error="Please enter your email">
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<input type="text" placeholder="Your number" id="number" class="form-control" name="mobile" value="<?php echo $mobile; ?>" required data-error="Please enter your number">
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<textarea class="form-control" name="message" id="message" placeholder="Your Message" rows="8" data-error="Write your message" required></textarea>
									<div class="help-block with-errors"></div>
								</div>
								<div class="submit-button text-center">
									<button class="btn btn-common" name="send" id="submit" type="submit">Send Message</button>
									<div id="msgSubmit" class="h3 text-center hidden"></div>
									<div class="clearfix"></div>
								</div>
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