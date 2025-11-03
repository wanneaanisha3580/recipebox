<?php require_once __DIR__ . '/includes/init.php'; $err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name=trim($_POST['name']??''); $email=trim($_POST['email']??''); $pass=$_POST['password']??'';
  if($name===''||$email===''||$pass===''){ $err='All fields required.'; }
  else{
    $hash=password_hash($pass,PASSWORD_DEFAULT);
    $stmt=$conn->prepare('INSERT INTO users(name,email,password) VALUES(?,?,?)');
    $stmt->bind_param('sss',$name,$email,$hash);
    if($stmt->execute()){ $_SESSION['user']=['id'=>$stmt->insert_id,'name'=>$name,'email'=>$email]; header('Location: /recipebox/recipes.php'); exit; }
    else{ $err='Email already registered.'; }
  }
}
include __DIR__ . '/includes/header.php'; ?>
<h1 class="h4 mb-3">Create account</h1>
<?php if($err): ?><div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
<form method="post" class="row g-3">
  <div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" required></div>
  <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
  <div class="col-12"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
  <div class="col-12"><button class="btn btn-primary">Register</button></div>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>