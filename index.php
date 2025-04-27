<?php
include('config.php');
$page = "home";
if (!isset($_GET["page"]) || !(get_number("page") > 0)) {
	header("location:index.php?page=1");
	exit();
}
include('header.php');
?>
<!-- Start Banner -->
<div class="ulockd-home-slider">
	<div class="container-fluid">
		<div class="row">
			<div class="pogoSlider" id="js-main-slider">
				<div class="pogoSlider-slide" data-transition="fade" data-duration="1500" style="background-image:url(images/slider4.png);">
					<div class="lbox-caption pogoSlider-slide-element">
						<div class="lbox-details">
							<h1>Welcome to online medicine store</h1>

						</div>
					</div>
				</div>
				<div class="pogoSlider-slide" data-transition="fade" data-duration="1500" style="background-image:url(images/slider.png);">
					<div class="lbox-caption pogoSlider-slide-element">
						<div class="lbox-details">
							<h1>Welcome to online medicine store</h1>

						</div>
					</div>
				</div>
				<div class="pogoSlider-slide" data-transition="fade" data-duration="1500" style="background-image:url(images/slider2.png);">
					<div class="lbox-caption pogoSlider-slide-element">
						<div class="lbox-details">
							<h1>Welcome to online medicine store</h1>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container pt-4 pb-3">
	<div class="row">
		<div class="col-lg-12">
			<div class="title-box">
				<h2>Products</h2>
			</div>
		</div>
	</div>
	<form action="index.php" method="get">
		<div class="row px-2">
			<div class="col-12 px-4">
				<div class="row font-weight-bold py-2 border border-secondary rounded">
					<input type="hidden" name="page" value="1">
					<div class="col-md-3 col-6 form-group">
						<label>Category : </label>
						<select class="form-control" id="cat" name="cat" required>
							<option value="0">All</option>
							<?php
							$cat_res = mysqli_query($con, "SELECT * FROM `categories` WHERE `cat_status` = 1");
							while ($cat = mysqli_fetch_assoc($cat_res)) {
							?>
								<option value="<?php echo $cat["cat_id"]; ?>" <?php print_selected($cat["cat_id"], get_number("cat")); ?>><?php echo $cat["cat_name"]; ?></option>
							<?php
							}
							?>
						</select>
					</div>
					<div class="col-md-3 col-6 form-group">
						<label>Company : </label>
						<select class="form-control" id="cmp" name="cmp" required>
							<option value="0">All</option>
							<?php
							$cmp_res = mysqli_query($con, "SELECT * FROM `companies` WHERE `cmp_status` = 1");
							while ($cmp = mysqli_fetch_assoc($cmp_res)) {
							?>
								<option value="<?php echo $cmp["cmp_id"]; ?>" <?php print_selected($cmp["cmp_id"], get_number("cmp")); ?>><?php echo $cmp["cmp_name"]; ?></option>
							<?php
							}
							?>
						</select>
					</div>
					<div class="col-md-3 col-12 form-group">
						<label style="user-select: none;" class="text-white d-md-block d-none">Filter Now : </label>
						<button class="btn btn-success btn-block btn-lg">Filter Now</button>
					</div>
					<div class="col-md-3 col-12 form-group">
						<label style="user-select: none;" class="text-white d-md-block d-none">Filter Now : </label>
						<button type="button" class="btn btn-secondary btn-block btn-lg" onclick="location.href='index.php?page=1&cat=0&cmp=0'">Reset</button>
					</div>
				</div>
			</div>
		</div>
	</form>
	<?php
	$qry_end = "";
	if (isset($_GET["cat"])) {
		$cat = get_number("cat");
		if ($cat > 0) {
			$qry_end .= "AND `m_cat` = '$cat' ";
		}
	}
	if (isset($_GET["cmp"])) {
		$cmp = get_number("cmp");
		if ($cmp > 0) {
			$qry_end .= "AND `m_cmp` = '$cmp' ";
		}
	}

	$med_per_page = 6;
	$med_total = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS `cnt` FROM `medicines` WHERE 1=1 $qry_end"))["cnt"];

	$med_total_pages = ceil($med_total / $med_per_page);
	$med_current_page = get_number("page", 1);
	$med_limit = " LIMIT " . (($med_current_page - 1) * $med_per_page) . ", " . $med_per_page . " ";

	$loop = array(1, $med_total_pages);
	if ($med_total_pages > 7) {
		$med_page_limit = 7;
		$avail_left = 3;
		$avail_right = 3;
		if ($med_current_page - $avail_left < 1) {
			$avail_to_shift_right = ($avail_left - $med_current_page) + 1;
			$avail_right += $avail_to_shift_right;
			$avail_left = $avail_left -  $avail_to_shift_right;
		}
		if ($med_current_page + $avail_right > $med_total_pages) {
			$avail_to_shift_left = intval(($avail_right - ($med_total_pages - $med_current_page)));
			$avail_right -= $avail_to_shift_left;
			$avail_left += $avail_to_shift_left;
		}
		$loop = array(
			($med_current_page - $avail_left),
			($med_current_page + $avail_right),
		);
	}
	?>
	<?php
	$med_res = mysqli_query($con, "SELECT * FROM `medicines`, `categories`, `companies` WHERE `m_cat` = `cat_id` AND `m_cmp` = `cmp_id` $qry_end $med_limit");

	if (mysqli_num_rows($med_res) > 0) {
	?>

		<div class="row py-4">
			<div class="col-12">
				<nav aria-label="Page navigation example">
					<ul class="pagination justify-content-center">
						<li class="page-item">
							<a class="page-link" href="index.php?page=1&cat=<?php echo get_number("cat", 0); ?>&cmp=<?php echo get_number("cmp", 0); ?>" aria-label="Previous">
								<span aria-hidden="true">&laquo;</span>
								<span class="sr-only">Previous</span>
							</a>
						</li>
						<?php
						for ($i = $loop[0]; $i <= $loop[1]; $i++) {
						?>
							<li class="page-item <?php print_active($med_current_page, $i); ?>"><a class="page-link" href="index.php?page=<?php echo $i; ?>&cat=<?php echo get_number("cat", 0); ?>&cmp=<?php echo get_number("cmp", 0); ?>"><?php echo $i; ?></a></li>
						<?php
						}
						?>
						<li class="page-item">
							<a class="page-link" href="index.php?page=<?php echo $med_total_pages; ?>&cat=<?php echo get_number("cat", 0); ?>&cmp=<?php echo get_number("cmp", 0); ?>" aria-label="Next">
								<span aria-hidden="true">&raquo;</span>
								<span class="sr-only">Next</span>
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
		<div class="row">
			<?php

			while ($med = mysqli_fetch_assoc($med_res)) {
			?>
				<div class="col-lg-4 col-md-6 col-sm-12 h-100 ">
					<div class="blog-inner border border-secondary rounded mb-md-4 mb-4">
						<div class="blog-img">
							<img class="img-fluid" style="max-width:100%; height:240px;" src="public/images/medicines/<?php print_not_empty($med["m_img"]); ?>" alt="" />
						</div>
						<div class="item-meta py-0">
							<div class="row text-white px-3 py-1 text-left" style="font-size: 16px;">
								<div class="col-7"><?php echo $med["cat_name"]; ?></div>
								<div class="col-5">Rs. <?php echo $med["m_price"]; ?></div>
							</div>
						</div>
						<h2 class="pb-0" style="max-height: 26.4px; overflow: hidden;"><?php echo $med["m_name"]; ?></h2>
						<a href="?page=1&cmp=<?php echo $med["cmp_id"]; ?> &s_id=<?php echo $med["s_id"]; ?>" class="p-0 cmp-link"><?php echo $med["cmp_name"]; ?></a>
						<div class="row p-3">
							<div class="col-6 pr-1">
								<select name="qty" id="qty-<?php echo $med["m_id"]; ?>" class="form-control border border-success">
									<?php
									$pack = $med["m_pack"];
									for ($i = 1; $i < $pack; $i++) {
									?>
										<option><?php echo $i; ?></option>
									<?php
									}
									?>
									<option selected><?php echo $pack; ?></option>
								</select>
							</div>
							<div class="col-6 pl-1">
								<button class="btn btn-block btn-lg btn-success add_to_cart" data-med="<?php echo $med["m_id"]; ?>" data-unit="#qty-<?php echo $med["m_id"]; ?>">Add to Cart</button>
							</div>
						</div>
					</div>
				</div>
			<?php
			}
			?>
		</div>
		<div class="row py-4">
			<div class="col-12">
				<nav aria-label="Page navigation example">
					<ul class="pagination justify-content-center">
						<li class="page-item">
							<a class="page-link" href="index.php?page=1&cat=<?php echo get_number("cat", 0); ?>&cmp=<?php echo get_number("cmp", 0); ?>" aria-label="Previous">
								<span aria-hidden="true">&laquo;</span>
								<span class="sr-only">Previous</span>
							</a>
						</li>
						<?php
						for ($i = $loop[0]; $i <= $loop[1]; $i++) {
						?>
							<li class="page-item <?php print_active($med_current_page, $i); ?>"><a class="page-link" href="index.php?page=<?php echo $i; ?>&cat=<?php echo get_number("cat", 0); ?>&cmp=<?php echo get_number("cmp", 0); ?>"><?php echo $i; ?></a></li>
						<?php
						}
						?>
						<li class="page-item">
							<a class="page-link" href="index.php?page=<?php echo $med_total_pages; ?>&cat=<?php echo get_number("cat", 0); ?>&cmp=<?php echo get_number("cmp", 0); ?>" aria-label="Next">
								<span aria-hidden="true">&raquo;</span>
								<span class="sr-only">Next</span>
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
		<?php
		function script()
		{
		?>
			<script>
				$(document).ready(function() {
					$(".add_to_cart").click(function() {
						var med = $(this).data("med");
						var units = $($(this).data("unit")).val();
						console.log(med);
						console.log(units);
						$.ajax({
							type: "POST",
							url: "ajax.php",
							data: {
								add:1,
								med:med,
								units:units
							},
							dataType: "text",
							success: function(resultData) {
								if(resultData == "Error"){
									console.error("Error");
								}else{
									console.log(resultData);
								}
							}
						});
					});
				});
			</script>
		<?php
		}
		?>
	<?php
	} else {
	?>
		<div class="row py-4">
			<div class="col-12 text-center my-4">
				<h1>No Medicine Found</h1>
			</div>
		</div>
	<?php
	}
	?>
</div>
<?php
include('footer.php');
?>