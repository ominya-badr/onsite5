<?php
$pageTitle="Standing Managament";
include '../includes/db.php';
include "../includes/header.php";
$teams = $pdo->query("
SELECT
t.*,
(t.goals_scored - t.goals_conceded) AS goal_difference,
(SELECT COUNT(*) FROM matches m
WHERE (m.team1_id = t.id OR m.team2_id = t.id)) AS
matches_played,
(SELECT COUNT(*) FROM matches m
WHERE ((m.team1_id = t.id AND m.team1_goals >
m.team2_goals) OR

(m.team2_id = t.id
AND m.team2_goals >
m.team1_goals))) AS wins,
(SELECT COUNT(*) FROM matches m
WHERE ((m.team1_id = t.id OR m.team2_id = t.id) AND
m.team1_goals = m.team2_goals)) AS draws
FROM teams t
ORDER BY t.points DESC, goal_difference DESC, t.goals_scored
DESC
")->fetchAll();
?>
<div class="card mt-4">
  <div class="card-header bg-primary text-light fs-4 fw-bold">
    Standings Management
  </div>
  <div class="card-body">
    <div class="container ">
<table class="table table-striped">
  <thead>
    
    <tr>
      <th scope="col">#</th>
      <th scope="col">Team</th>
      <th scope="col">P</th>
      <th scope="col">W</th>
      <th scope="col">D</th>
      <th scope="col">L</th>
      <th scope="col">GF</th>
      <th scope="col">GA</th>
      <th scope="col">GD</th>
      <th scope="col">PTS</th>
    </tr>
    
  </thead>
  <tbody>
    <?php foreach ($teams as $index => $team): ?>
<tr>
<td><?= $index + 1 ?></td>
<td><?= htmlspecialchars($team['class_name']) ?></td>
<td><?= $team['matches_played'] ?></td>
<td><?= $team['wins'] ?></td>
<td><?= $team['draws'] ?></td>
<td><?= $team['matches_played'] - $team['wins'] -
$team['draws'] ?></td>
<td><?= $team['goals_scored'] ?></td>
<td><?= $team['goals_conceded'] ?></td>
<td><?= $team['goal_difference'] ?></td>
<td><strong><?= $team['points'] ?></strong></td>
</tr>
<?php endforeach; ?>
  </tbody>
</table>
    </div>
  </div>
  <ul class="list-inline p-3">
    <h3>Legand</h3>
  <li class="list-inline-item">P: Matches Played</li>
  <li class="list-inline-item">W: Win</li>
  <li class="list-inline-item">D: Draws</li>
  <li class="list-inline-item">L: Losses</li>
  <li class="list-inline-item">GF: Goals For</li>
  <li class="list-inline-item">GA: Goals Against</li>
  <li class="list-inline-item">GD: Goals Difference</li>
  <li class="list-inline-item">PTS: Pionts</li>
</ul>
</div>
<?php
include "../includes/footer.php";
?>