<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kelani | User Privileges</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">


    <!-- Bootstrap Core CSS -->

    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="./css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="./css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="./font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Custom Css -->
    <link href="./css/formcss.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script type="text/javascript">

    </script>

</head>
<body>

<?php
include_once 'dbconfig.php'; //Connect to database
include_once './inc/top.php';

$row_edt = null;
if (isset($_GET['edit'])) {
    $id = trim($_GET['edit']);
    $query = "SELECT p.UserLevel_tbl_id, p.Form_tbl_FormID, f.Name, p.R, p.W, p.D FROM privileges_tbl p, form_tbl f WHERE p.UserLevel_tbl_id=" . $id . " and f.FormID = p.Form_tbl_FormID";

    $result = getData($query);
    if (mysqli_num_rows($result) > 0) {
        $row_edt = mysqli_fetch_all($result);

        $btnStatus = 'enabled';
        $btnAddStatus = 'disabled';

    } else {
        $userlevel_ = "";
        $form_ = "";
        $cbr_ = "";
        $cbw_ = "";
        $cbd_ = "";

        $btnStatus = 'disabled';
        $btnAddStatus = 'enabled';
    }
} else {
    $userlevel_ = "";
    $form_ = "";
    $cbr_ = "";
    $cbw_ = "";
    $cbd_ = "";

    $btnStatus = 'disabled';
    $btnAddStatus = 'enabled';
}
?>
?>

<div id="wrapper">
    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        User Privileges
                    </h1>
                    <ol class="breadcrumb">
                        <li>
                            <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                        </li>
                        <li class="active">
                            <i class="fa fa-bar-chart-o"></i> User Privileges
                        </li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <?php
                require_once("./config.php");
                $stmt = $db_con->prepare("SELECT * FROM privileges_tbl WHERE UserLevel_tbl_id = '" . $_SESSION['userLvl'] . "' AND Form_tbl_FormID = 'usrpri'");
                $stmt->execute();
                $permissions = $stmt->fetchAll();
                if ($permissions[0]['R']){
                ?>
                <form method="post" action="controller/userPrivilegesController.php" target="_self"
                      data-toggle="validator" id="usrpri">
                    <div class="col-lg-6">

                        <div class="form-group">
                            <label class="control-label col-md-4">User Level</label><br/>
                            <select class="form-control col-md-8" name="cmbUserLevel" id="cmbUserLevel" <?php echo $btnAddStatus; ?>>
                                <option value='0'> --Select UserLevel--</option>
                                <?php
                                include_once 'dbconfig.php';
                                $query = 'SELECT * FROM userlevel_tbl;';
                                $result = getData($query);
                                if (mysqli_num_rows($result) > 0) {
                                    // output data of each row
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $selected = $row['id'] == $row_edt[0][0] ? 'selected' : '';
                                        echo "<option " . $selected . " value='" . $row['id'] . "'>" . $row['lavel_name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div>
                            <?php if ($permissions[0]['W']) { ?>
                                <input name="btnAdd" type="submit" value="Add" class="btn btn-primary" <?php echo $btnAddStatus; ?>/>
                                <input name="btnUpdate" onclick="" type="submit" value="Update" <?php echo $btnStatus; ?>
                                       class="btn btn-primary"/>
                            <?php } else {
                                ?>
                                <input name="btnAdd" type="submit" value="Add" class="btn btn-primary" <?php echo $btnAddStatus; ?> disabled/>
                                <input name="btnUpdate" onclick="" type="submit" value="Update" class="btn btn-primary" <?php echo $btnStatus; ?> disabled/>
                                <?php
                            }
                            if ($permissions[0]['D']) {
                                ?>
                                <input name="btnDelete" type="submit" value="Delete" class="btn btn-danger" <?php echo $btnStatus; ?>/>
                            <?php } else {
                                ?>
                                <input name="btnDelete" type="submit" value="Delete" class="btn btn-danger" <?php echo $btnStatus; ?>/>
                                <?php
                            } ?>
                            <input name="btnClear" type="reset" value="Clear" class="btn btn-default"/>
                        </div>

                        <div>
                            <hr>
                            <h3>User Level List</h3>
                            <?php
                            include_once 'dbconfig.php'; //Connect to database
                            $query = "SELECT distinct p.UserLevel_tbl_id, ul.lavel_name
                                FROM  privileges_tbl p
                                INNER JOIN userlevel_tbl ul ON p.UserLevel_tbl_id = ul.id";
                            $result = getData($query);
                            echo "<table width='100%'>"; // start a table tag in the HTML
                            echo "<th>USER LEVEL</th><th>&nbsp;</th></tr>";
                            while ($row = mysqli_fetch_array($result)) {   //Creates a loop to loop through results
                                echo "<tr><td>" . $row['lavel_name'] . "</td><td><a href='userPrivileges.php?edit=" . $row['UserLevel_tbl_id'] . "'>View</a></td></tr>";  //$row['index'] the index here is a field name
                            }
                            echo "</table>"; //Close the table in HTML
                            connection_close(); //Make sure to close out the database connection
                            ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <table name="tbl_previ">
                            <tr>
                                <th>Form Name</th>
                                <th>R</th>
                                <th>W</th>
                                <th>D</th>
                            </tr>
                            <?php
                            if (!is_null($row_edt)) {
                                $i = 0;
                                foreach ($row_edt as $row) {
                                    ?>
                                    <tr>
                                        <td>
                                            <input type='text' readonly name='txtname[]' value='<?php echo $row[2]; ?>'>
                                            <input type='hidden' readonly name='txtid[]' value='<?php echo $row[1]; ?>'>
                                        </td>
                                        <td><input type='checkbox'
                                                   name='cbR<?php echo $i ?>' <?php echo ($row[3] == '1') ? 'checked' : '' ?>
                                                   value='1'></td>
                                        <td><input type='checkbox'
                                                   name='cbW<?php echo $i ?>' <?php echo ($row[4] == '1') ? 'checked' : '' ?>
                                                   value='1'></td>
                                        <td><input type='checkbox'
                                                   name='cbD<?php echo $i ?>' <?php echo ($row[5] == '1') ? 'checked' : '' ?>
                                                   value='1'></td>


                                    </tr>
                                    <?php
                                    $i++;
                                }
                            } else {
                                include_once 'dbconfig.php';
                                $result = getData("SELECT FormID, `Name` FROM form_tbl");
                                $rows = mysqli_fetch_all($result);
                                $i = 0;
                                foreach ($rows as $row) {
                                    ?>
                                    <tr>
                                        <td>
                                            <input type='text' readonly name='txtname[]' value='<?php echo $row[1]; ?>'>
                                            <input type='hidden' readonly name='txtid[]' value='<?php echo $row[0]; ?>'>
                                        </td>
                                        <td><input type='checkbox'
                                                   name='cbR<?php echo $i ?>' <?php echo ($cbr_ == '1') ? 'checked' : '' ?>
                                                   value='1'></td>
                                        <td><input type='checkbox'
                                                   name='cbW<?php echo $i ?>' <?php echo ($cbw_ == '1') ? 'checked' : '' ?>
                                                   value='1'></td>
                                        <td><input type='checkbox'
                                                   name='cbD<?php echo $i ?>' <?php echo ($cbd_ == '1') ? 'checked' : '' ?>
                                                   value='1'></td>


                                    </tr>
                                    <?php
                                    $i++;
                                }
                                connection_close(); //Make sure to close out the database connection
                            }
                            ?>
                    </div>
                    <?php } else {
                        ?>
                        <h1>You Do Not Have Permissions To This Page...!</h1>
                        <?php
                    } ?>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once './inc/footer.php'; ?>
</body>
</html>