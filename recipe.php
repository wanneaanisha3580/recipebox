<?php require_once __DIR__ . '/includes/init.php'; $id=(int)($_GET['id']??0);
$stmt=$conn->prepare('SELECT r.*, u.name author FROM recipes r JOIN users u ON u.id=r.user_id WHERE r.id=?'); $stmt->bind_param('i',$id); $stmt->execute(); $rec=$stmt->get_result()->fetch_assoc();
include __DIR__ . '/includes/header.php';
if(!$rec){ echo '<div class="alert alert-warning">Recipe not found.</div>'; include __DIR__ . '/includes/footer.php'; exit; }
?>
<div class="row g-4">
  <div class="col-md-6"><img src="<?php echo htmlspecialchars($rec['image']); ?>" class="img-fluid rounded" alt=""></div>
  <div class="col-md-6">
    <h1 class="h4"><?php echo htmlspecialchars($rec['title']); ?></h1>
    <div class="recipe-meta mb-2">By <?php echo htmlspecialchars($rec['author']); ?> • <?php echo htmlspecialchars($rec['created_at']); ?></div>
    <p><?php echo nl2br(htmlspecialchars($rec['description'])); ?></p>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>