<?php
include('config.php');
if (isset($_GET["order"])) {
    $oid = get_number("order");
    $uid = $_SESSION["user_id"];
    $res = mysqli_query($con, "SELECT * FROM orders o JOIN orderstatus os on o.o_status = os.os_id WHERE `o_id` = '$oid' AND `o_user` = '$uid'");
    if (mysqli_num_rows($res) == 1) {
        $order = mysqli_fetch_assoc($res);
    } else {
        header("location:index.php?s=0");
    }
} else {
    header("location:index.php?s=0");
}
$page = "my-orders";
include('header.php');
?>
<div class="container" id="printable">
    <div class="row printable_hide">
        <div class="col-lg-12">
            <div class="title-box">
                <h2>My Orders</h2>
                <p>Here you Can see Recent Orders </p>
            </div>
        </div>
    </div>
    <div class="row pb-5">
        <div class="col-12">
            <div class="card" >
                <div class="card-header pb-1 pt-3">
                    <div class="row">
                        <div class="col-3">
                            <h2 class=" font-weight-bold">ORDER ID <span class="float-right">:</span></h2>
                        </div>
                        <div class="col-3">
                            <h2 class=" font-weight-bold"><?php echo $order["o_id"]; ?></h2>
                        </div>
                        <div class="col-3">
                            <h2 class=" font-weight-bold">ORDER DATE <span class="float-right">:</span></h2>
                        </div>
                        <div class="col-3">
                            <h2 class=" font-weight-bold"><?php echo date("d-m-Y", strtotime($order["o_date"])); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-2">
                            <h2 class="font-weight-bold">Name </h2>
                        </div>
                        <div class="col-1 text-right">
                            <h2 class="font-weight-bold"> : </h2>
                        </div>
                        <div class="col-9">
                            <h2 class="font-weight-bold"><?php echo $order["o_name"]; ?></h2>
                        </div>
                        <div class="col-2">
                            <h2 class="font-weight-bold">Mobile </h2>
                        </div>
                        <div class="col-1 text-right">
                            <h2 class="font-weight-bold"> : </h2>
                        </div>
                        <div class="col-9">
                            <h2 class="font-weight-bold"><?php echo $order["o_mobile"]; ?></h2>
                        </div>
                        <div class="col-2">
                            <h2 class="font-weight-bold">Email </h2>
                        </div>
                        <div class="col-1 text-right">
                            <h2 class="font-weight-bold"> : </h2>
                        </div>
                        <div class="col-9">
                            <h2 class="font-weight-bold"><?php echo $order["o_email"]; ?></h2>
                        </div>
                        <div class="col-2">
                            <h2 class="font-weight-bold">Pincode </h2>
                        </div>
                        <div class="col-1 text-right">
                            <h2 class="font-weight-bold"> : </h2>
                        </div>
                        <div class="col-9">
                            <h2 class="font-weight-bold"><?php echo $order["o_pincode"]; ?></h2>
                        </div>
                        <div class="col-2">
                            <h2 class="font-weight-bold">Address </h2>
                        </div>
                        <div class="col-1 text-right">
                            <h2 class="font-weight-bold"> : </h2>
                        </div>
                        <div class="col-9">
                            <h2 class="font-weight-bold"><?php echo nl2br($order["o_address"]); ?></h2>
                        </div>
                        <div class="col-2">
                            <h2 class="font-weight-bold">Status </h2>
                        </div>
                        <div class="col-1 text-right">
                            <h2 class="font-weight-bold"> : </h2>
                        </div>
                        <div class="col-9">
                            <h2 class="font-weight-bold"><?php echo $order["os_name"]; ?></h2>
                        </div>
                        <div class="col-12 mt-4">
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
                            $order["o_medicines"] = json_decode($order["o_medicines"], true);
                            foreach ($order["o_medicines"] as $med) {
                            ?>
                                <div class="card my-3 border border-secondary">
                                    <div class="card-body py-3">
                                        <div class="row">
                                            <div class="col-md-6 my-auto">
                                                <h2 class="font-weight-bold pb-md-0 pb-3"><?php echo mysqli_fetch_array(mysqli_query($con, "SELECT `m_name` FROM `medicines` WHERE `m_id` = " . $med["id"]))[0]; ?></h2>
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
                                                <h2 class="font-weight-bold pb-0 total"><?php echo $med["total"]; ?></h2>
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
                                            <h1 class="font-weight-bold pb-0"><?php echo count($order["o_medicines"]); ?> Medicines</h1>
                                        </div>
                                        <div class="col-md-2 col-6">
                                            <h1 class="font-weight-bold pb-0">Total</h1>
                                        </div>
                                        <div class="col-md-2 col-6">
                                            <h1 class="font-weight-bold pb-0" id="total"><?php echo $order["o_total"]; ?>/-</h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 printable_hide text-center pt-5">
            <button class="btn btn-outline-secondary btn-lg" onclick="location.href='my-orders.php'">Back to My Orders</button>
            <button class="btn btn-success btn-lg" onclick="window.print();">Print</button>
        </div>
        <style>
            @media print {
                body * {
                    visibility: hidden ;
                }

                #printable,
                #printable * {
                    visibility: visible ;
                }

                #printable {
                    position: absolute;
                    left: 0;
                    top: 0;
                }
                .printable_hide, .printable_hide *{
                    visibility: hidden;
                    display: none;
                }
            }
        </style>
    </div>
</div>
<?php
include('footer.php');
?>