<?php require_once __DIR__ . '/includes/init.php'; $err=''; $next=$_GET['next'] ?? '/recipebox/recipes.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email=trim($_POST['email']??''); $pass=$_POST['password']??'';
  $stmt=$conn->prepare('SELECT id,name,email,password FROM users WHERE email=?'); $stmt->bind_param('s',$email); $stmt->execute();
  $u=$stmt->get_result()->fetch_assoc();
  if($u && password_verify($pass,$u['password'])){ $_SESSION['user']=['id'=>$u['id'],'name'=>$u['name'],'email'=>$u['email']]; header('Location: '.$next); exit; }
  else{ $err='Invalid email or password.'; }
}
include __DIR__ . '/includes/header.php'; ?>
<h1 class="h4 mb-3">Login</h1>
<?php if($err): ?><div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
<form method="post" class="row g-3">
  <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
  <div class="col-md-6"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
  <input type="hidden" name="next" value="<?php echo htmlspecialchars($next); ?>">
  <div class="col-12"><button class="btn btn-primary">Login</button></div>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>