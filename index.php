<?
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);

	require_once 'configs.php';
	require_once 'src/App.php';

	use src\App as App;

	$app = new App($configs);

	if(empty($_GET)) {
		header('Content-Type: application/json');
		echo json_encode($app->getData(), JSON_UNESCAPED_UNICODE);
	} else {
		file_put_contents('log.txt', print_r($_REQUEST,1), FILE_APPEND);
	}
?>
