<?php
include("./config.php");
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
$page = "my-cart";
include("./header.php");
?>
<div class="container pt-4 pb-3">
    <div class="row">
        <div class="col-lg-12">
            <div class="title-box">
                <h2>My Cart</h2>
            </div>
        </div>
    </div>
    <?php
    if (count($cart_items) > 0) {
    ?>
        <div class="row">
            <div class="col-12">
                <div class="card bg-secondary d-md-block d-none">
                    <div class="card-body py-3">
                        <div class="row ">
                            <div class="col-4">
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
							
                            <div class="col-2">
                                <h2 class="font-weight-bold pb-0 text-white">Action</h2>
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
                                <div class="col-md-4 my-auto">
                                    <h2 class="font-weight-bold pb-md-0 pb-3"><?php echo $med["name"]; ?></h2>
                                </div>
                                <div class="col-md-2 col-6 my-auto">
                                    <span class="d-md-none hide-and-seek">Price</span>
                                    <h2 class="font-weight-bold pb-0"><?php echo $med["price"]; ?></h2>
                                </div>
                                <div class="col-md-2 col-6 my-auto">
                                    <span class="d-md-none hide-and-seek">Units</span>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text btn bg-danger text-white font-weight-bold btn-unit-change" data-change="-1" data-unit="#unit-<?php echo $med["id"]; ?>">-</div>
                                        </div>
                                        <input type="number" id="unit-<?php echo $med["id"]; ?>" class="form-control px-1 text-center no-spinner unit" placeholder="Units" value="<?php echo $med["units"]; ?>" data-med="<?php echo $med["id"]; ?>" data-price="<?php echo $med["price"]; ?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text text-white font-weight-bold btn bg-success btn-unit-change" data-change="1" data-unit="#unit-<?php echo $med["id"]; ?>">+</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 col-6 my-auto">
                                    <span class="d-md-none hide-and-seek">Total</span>
                                    <h2 class="font-weight-bold pb-0 total" id="total-<?php echo $med["id"]; ?>"><?php echo $med["total"];
                                                                                                                    $total += $med["total"]; ?></h2>
                                </div>

                                <div class="col-md-2 col-6 my-auto">
                                    <span class="d-md-none hide-and-seek">Action</span><br class="d-md-none">
                                    <button type="button" id="del-<?php echo $med["id"]; ?>" class="btn btn-danger delete-med" data-med="<?php echo $med["id"]; ?>"><i class="fa fa-trash"></i></button>
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
                            <div class="col-md-6 mb-md-0 mb-2">
                            <h1 class="font-weight-bold pb-0"><?php echo count($cart_items); ?> Medicines</h1>
                            </div>
                            <div class="col-md-2 col-6">
                                <h1 class="font-weight-bold pb-0">Total</h1>
                            </div>
                            <div class="col-md-4 col-6">
                                <h1 class="font-weight-bold pb-0" id="total"><?php echo $total; ?>/-</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-center pt-3 pb-4">
                <button class="btn btn-success btn-lg" onclick="location.href='checkout.php'">Proceeded to Checkout</button>
            </div>
        </div>
        <?php
        function script()
        {
        ?>
            <script>
                function update_total() {
                    var total = 0;
                    $(".total").each(function() {
                        total += parseFloat($(this).html());
                    });
                    $("#total").html((Math.round(total * 100) / 100) + "/-");
                }
                $(document).ready(function() {
                    $(".unit").change(function() {
                        var med = $(this).data("med");
                        var price = $(this).data("price");
						var s_id = $(this).data("s_id");
                        var units = $(this).val();
                        if (units <= 0) {
                            $("#del-"+med).click();
                        } else {
                            $.ajax({
                                type: "POST",
                                url: "ajax.php",
                                data: {
                                    update: 1,
                                    med: med,
                                    units: units,
									s_id = s_id
                                },
                                dataType: "text",
                                success: function(resultData) {
                                    if (resultData == "Error") {
                                        console.error("Error");
                                    } else {
                                        $("#total-" + med).html((Math.round((parseFloat(price) * parseInt(units) * 100)) / 100));
                                        update_total();
                                    }
                                }
                            });
                        }
                    });
                    $(".btn-unit-change").click(function() {
                        var change = parseInt($(this).data("change"));
                        var $unit = $($(this).data("unit"));
                        var current = parseInt($unit.val());
                        if ((current + change) <= 0) {
                            // "Delete Item"
                        } else {
                            $unit.val(current + change);
                            $unit.change();
                        }
                    });
                    $(".delete-med").click(function() {
                        var med = $(this).data("med");
                        var $btn = $(this);
                        $.ajax({
                            type: "POST",
                            url: "ajax.php",
                            data: {
                                delete: 1,
                                med: med,
                            },
                            dataType: "text",
                            success: function(resultData) {
                                if (resultData == "1") {
                                    console.log("Deleted");
                                    console.log($btn.parent().parent().parent().parent().remove());;
                                    update_total();
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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body py-3 text-center py-5">
                        <h1 class="font-weight-bold pb-0">No Items in Cart</h1>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</div>
<?php
include("./footer.php");
?>