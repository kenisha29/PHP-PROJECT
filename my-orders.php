<?php
include('config.php');
if(isset($_GET["cancel"])){
    $oid = get_number("cancel");
    mysqli_query($con, "UPDATE `orders` SET `o_status` = '5' WHERE `o_id` = $oid AND `o_status` = 1");
    header("location:my-orders.php?s=1");
}
$page = "my-orders";
include('header.php');
?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="title-box">
                <h2>My Orders</h2>
                <p>Here you Can see Recent Orders </p>
            </div>
        </div>
    </div>
    <div class="row pb-5">
        <div class="col-12">
            <div class="card bg-secondary d-lg-block d-none">
                <div class="card-body py-3">
                    <div class="row">
                        <div class="col-1">
                            <h2 class="font-weight-bold pb-0 text-white">No.</h2>
                        </div>
                        <div class="col-2">
                            <h2 class="font-weight-bold pb-0 text-white">Date</h2>
                        </div>
                        <div class="col-4">
                            <h2 class="font-weight-bold pb-0 text-white">Medicines</h2>
                        </div>
                        <div class="col-5">
                            <div class="row">
                                <div class="col-4">
                                    <h2 class="font-weight-bold pb-0 text-white">Total</h2>
                                </div>
                                <div class="col-4">
                                    <h2 class="font-weight-bold pb-0 text-white">Status</h2>
                                </div>
                                <div class="col-4">
                                    <h2 class="font-weight-bold pb-0 text-white">Actions</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $user_id = $_SESSION["user_id"];
            $ord_res = mysqli_query($con, "SELECT o_id , o_date , o_medicines,o_total, os_name, o_status FROM `orders` o left join `orderstatus` os ON o.o_status = os.os_id WHERE `o_status` != 0 AND `o_user` = '$user_id' ORDER BY `o_id` DESC LIMIT 10");
            while ($ord = mysqli_fetch_assoc($ord_res)) {
            ?>
                <div class="card my-3 border border-secondary">
                    <div class="card-body py-3">
                        <div class="row">
                            <div class="col-lg-1 col-6 my-auto">
                                <span class="d-lg-none hide-and-seek">Order No.</span>
                                <h2 class="font-weight-bold pb-0"><?php echo $ord["o_id"]; ?></h2>
                            </div>
                            <div class="col-lg-2 col-6 my-auto">
                                <span class="d-lg-none hide-and-seek">Date</span>
                                <h2 class="font-weight-bold pb-0"><?php echo date("d-m-Y", strtotime($ord["o_date"])); ?></h2>
                            </div>
                            <div class="col-lg-4 my-auto">
                                <span class="d-lg-none hide-and-seek">Medicines</span>
                                <?php
                                $ord["o_medicines"] = json_decode($ord["o_medicines"], true);
                                foreach ($ord["o_medicines"] as $med) {
                                ?>
                                    <h2 class="font-weight-bold pb-0"><?php echo mysqli_fetch_array(mysqli_query($con, "SELECT `m_name` FROM `medicines` WHERE `m_id` = " . $med["id"]))[0]; ?> (<?php echo $med["units"]; ?>)</h2>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="col-lg-5">
                                <div class="row">
                                    <div class="col-lg-4 col-6 my-auto">
                                        <span class="d-lg-none hide-and-seek">Total</span>
                                        <h2 class="font-weight-bold pb-0"><?php echo $ord["o_total"]; ?></h2>
                                    </div>
                                    <div class="col-lg-4 col-6 my-auto">
                                        <span class="d-lg-none hide-and-seek">Status</span>
                                        <h2 class="font-weight-bold pb-0"><?php echo $ord["os_name"]; ?></h2>
                                    </div>
                                    <div class="col-lg-4 my-auto">
                                        <span class="hide-and-seek d-lg-none">Actions</span>
                                        <div class="row">
                                            <div class="col-lg-12 <?php if ($ord["o_status"] == "1") {
                                                                        echo "col-6 pb-3";
                                                                    } ?>">
                                                <button class="btn btn-success btn-block" onclick="location.href='print.php?order=<?php echo $ord['o_id']; ?>'">Print</button>
                                            </div>
                                            <?php
                                            if ($ord["o_status"] == "1") {
                                            ?>
                                                <div class="col-lg-12 col-6">
                                                    <button class="btn btn-danger btn-block" onclick="location.href='my-orders.php?cancel=<?php echo $ord['o_id']; ?>'">Cancel</button>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<!-- End Contact -->
<?php
include('footer.php');
?>