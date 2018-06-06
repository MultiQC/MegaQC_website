<?php
if ( $_POST['payload'] ) {

  // Get the hook secret
  $config = parse_ini_file("../config.ini");

  // Check that the hashed signature looks right
  list($algo, $hash) = explode('=', $_SERVER['HTTP_X_HUB_SIGNATURE'], 2) + array('', '');
  $rawPost = file_get_contents('php://input');
  if ($hash !== hash_hmac($algo, $rawPost, $config['secret'])){
    die('GitHub signature looks wrong.');
  }

  // Pull the new version of the repo, overwriting local changes (eg. rebasing)
  shell_exec("cd /home/megaqc/MegaQC_website && git fetch && git reset --hard origin/master");
  // Pull the new version of the MegaQC repo
  shell_exec("cd /home/megaqc/MegaQC_website/includes/MegaQC && git fetch && git reset --hard origin/master");
  die("done " . mktime());
}
?>
