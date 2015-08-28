<?php
require_once('./controller/bootsrap.php');

if(isset($_GET['errors'])){
    $errors = unserialize(urldecode($_GET['errors']));
    ?>

<?php
}
$view = isset($_REQUEST['view']) && $_REQUEST['view'] ? $_REQUEST['view'] : 'welcome';

$postAction = isset($_REQUEST['action']) && $_REQUEST['action']  ? $_REQUEST['action'] : null;

if($postAction != null){
    ViewController::getInstance()->invokeAction();
}
if(file_exists(__DIR__ . '/views/'. $view . '.php')){
    require('/views/'. $view. '.php');
}