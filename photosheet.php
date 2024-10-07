<?php

$site_title = "Photographs of Roadside America";
$site_desc = "by John Margolies";
$site_style = "style.css";
$img_folder = "img/john-margolies";
$allowed_types = ["gif","jpg","jpeg","png","webp"];

function create_slug($string) {
  $string = strtolower($string);
  $string = strip_tags($string);
  $string = stripslashes($string);
  $string = html_entity_decode($string);
  $string = str_replace('\'', '', $string);
  $string = trim(preg_replace('/[^a-z0-9]+/', '-', $string), '-');
  return $string;
}

$dimg = opendir($img_folder);
$grid = '';
$lightbox = '';

while($img_file = readdir($dimg)) {
  if(in_array(strtolower(@end(explode('.',$img_file))),$allowed_types))
  {$a_img[] = $img_file;} 
}

if(is_array($a_img)) sort($a_img, SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);

$totimg = count($a_img);

for($x = 0; $x < $totimg; $x++) {
  
  $file_name = pathinfo($a_img[$x], PATHINFO_FILENAME); 
  $file_slug = create_slug($file_name);
  
  $size = getimagesize($img_folder.'/'.$a_img[$x]);
  $width = $size[0];
  $height = $size[1];
  $aspect = $height / $width;
  if ($aspect >= 1) $orientation = 'portrait';
  else $orientation = 'landscape';

  $grid .= '
  <figure class="'.$orientation.'">
    <a href="#'.$file_slug.'" id="'.$file_slug.'-thumb">
      <img class="open" loading="lazy" width="'.$width.'" height="'.$height.'" src="'.$img_folder.'/'.$a_img[$x].'" alt="'.$file_name.'">
    </a>
    <figcaption>'.$file_name.'</figcaption>
  </figure>
  ';
  
  $lightbox .= '
  <figure tabindex="0" id="'.$file_slug.'" class="'.$orientation.'">
    <a tabindex="-1" href="#'.$file_slug.'" class="image">
      <img loading="lazy" width="'.$width.'" height="'.$height.'" src="'.$img_folder.'/'.$a_img[$x].'" alt="'.$file_name.'">
    </a>
    <a tabindex="-1" href="#'.$file_slug.'-thumb" class="close">Close</a>
  </figure>
  ';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">     
  <title><?php echo $site_title; ?></title>
  <meta name="description" content="<?php echo $site_desc; ?>">
  <meta property="og:title" content="<?php echo $site_title; ?>">
  <meta property="og:description" content="<?php echo $site_desc; ?>">
  <meta name="twitter:card" content="summary">
  <style type="text/css">
    <?php echo file_get_contents($site_style); ?>
  </style>
</head>
<body>
  <main>
    <h1><?php echo $site_title; ?> <span><?php echo $site_desc; ?></span></h1>
    <input type="radio" name="size" id="x-large">
    <label for="x-large">XL</label>
    <input type="radio" name="size" id="large">
    <label for="large">L</label>
    <input checked type="radio" name="size" id="medium">
    <label for="medium">M</label>
    <input type="radio" name="size" id="small">
    <label for="small">S</label>
    <div class="grid">
      <?php echo $grid; ?>
    </div>
    <div class="lightbox">
      <?php echo $lightbox; ?>   
      <div class="counter"></div>        
    </div> 
  </main>
  <footer>
    This <a target="_blank" rel="noopener" href="http://github.com/cadars/photosheet">photo sheet</a> was generated on <?php echo date("F j, Y"); ?>
  </footer>
  <script>
  // show lightbox
  document.addEventListener('click', function(event) {
    if (!event.target.matches('.open')) return;
    document.querySelector('body').classList.add('fixed')
  }, false);

  document.addEventListener(
    "keydown", (e) => {
      if (e.keyCode == 13) {
        document.activeElement.click();
        document.querySelector('body').classList.add('fixed')
      }
    }, false);

  // hide lightbox
  document.addEventListener('click', function(event) {
    if (!event.target.matches('.close')) return;
    document.querySelector('body').classList.remove('fixed')
  }, false);

  // esc key to close
  document.addEventListener(
    "keydown", (e) => {
      if (e.keyCode == 27) {
        document.activeElement.querySelector('.close').click();
        document.querySelector('body').classList.remove('fixed');
      }
    }, false);

  </script>
</body>
</html>
