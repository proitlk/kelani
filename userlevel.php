<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kelani | User Level</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="title" content="al_ubject">


    <?php 
	require_once './inc/header.php';
	?>
</head>
<body>

<?php

include_once './inc/top.php';
include_once 'dbconfig.php'; //Comnnect to database
?>

<div id="wrapper">
    <div id="page-wrapper">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-">
                    <h1 class="page-header">
                        User Level
                    </h1>
                    <ol class="breadcrumb">
                        <li>
                            <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                        </li>
                        <li class="active">
                            <i class="fa fa-bar-chart-o"></i> User Level
                        </li>
                    </ol>
                </div>
            </div>
            <?php
            require_once("./config.php");
            $stmt = $db_con->prepare("SELECT * FROM privileges_tbl WHERE UserLevel_tbl_id = '" . $_SESSION['userLvl'] . "' AND Form_tbl_FormID = 'usrlvl'");
            $stmt->execute();
            $permissions = $stmt->fetchAll();
            ?>
            <?php if($permissions[0]['R']){?>
            <form method="post" action="controller/userlevelController.php" data-toggle="validator" id="usrlvl">
                <div class="row">
                    <div class="col-lg-4">

                        <div class="form-group">
                            <label class="control-label col-md-4">User Level</label>
                            <input type="text" name="txtUserLevel" class="form-control col-md-8"></p>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                            <?php if($permissions[0]['W']){?>
                            <input type="submit" value="Add" name="btnAdd" class="btn-primary"/>
                            <input type="submit" value="Update" name="btnUpdate" class="btn-primary"/>
                            <?php } else {
                                ?>
                                <input type="submit" value="Add" name="btnAdd" class="btn-disabled" disabled/>
                                <input type="submit" value="Update" name="btnUpdate" class="btn-disabled" disabled/>
                                <?php
                            }
                            if($permissions[0]['D']){?>
                            <input type="submit" value="Delete" name="btnDelete" class="btn-danger"/>
                            <?php } else {
                                ?>
                                <input type="submit" value="Delete" name="btnDelete" class="btn-disabled" disabled/>
                                <?php
                            } ?>
                            <input type="reset" value="Clear" name="btnClear"  class="btn-default"/>
                            </div>
                            <div id='msg'></div>
                            </div>
                    </div>

                    <div class="col-lg-6 selecttable" id="acyeartbl">
                        <?php include './userlevel_list.php'; ?>
                    </div>
                </div>
            </form>
            <?php } else {
                ?>
                <h1>You Do Not Have Permissions To This Page...!</h1>
                <?php
            } ?>
            <!-- /al subject mng -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
<?php include_once './inc/footer.php'; ?>

<script type="text/javascript">
    $('document').ready(function () {
        $("#usrlvl").validate({
            submitHandler: submitForm
        });
        function submitForm() {
            var data = $("#usrlvl").serialize();
            $.ajax({
                type: 'POST',
                url: 'controller/userlevelController.php',
                data: data,
                beforeSend: function () {
                    $("#msg").fadeOut();
                },
                success: function (response) {
                    console.log(response);
                    if (response) {
                        $("#msg").fadeIn(function () {
                            $("#msg").html('<div class="alert alert-success"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp; Successfully Inserted...!</div>');
                        });
                        $('#msg').fadeOut(4000);
                        $('#acyeartbl').load('./userlevel_list.php');
                        $("#usrlvls")[0].reset();
                    }
                    else {
                        $("#msg").fadeIn(1000, function () {
                            $("#msg").html('<div class="alert alert-danger"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp; ' + response + ' !</div>');
                        });
                        $('#msg').fadeOut(4000);
                    }
                }
            });
            return false;
        }
    });
</script>

</body>
</html>