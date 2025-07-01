<?php 
include('inc.header.php'); 
$sql = "SELECT * FROM `categories`";
$result = mysqli_query($conn, $sql);
?>
 <main id="main" class="main">

    <div class="pagetitle">
      <h1>Categories</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">categories</li>
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
              <h5 class="card-title">Categories</h5>

                <div class="text-start mb-3">
                    <a href="category.create.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New Category</a>
                </div>

              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Parent ID</th>
                    <th scope="col">Image</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                <?php $srno = 1; While($category = mysqli_fetch_assoc($result)) {?> 
                  <tr>
                    <th scope="row"><?php echo $srno;?></th>
                    <td><?php echo $category['id']?></td>
                    <td><?php echo $category['name']?></td>
                    <td><?php echo $category['description']?></td>
                    <td><?php echo $category['parent_id']?></td>
                    <td><img src="<?php echo $category['image']?>" alt="<?php echo $category['name']?>" style="width: 100px"></td>
                    <td>
                        <a href="category.edit.php?id=<?php echo $category['id']?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                        <a href="category.delete.php?id=<?php echo $category['id']?>" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
                  </tr>
                <?php $srno++; }?>  
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->

  
<?php include('inc.footer.php'); ?>