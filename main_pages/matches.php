<?php
$pageTitle="Matches Managament";
include '../includes/db.php';
include "../includes/header.php";
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_matches'])) {
    $team1 = (int) $_POST['team1'];
    $team2 = (int) $_POST['team2'];
    $team1_goal = (int) $_POST['team1_goal'];
    $team2_goal = (int) $_POST['team2_goal'];
    $match_date= $_POST['match_date'];
    if($team1 === $team2) {
        echo '<div class="alert alert-danger">You Can nog select the same team</div>';
    }elseif (empty($team1) || empty($team2) || $match_date === '') {
        echo '<div class="alert alert-danger">All Fields are required.</div>';
    }else {
        try{
            $pdo->beginTransaction();
            $stmt=$pdo->prepare("INSERT INTO matches (team1_id,team2_id,team1_goals,team2_goals,match_date) VALUES(?,?,?,?,?)");
            $stmt->execute([
                $team1, $team2, $team1_goal, $team2_goal, $match_date
            ]);
            updateTeamStats($pdo,$team1,$team1_goal,$team2_goal);
            updateTeamStats($pdo,$team2,$team2_goal,$team1_goal);
            $pdo->commit();

            echo '<div class="alert alert-success">Match added successfully.</div>';
        }catch(Exception $e) {
            $pdo->rollBack();
            echo '<div class="alert alert-danger">Error ' .$e->getMessage(). '.</div>';
        }
    }
}

function updateTeamStats($pdo, $teamId, $goalsFor, $goalsAgainst) {
    $points = 0;
    if($goalsFor > $goalsAgainst) {
        $points = 3;
    }elseif($goalsFor === $goalsAgainst) {
        $points = 1;
    }

    $stmt = $pdo->prepare("UPDATE teams SET points = points + ?, goals_scored = goals_scored + ?, goals_conceded=goals_conceded+? WHERE  id = ?");

    $stmt->execute([$points, $goalsFor, $goalsAgainst, $teamId]);
}

$teams = $pdo->query("SELECT id,class_name FROM teams ORDER BY class_name ASC")->fetchAll()
?>
<div class="card mb-4">
  <div class="card-header bg-primary text-light fs-4 fw-bold">
   Add Matches Management
  </div>
  <div class="card-body">
    <div class="container">
    <form class="d-flex my-2 justify-content-evenly" method="POST">
        <div class="mb-3">
            <select name="team1" id="input" class="form-control" required="required">
            <option value="">Select Team 1</option>
            <?php foreach($teams as $team):?>
                <option value=<?= $team['id'] ?>>
                    <?= htmlspecialchars($team['class_name']) ?>
            </option>
            <?php endforeach?>
        </select>
        </div>
         <div class="mb-3">
            <input type="number" class="form-control w-25" name="team1_goal">
        </div>
        <div class="mb-3">
            <input type="number" class="form-control w-25" name="team2_goal">
        </div>
         <div class="mb-3">
            <select name="team2" id="input" class="form-control" required="required">
            <option value="">Select Team 2</option>
            <?php foreach($teams as $team):?>
                <option value=<?= $team['id'] ?>>
                    <?= htmlspecialchars($team['class_name']) ?>
            </option>
            <?php endforeach?>
        </select>
        </div>
         <div class="mb-3">
            <input type="datetime-local" name="match_date" class="form-control">
        </div>
            <button type="submit" name="add_matches" class="btn btn-success align-self-start ms-3">Save</button>
</form>
<table class="table table-striped">
  <thead>
    
    <tr>
      <th scope="col">Date</th>
      <th scope="col">Match</th>
      <th scope="col">Result</th>
    </tr>
    
  </thead>
  <tbody>
    <?php
        $matches = $pdo->query('SELECT m.match_date, m.team1_goals, m.team2_goals, t1.class_name AS team1_name, t2.class_name AS team2_name FROM matches m  INNER JOIN teams t1 ON m.team1_id = t1.id
        INNER JOIN teams t2 ON m.team2_id = t2.id ORDER BY m.match_date DESC')->fetchAll()
    ?>
    <?php foreach($matches as $matche): ?>
    <tr>
      <td><?= date('d/m/y H:i',strtotime($matche['match_date'])) ?></td>
      <td><?= htmlspecialchars($matche['team1_name']) ?> vs <?= htmlspecialchars($matche['team2_name']) ?></td>
      <td><?= $matche['team1_goals'] ?> - <?= $matche['team2_goals'] ?></td>
    </tr>
    <?php endforeach?>
  </tbody>
</table>
    </div>
  </div>
  
</div>








<?php
include "../includes/footer.php";
?>