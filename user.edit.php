<?php
include('inc.header.php');
// Variables
$id = $name = $email = $password = $role = '';
$profile_pic = '';
$errors = [];

if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
  // Check if user is logged in
  $id = safe_input($_GET['id']);
  // Fetch user details from database
    $sql = "SELECT * FROM `users` WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        $password = $user['password']; // Keep old password
        $name = $user['name'];
        $email = $user['email'];        
        $role = $user['role'];
        $profile_pic = $user['profile_pic'];
    } else {
        array_push($errors, "User not found.");
    }
 
}

// Check if form is submitted
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  // pr($_POST);
  // pr($_FILES);
  $id = safe_input($_POST['id']);
  $name = safe_input($_POST['name']);
  $email = safe_input($_POST['email']);    
  $password = safe_input($_POST['password']);
    // If password is empty, keep the old password
    if(empty($password)) {
        $sql = "SELECT password FROM `users` WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $password = $user['password']; // Keep old password
        } else {
            array_push($errors, "User not found.");
        }
    }
  $role = safe_input($_POST['role']);
  $profile_pic = $_FILES['profile_pic'];
  // pr($profile_pic);
  // Validate inputs
  if(empty($name) || empty($email) || empty($password) || empty($role)) {
    array_push($errors, "All fields are required.");
  } else {
    // Process file upload
    $target_dir = "assets/img/profile_pic/";
    $target_file = $target_dir . basename($profile_pic["name"]);
    $uploadOk = 1;   


    // Check if file is an image
    $check = getimagesize($profile_pic["tmp_name"]);
    if($check !== false) {
      $uploadOk = 1;
    } else {
      array_push($errors,"File is not an image.");
      $uploadOk = 0;
    }
    
    // Check file size
    if ($profile_pic["size"] > 5000000) {
      array_push($errors,"Sorry, your file is too large.");
      $uploadOk = 0;
    }
    
    // Allow certain file formats
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if(!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
      array_push($errors,"Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
      $uploadOk = 0;
    }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      array_push($errors, "Sorry, your file was not uploaded.");
    } else {
      // If everything is ok, try to upload file
      if (move_uploaded_file($profile_pic["tmp_name"], $target_file)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert into database
        $sql = "UPDATE `users` SET 
                name = '$name', 
                email = '$email', 
                password = '$hashed_password', 
                role = '$role', 
                profile_pic = '$target_file' 
                WHERE id = '$id'";
        if(mysqli_query($conn, $sql)) {
          echo "New user created successfully";
          // Redirect to users page
          header("Location: users.php");
          exit();
        } else {
          echo "Error: " . mysqli_error($conn);
        }
      } else {
        array_push($errors,"Sorry, there was an error uploading your file.");
      }
    }
  }

}
    
?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Add New User</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="users.php">users</a></li>
                <li class="breadcrumb-item active">Create User</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Enter User Details</h5>
                        <?php if(!empty($errors)){ ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php } ?>
                        <!-- Add New User Form -->
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post"
                            enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                            <!-- ID -->
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">                            
                            <!-- Name -->
                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" required>
                                </div>
                            </div>
                            <!-- Email -->
                            <div class="row mb-3">
                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
                                </div>
                            </div>
                            <!-- Password -->
                            <div class="row mb-3">
                                <label for="password" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" id="password" name="password" class="form-control" value="<?php echo htmlspecialchars($password); ?>" placeholder="Type new password if you want to change it">
                                </div>
                            </div>
                            <!-- Role -->
                            <div class="row mb-3">
                                <label for="yourRole" class="col-sm-2 col-form-label">Select Role</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="yourRole" name="role"
                                        aria-label="Default select example">
                                        <option value="">Choose Role...</option>
                                        <option value="admin" <?php if($role == 'admin'){echo 'selected';}?>>Admin</option>
                                        <option value="customer" <?php if($role == 'customer'){echo 'selected';}?>>Customer</option>
                                        <option value="employee" <?php if($role == 'employee'){echo 'selected';}?>>Employee</option>
                                    </select>
                                </div>
                            </div>
                            <!-- show old Picture -->
                            <div class="row mb-3">
                                <label for="oldProfilePic" class="col-sm-2 col-form-label">Old Profile Picture</label>
                                <div class="col-sm-10">
                                    <?php if($profile_pic): ?>
                                    <img src="<?php echo $profile_pic; ?>" alt="Profile Picture" style="width: 100px;">
                                    <?php else: ?>
                                    <p>No profile picture uploaded.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- Profile Picture Upload -->
                            <div class="row mb-3">
                                <label for="profilePic" class="col-sm-2 col-form-label">File Upload</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="profile_pic" type="file" id="profilePic"
                                        accept="image/*" value="<?php echo htmlspecialchars($profile_pic); ?>">
                                </div>
                            </div>
                            <!-- Submit -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Submit Button</label>
                                <div class="col-sm-10">                                    
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>

                        </form><!-- End Add New User Form -->

                    </div>
                </div>

            </div>

        </div>
    </section>

</main><!-- End #main -->
<?php include('inc.footer.php');?>