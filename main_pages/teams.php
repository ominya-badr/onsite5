<?php
$pageTitle="Teams Managament";
include '../includes/db.php';
include "../includes/header.php";
?>
<div class="card mt-4">
  <div class="card-header bg-primary text-light fs-4 fw-bold">
    Teams Management
  </div>
  <div class="card-body">
    <div class="container ">
        <?php
            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
                $stmt =$pdo->prepare('INSERT INTO teams (class_name) VALUES(?)');
                $stmt->execute([$_POST['name']]);
                echo "<div class='alert alert-success'>Team Added succussefully</div>";
            }
        ?>
    <form class="d-flex my-2" method="POST">
        <div class="mb-3">
            <input type="text" class="form-control" style="width: 500px" name='name' id="exampleInputEmail1" placeholder="Class Name" >
        </div>
            <button type="submit"  class="btn btn-success align-self-start ms-3">Add Team</button>
</form>
<table class="table table-striped">
  <thead>
    
    <tr>
      <th scope="col">Class</th>
      <th scope="col">Points</th>
      <th scope="col">Goals for</th>
      <th scope="col">Goals Against</th>
      <th scope="col">Actions</th>
    </tr>
    
  </thead>
  <tbody>
    <?php
    $teams = $pdo->query("SELECT * FROM teams");
    foreach($teams as $team) {
     ?>
    <tr>
      <td><?= htmlspecialchars($team['class_name']) ?></td>
      <td><?= $team["points"] ?></td>
      <td><?= $team["goals_scored"] ?></td>
      <td><?= $team["goals_conceded"] ?></td>
      <td>
        <a href="#" class="btn btn-warning btn-sm">Edit</a>
        <a href="delete.php?id=<?= $team['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
      </td>
    </tr>
    <?php } ?>
  </tbody>
</table>
    </div>
  </div>
  
</div>
<?php
include "../includes/footer.php";
?>