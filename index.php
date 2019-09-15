<?php
declare(strict_types=1);
require_once 'autoload.php';

use Library\Toolkit;

try {

	$toolkit = Toolkit::createElement('div')
		->attribute('data', 'parent')
		->text('Hello World!')
		->text('I\'m Dilan Madhusanka.')
		->text('Thanks.')
		->child('p')
		->attribute('data', 'child-1')
		->text('This is 1st child element.')
		->text('This is 1st text of the child element.')
		->text('This is 2nd text of the child element.')
		->childOutput()
		->child('p')
		->attribute('data', 'child-2')
		->text('This is 2nd child element.')
		->text('This is 1st text of the child element.')
		->text('This is 2nd text of the child element.')
		->childOutput()	
		->child('p')
		->attribute('data', 'child-3')
		->text('This is 3rd child element.')
		->text('This is 1st text of the child element.')
		->text('This is 2nd text of the child element.')
		->childOutput()
		->child('p')
		->attribute('data', 'child-4')
		->text('This is 4th child element.')
		->text('This is 1st text of the child element.')
		->text('This is 2nd text of the child element.')
		->childOutput()
		->getTop()
		->output();

	echo $toolkit;

} catch (TypeError $e) {
	var_dump($e);
} catch (Error $e) {
	var_dump($e);
}

// $a = '';
// if ($a != null) {
// 	echo 'true';
// } else {
// 	echo 'false';
// }