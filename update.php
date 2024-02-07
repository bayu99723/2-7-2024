<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$judul = $isi = $kategori = "";
$judul_err = $isi_err = $kategori_err ="";
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate isi
    $input_judul = trim($_POST["judul"]);
    if(empty($input_judul)){
        $judul_err = "Please enter an judul.";
    } else{
        $judul = $input_judul;
    }
    
    // Validate isi
    $input_isi = trim($_POST["isi"]);
    if(empty($input_isi)){
        $isi_err = "Please enter an isi.";
    } else{
        $isi = $input_isi;
    }

    // Validate kategori
    $input_kategori = trim($_POST["kategori"]);
    if(empty($input_kategori)){
        $kategori_err = "Please enter an kategori.";
    } else{
        $kategori = $input_kategori;
    }

    // Check input errors before inserting in database
    if(empty($judul_err) && empty($isi_err) && empty($kategori_err)){
        // Prepare an update statement
        $sql = "UPDATE table_note SET judul=?, isi=?, kategori=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
           mysqli_stmt_bind_param($stmt, "sssi", $param_judul, $param_isi, $param_kategori, $param_id);
            
            // Set parameters
            $param_judul = $judul;
            $param_isi = $isi;
            $param_kategori = $kategori;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: tampil.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM table_note WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $judul = $row["judul"];
                    $isi = $row["isi"];
                    $kategori = $row["kategori"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <div class="page-header">
                        <h2>Perbarui</h2>
                    </div>
                    <p>Mohon edit data untuk di perbarui.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" style="margin-bottom: 20px;">
                        <div class="form-group <?php echo (!empty($judul_err)) ? 'has-error' : ''; ?>">
                            <label>Judul</label>
                            <input type="text" name="judul" class="form-control" value="<?php echo $judul; ?>">
                            <span class="help-block"><?php echo $judul_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($isi_err)) ? 'has-error' : ''; ?>">
                            <label>isi</label>
                            <textarea name="isi" class="form-control"><?php echo $isi; ?></textarea>
                            <span class="help-block"><?php echo $isi_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($kategori_err)) ? 'has-error' : ''; ?>">
                            <label>Kategori</label>
                            <input type="text" name="kategori" class="form-control" value="<?php echo $kategori; ?>">
                            <span class="help-block"><?php echo $kategori_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="tampil.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>