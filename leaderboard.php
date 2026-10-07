<?php require_once 'config.php';
$rows = db()->query('SELECT name, points, streak, longest_streak FROM users WHERE role = "member" ORDER BY points DESC, streak DESC, name ASC LIMIT 50')->fetchAll();
require 'header.php'; ?>
<section class="leaderboard-head"><div><p class="eyebrow">Community momentum</p><h1>Goodness grows<br>when it is shared.</h1><p class="lead">Every point represents a small positive action. Cheer on the people making a ripple.</p></div><div class="leaderboard-art">🏆<small>keep going</small></div></section>
<section class="leaderboard-panel"><div class="panel-title"><div><p class="eyebrow">The ripple board</p><h2>Top community members</h2></div><span><?= count($rows) ?> members</span></div>
<?php if ($rows): ?><div class="leaderboard-list"><?php foreach ($rows as $i => $row): ?><article class="leaderboard-row <?= $i < 3 ? 'top-rank' : '' ?>"><div class="rank rank-<?= $i + 1 ?>"><?= $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : $i + 1)) ?></div><div class="leader-name"><b><?= esc($row['name']) ?></b><small><?= (int)$row['streak'] ?> day streak · best <?= (int)$row['longest_streak'] ?> days</small></div><strong><?= (int)$row['points'] ?><small> pts</small></strong></article><?php endforeach; ?></div><?php else: ?><p class="empty large">The first ripple is waiting. Register to appear here.</p><?php endif; ?></section>
<?php require 'footer.php'; ?>
