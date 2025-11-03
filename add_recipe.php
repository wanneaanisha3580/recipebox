<?php require_once __DIR__ . '/includes/init.php'; require_login(); $err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $title=trim($_POST['title']??''); $description=trim($_POST['description']??'');
  if($title===''||$description===''){ $err='Title and description required.'; }
  else{
    // ensure uploads dir exists
    $u_dir = __DIR__ . '/uploads';
    if(!is_dir($u_dir)){ mkdir($u_dir, 0755, true); }
    $image_path = '';
    if(isset($_FILES['image']) && $_FILES['image']['error']===UPLOAD_ERR_OK){
      $mime = mime_content_type($_FILES['image']['tmp_name']);
      $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/gif'=>'gif','image/webp'=>'webp'];
      if(!isset($allowed[$mime])){ $err='Only JPG, PNG, GIF or WEBP allowed.'; }
      else{
        $ext = $allowed[$mime];
        $fname = 'uploads/'.uniqid('img_').'.'.$ext;
        if(move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/' . $fname)){
          $image_path = $fname;
        } else { $err='Failed to move uploaded file.'; }
      }
    } else { $err='Please upload an image.'; }
    if(!$err){
      $uid = $_SESSION['user']['id'];
      $stmt = $conn->prepare('INSERT INTO recipes(user_id,title,description,image,created_at) VALUES(?,?,?,?,NOW())');
      $stmt->bind_param('isss',$uid,$title,$description,$image_path);
      if($stmt->execute()){ header('Location: /recipebox/recipe.php?id='.$stmt->insert_id); exit; }
      else{ $err='DB error: '.$conn->error; }
    }
  }
}
include __DIR__ . '/includes/header.php'; ?>
<h1 class="h4 mb-3">Add Recipe</h1>
<?php if($err): ?><div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="row g-3">
  <div class="col-md-6"><label class="form-label">Title</label><input class="form-control" name="title" required></div>
  <div class="col-md-6"><label class="form-label">Image</label><input type="file" class="form-control" name="image" accept="image/*" required></div>
  <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" rows="6" name="description" required></textarea></div>
  <div class="col-12"><button class="btn btn-success">Save Recipe</button></div>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>