<?php require_once __DIR__ . '/includes/init.php'; $q=trim($_GET['q']??'');
if($q!==''){$stmt=$conn->prepare("SELECT r.*, u.name author FROM recipes r JOIN users u ON u.id=r.user_id WHERE r.title LIKE CONCAT('%',?,'%') OR r.description LIKE CONCAT('%',?,'%') ORDER BY r.created_at DESC"); $stmt->bind_param('ss',$q,$q);}
else{$stmt=$conn->prepare('SELECT r.*, u.name author FROM recipes r JOIN users u ON u.id=r.user_id ORDER BY r.created_at DESC');}
$stmt->execute(); $res=$stmt->get_result(); include __DIR__ . '/includes/header.php'; ?>
<div class="hero"><h1 class="h3 m-0">Discover Recipes</h1>
  <form class="mt-2" method="get"><div class="input-group"><input class="form-control" type="search" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Search recipes..."><button class="btn btn-light">Search</button></div></form></div>
<div class="row g-3">
<?php while($r=$res->fetch_assoc()): ?>
  <div class="col-sm-6 col-lg-4"><div class="card h-100 shadow-sm"><img class="card-img-top" src="<?php echo htmlspecialchars($r['image']); ?>" alt=""><div class="card-body"><h5 class="card-title mb-1"><?php echo htmlspecialchars($r['title']); ?></h5><div class="recipe-meta mb-2">By <?php echo htmlspecialchars($r['author']); ?> • <?php echo htmlspecialchars($r['created_at']); ?></div><p class="card-text"><?php echo htmlspecialchars(mb_strimwidth($r['description'],0,150,'...')); ?></p><a href="recipe.php?id=<?php echo (int)$r['id']; ?>" class="stretched-link">Open</a></div></div></div>
<?php endwhile; if($res->num_rows===0): ?><div class="col-12"><div class="alert alert-info">No recipes yet. <?php if(current_user()): ?><a href="add_recipe.php">Add one now</a>.<?php else: ?>Login to add your first recipe.<?php endif; ?></div></div><?php endif; ?>
</div><?php include __DIR__ . '/includes/footer.php'; ?>