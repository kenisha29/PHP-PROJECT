<?php
include("./config.php");
if (!isset($_SESSION["user_id"])) {
    header("location:login.php");
}
$cart = $_SESSION["cart"];
$cart[0] = 0;
$med_ids = array_keys($cart);
$med_ids_string = "(" . implode(",", $med_ids) . ")";
$mes_res = mysqli_query($con, "SELECT * FROM `medicines` WHERE `m_id` IN $med_ids_string");
$cart_items = array();
if (mysqli_num_rows($mes_res) > 0) {
    while ($med = mysqli_fetch_assoc($mes_res)) {
        $cart_items[] = array(
            "id" => $med["m_id"],
            "name" => $med["m_name"],
            "units" => $cart[$med["m_id"]],
            "price" => $med["m_price"],
            "total" => (floatval($cart[$med["m_id"]]) * floatval($med["m_price"])),
			"s_id" => $med["s_id"],
        );
    }
}
if (count($cart_items) == 0) {
    header("location:index.php");
    exit();
} else {
    $user = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `users` WHERE `u_id` = " . $_SESSION["user_id"]));
}
$page = "my-cart";
include("./header.php");
?>
<div class="container pt-4 pb-3">
    <form action="place.php" method="post">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-box">
                    <h2>Checkout</h2>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <div class="card border border-secondary">
                    <div class="card-body pb-1">
                        <div class="row">
                            <div class="col-12">
                                <h1 class="font-weight-bold">Customer Details:</h1>
                            </div>
                            <div class="col-12 form-group">
                                <label>Name:</label>
                                <input type="text" name="name" id="name" value="<?php echo $user["u_name"]; ?>" class="form-control border border-secondary">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Mobile:</label>
                                <input type="text" name="mobile" id="mobile" value="<?php echo $user["u_mobile"]; ?>" class="form-control border border-secondary">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Email:</label>
                                <input type="text" name="email" id="email" value="<?php echo $user["u_email"]; ?>" class="form-control border border-secondary">
                            </div>
                            <div class="col-12 form-group">
                                <label>Pincode:</label>
                                <input type="text" name="pincode" id="pincode" maxlength="6" value="<?php echo $user["u_pincode"]; ?>" class="form-control border border-secondary">
                            </div>
                            <div class="col-12   form-group">
                                <label>Address:</label>
                                <textarea name="address" id="address" class="form-control border border-secondary"><?php echo $user["u_address"]; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card bg-secondary d-md-block d-none">
                    <div class="card-body py-3">
                        <div class="row ">
                            <div class="col-6">
                                <h2 class="font-weight-bold pb-0 text-white">Medicine Name</h2>
                            </div>
                            <div class="col-2">
                                <h2 class="font-weight-bold pb-0 text-white">Price</h2>
                            </div>
                            <div class="col-2">
                                <h2 class="font-weight-bold pb-0 text-white">Units</h2>
                            </div>
                            <div class="col-2">
                                <h2 class="font-weight-bold pb-0 text-white">Total</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $total = 0;
                foreach ($cart_items as $med) {
                ?>
                    <div class="card my-3 border border-secondary">
                        <div class="card-body py-3">
                            <div class="row">
                                <div class="col-md-6 my-auto">
                                    <h2 class="font-weight-bold pb-md-0 pb-3"><?php echo $med["name"]; ?></h2>
                                </div>
                                <div class="col-md-2 col-4 my-auto">
                                    <span class="d-md-none hide-and-seek">Price</span>
                                    <h2 class="font-weight-bold pb-0"><?php echo $med["price"]; ?></h2>
                                </div>
                                <div class="col-md-2 col-4 my-auto">
                                    <span class="d-md-none hide-and-seek">Units</span>
                                    <h2 class="font-weight-bold pb-0"><?php echo $med["units"]; ?></h2>
                                </div>
                                <div class="col-md-2 col-4 my-auto">
                                    <span class="d-md-none hide-and-seek">Total</span>
                                    <h2 class="font-weight-bold pb-0 total" id="total-<?php echo $med["id"]; ?>"><?php echo $med["total"];
                                                                                                                    $total += $med["total"]; ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
                <div class="card my-3 border border-secondary">
                    <div class="card-body py-3">
                        <div class="row">
                            <div class="col-md-8 mb-md-0 mb-2">
                                <h1 class="font-weight-bold pb-0"><?php echo count($cart_items); ?> Medicines</h1>
                            </div>
                            <div class="col-md-2 col-6">
                                <h1 class="font-weight-bold pb-0">Total</h1>
                            </div>
                            <div class="col-md-2 col-6">
                                <h1 class="font-weight-bold pb-0" id="total"><?php echo $total; ?>/-</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-center pt-3 pb-4">
                <button class="btn btn-outline-secondary btn-lg mr-2" onclick="location.href='my-cart.php'">Back to Cart</button>
                <button type="submit" class="btn btn-success btn-lg ml-2" place.php>Place Order</button>
            </div>
        </div>
    </form>
</div>
<?php
include("./footer.php");
?>