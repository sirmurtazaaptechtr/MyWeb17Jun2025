<?php
include('inc.header.php');
$name = $description = $unit = $category_id = $image = '';
$_SESSION['errors'] = [];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // prx($_POST);
    // prx($_FILES);
    $name = safe_input($_POST['name']);
    $description = safe_input($_POST['description']);
    $unit = safe_input($_POST['unit']);
    // Handle 'null' parent correctly
    $category_id = isset($_POST['category_id']) && $_POST['category_id'] !== '' ? safe_input($_POST['category_id']) : 'NULL';

    // print($category_id);

    // Validate inputs
    if(empty($name)) {
        array_push($_SESSION['errors'], "Name is required.");
    }  
   

    // Process file upload
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "assets/img/products/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is an image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check === false) {
            array_push($_SESSION['errors'], "File is not an image.");
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["image"]["size"] > 5000000) {
            array_push($_SESSION['errors'], "Sorry, your file is too large.");
            $uploadOk = 0;
        }

        // Allow certain file formats
        if(!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            array_push($_SESSION['errors'], "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            array_push($_SESSION['errors'], "Sorry, your file was not uploaded.");
        } else {
            // If everything is ok, try to upload file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = $target_file;
            } else {
                array_push($_SESSION['errors'], "Sorry, there was an error uploading your file.");
            }
        }
    }

    // If no errors, insert into database
    if(empty($_SESSION['errors'])) {
        $sql = "INSERT INTO `products` (name, description, unit, category_id, image) VALUES ('$name', '$description', '$unit', ".($category_id === 'NULL' ? 'NULL' : "'$category_id'").", '$image')";
        if(mysqli_query($conn, $sql)) {
            $_SESSION['success'] = "Category added successfully.";
            header("Location: products.php");
            exit();
        } else {
            array_push($_SESSION['errors'], "Error adding category: " . mysqli_error($conn));
        }
    }
} 

$categories_sql = "SELECT * FROM `categories`";
$categories_result = mysqli_query($conn, $categories_sql);

?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Add New Product</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="products.php">Products</a></li>
                <li class="breadcrumb-item active">Create new Product</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <!-- errors and success -->
    <?php if(isset($_SESSION['success'])) { ?>
      <div class="alert alert-success">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
      </div>
    <?php } ?>
    <?php if(isset($_SESSION['error'])) { ?>
      <div class="alert alert-danger">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
      </div>
    <?php } ?>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Enter Product Details</h5>
                        <?php if(!empty($_SESSION['errors'])){ ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach($_SESSION['errors'] as $error): ?>
                                <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php } ?>
                        <!-- Add New User Form -->
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post"
                            enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                            <!-- Name -->
                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" id="name" name="name" class="form-control" required>
                                </div>
                            </div>
                            <!-- Description -->
                            <div class="row mb-3">
                                <label for="description" class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="description" id="description" style="height: 100px"></textarea>
                                </div>
                            </div>
                            <!-- Unit -->
                            <div class="row mb-3">
                                <label for="unit" class="col-sm-2 col-form-label">Unit</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="unit" id="unit">
                                </div>
                            </div>
                            <!-- Select Parent Category -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Select Category</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="category_id" aria-label="Default select example">
                                        <option value="">Leave if category is parent...</option>
                                        <?php while ($category = mysqli_fetch_assoc($categories_result)) { ?>
                                        <option value="<?php echo $category['id']; ?>">
                                            <?php echo $category['name']; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>                            
                            <!-- Category Image Upload -->
                            <div class="row mb-3">
                                <label for="categoryImage" class="col-sm-2 col-form-label">File Upload</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="image" type="file" id="categoryImage"
                                        accept="image/*">
                                </div>
                            </div>
                            <!-- Submit -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Submit Button</label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Submit Form</button>
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