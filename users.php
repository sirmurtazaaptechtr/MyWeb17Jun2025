<?php 
include('inc.header.php'); 
$sql = "SELECT * FROM `users`";
$result = mysqli_query($conn, $sql);
?>
 <main id="main" class="main">

    <div class="pagetitle">
      <h1>All Users</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Users</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Users</h5>

                <div class="text-start mb-3">
                    <a href="user.create.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New User</a>
                </div>

              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Profile Picture</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                <?php $srno = 1; While($user = mysqli_fetch_assoc($result)) {?> 
                  <tr>
                    <th scope="row"><?php echo $srno;?></th>
                    <td><?php echo $user['id']?></td>
                    <td><?php echo $user['name']?></td>
                    <td><?php echo $user['email']?></td>
                    <td><?php echo $user['role']?></td>
                    <td><img src="<?php echo $user['profile_pic']?>" alt="<?php echo $user['name']?>" style="width: 100px"></td>
                    <td>
                        <a href="user.edit.php?id=<?php echo $user['id']?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                        <a href="user.delete.php?id=<?php echo $user['id']?>" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
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