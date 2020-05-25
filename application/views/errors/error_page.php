<!DOCTYPE html>
<html lang="en ru" >
<head>
  <meta charset="UTF-8">
  <title>Error Pages</title>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'>
  <link rel="stylesheet" href="<?php echo $path_scripts; ?>/style.css">
</head>
<body>
<div class="error-page">
  <div>
    <h1 data-h1="<?php echo $code; ?>"><?php echo $code; ?></h1>
    <p data-p="<?php echo $eng_messahe; ?>"><?php echo $message; ?></p>
  </div>
</div>
<div id="particles-js"></div>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js'></script>
  <script  src="<?php echo $path_scripts; ?>/script.js"></script>
</body>
</html>
