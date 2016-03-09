<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kelani | Subject and Course Management</title>
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
</head><body>

<?php
include_once './inc/top.php';
include_once 'dbconfig.php'; //Connect to database

if(isset($_GET['acadamicyear'])){
    $ACADAMICYEAR = trim($_GET['acadamicyear']);
    $COURSEID = trim($_GET['course']);
    $PART = trim($_GET['part']);
    $SUBJECTID = trim($_GET['subject']);
    //var_dump($_GET);exit();




    $query = "SELECT AcadamicYear_id, Course_tbl_id, Part_table_id, Subject_tbl_id, Price, CreateDate, CreateUser, 'Status' FROM subject_course_tbl WHERE AcadamicYear_id='$ACADAMICYEAR' AND Course_tbl_id='$COURSEID' AND Part_table_id='$PART' AND Subject_tbl_id='$SUBJECTID' ";
    //var_dump($query);exit();
    //$query = "SELECT s.`subjectname` AS SubjectName,c. `Name` AS CourseName, p.`name` AS Part, sc.Price,a.`year` AS AcademicYear FROM subject_tbl AS s,course_tbl AS c , subject_course_tbl AS sc, part_tbl AS p,acadamicyear AS a WHERE a.id = sc.AcadamicYear_id AND sc.Subject_tbl_id=s.id AND sc.Course_tbl_id = c.id AND sc.Part_table_id = p.id";
    $result = getData($query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $ACADAMICYEAR = $row['AcadamicYear_id'];
            $COURSEID  = $row['Course_tbl_id'];
            $PART = $row['Part_table_id'];
            $SUBJECTID  = $row['Subject_tbl_id'];
            $FEE = $row['Price'];
            $btnStatus = 'enabled';
            $btnAddStatus = 'disabled';
        }
    }
    else{
        $ACADAMICYEAR = '';
        $COURSEID  = '';
        $PART = '';
        $SUBJECTID  = '';
        $FEE = '';
        $btnStatus = 'disabled';
        $btnAddStatus = 'enabled';
    }
}
else{
    $ACADAMICYEAR = '';
    $COURSEID  = '';
    $PART = '';
    $SUBJECTID  = '';
    $FEE = '';
    $btnStatus = 'disabled';
    $btnAddStatus = 'enabled';
}

?>
    <div id="wrapper">
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-">
                        <h1 class="page-header">
                            Subject and Course Management
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.php">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-bar-chart-o"></i> Subject and Course Management
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                <!-- cours subject mng -->
                <?php
                require_once("./config.php");
                $stmt = $db_con->prepare("SELECT * FROM privileges_tbl WHERE UserLevel_tbl_id = '" . $_SESSION['userLvl'] . "' AND Form_tbl_FormID = 'subcou'");
                $stmt->execute();
                $permissions = $stmt->fetchAll();
                ?>
                <?php if($permissions[0]['R']){?>
                <form method="post" action="controller/course_subjectController.php" data-toggle="validator" id="subcou">
                    <div class="row">
                        <div class="col-lg-4">

                            <div class="form-group">
                                <label class="control-label col-md-8">Academic Year</label><br/>
                            <select name="cmbAcademicYear" class="form-control col-md-8" value="<?php echo $ACADAMICYEAR; ?>" <?php echo $btnAddStatus; ?> required>
                            <option value='0'>        --Select Academic Year--</option>
                                <?php
                                include_once 'dbconfig.php';
                                $query = 'SELECT id, `year` FROM acadamicyear';
                                $result = getData($query);
                                if (mysqli_num_rows($result) > 0) {
                                    // output data of each row
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $selected = $row['id'] == $ACADAMICYEAR ? 'selected' : '';
                                        echo "<option ". $selected ." value='".$row['id']."'>".$row['year']."</option>";
                                    }
                                }
                                ?>
                            </select><br />
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4">Course Name</label><br/>
                            <select name="cmbCourse" class="form-control col-md-8" value="<?php echo $COURSEID; ?>" <?php echo $btnAddStatus; ?> required>
                            <option value='0'>        --Select Course--</option>
                                <?php
                                include_once 'dbconfig.php';
                                $query = 'SELECT id,`Name` FROM course_tbl';
                                $result = getData($query);
                                if (mysqli_num_rows($result) > 0) {
                                    // output data of each row
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $selected = $row['id'] == $COURSEID ? 'selected' : '';
                                        echo "<option ". $selected ." value='".$row['id']."'>".$row['Name']."</option>";
                                    }
                                }
                                ?>
                            </select><br />
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4">Part</label><br/>
                            <select name="cmbPart_DD" class="form-control col-md-8" value="<?php echo $PART; ?>" <?php echo $btnAddStatus; ?> required>
                            <option value='0'>        --Select Part--</option>
                                <?php
                                include_once 'dbconfig.php';
                                $query = 'SELECT id, `name` FROM part_tbl';
                                $result = getData($query);
                                if (mysqli_num_rows($result) > 0) {
                                    // output data of each row
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $selected = $row['id'] == $PART ? 'selected' : '';
                                        echo "<option ". $selected ." value='".$row['id']."'>".$row['name']."</option>";
                                    }
                                }
                                ?>
                            </select><br />
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4">Subject Name</label><br/>
                            <select name="cmbSubject" class="form-control col-md-8" value="<?php echo $SUBJECTID; ?>" <?php echo $btnAddStatus; ?> required>
                            <option value='0'>        --Select Subject--</option>
                                <?php
                                include_once 'dbconfig.php';
                                $query = 'SELECT * FROM subject_tbl';
                                $result = getData($query);
                                if (mysqli_num_rows($result) > 0) {
                                    // output data of each row
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $selected = $row['id'] == $SUBJECTID ? 'selected' : '';
                                        echo "<option ". $selected ." value='".$row['id']."'>".$row['subjectname']."</option>";
                                    }
                                }
                                ?>
                            </select><br />
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4">Fee</label><br/>
                            <input type="text" class="form-control col-md-8" name="txtFee" size="10" maxlength="10" value="<?php echo $FEE; ?>" required/><br/>
                            <input type="hidden" value="<?php echo ($_SESSION['user_session']=='loged')?$_SESSION['username']: 'User'; ?>" name="ssUser">
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
                            </div>
                        </div>
                    
                        <div class="col-lg-8 selecttable">
                        <?php
						include_once 'dbconfig.php'; //Connect to database
						$query = "SELECT sc.Subject_tbl_id, s.`subjectname` AS SubjectName, sc.Course_tbl_id, c. `Name` AS CourseName, sc.Part_table_id, p.`name` AS Part, sc.Price, sc.AcadamicYear_id, a.`year` AS AcademicYear
FROM subject_tbl AS s,course_tbl AS c , subject_course_tbl AS sc, part_tbl AS p,acadamicyear AS a
WHERE a.id = sc.AcadamicYear_id AND sc.Subject_tbl_id=s.id AND sc.Course_tbl_id = c.id AND sc.Part_table_id = p.id;";
						$result = getData($query);
						echo "<table width='100%'>"; // start a table tag in the HTML
						echo "<tr>
								<th>COURSE</th>
								<th>YEAR</th>
								<th>PART</th>
								<th>SUBJECT</th>
								<th>FEE</th>
								<th>&nbsp;</th>
							  </tr>";
						while($row = mysqli_fetch_array($result)){   //Creates a loop to loop through results

                                echo "<tr><td id='Course_tbl_id'>" . $row['CourseName']. "</td><td id='AcadamicYear_id'>" . $row['AcademicYear']. "</td><td id='Part_table_id'>" . $row['Part']. "</td><td id='Subject_tbl_id'>" . $row['SubjectName']. "</td><td>" . $row['Price'] . "</td><td><a href='course_subject.php?acadamicyear=".$row['AcadamicYear_id']."&course=".$row['Course_tbl_id']."&part=".$row['Part_table_id']."&subject=".$row['Subject_tbl_id']."'>Edit</a></td></tr>";  //$row['index'] the index here is a field name
						}
						echo "</table>"; //Close the table in HTML
						connection_close(); //Make sure to close out the database connection
						?>
                        </div>
                    </div>
                </form>
                <!-- /cours subject mng -->
                <?php } else {
                    ?>
                    <h1>You Do Not Have Permissions To This Page...!</h1>
                    <?php
                } ?>

            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

<?php include_once './inc/footer.php'; ?>
</body></html>