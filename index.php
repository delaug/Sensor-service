<?
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);

	require_once 'configs.php';
	require_once 'src/App.php';

	use src\App as App;

	$app = new App($configs);
?>
