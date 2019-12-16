<?php
declare(strict_types=1);
require_once 'autoload.php';

use Library\Toolkit;

try {

	$toolkit = Toolkit::createElement('div')
		->attribute('data', 'parent')
      ->text('This is parent element.')
      ->text(' This is 1st text of the parent element.', true)
      ->text(' This is 2nd text of the parent element.')
      ->child('div')
      ->attribute('data', 'child-01')
      ->text('This is a child element.')
      ->text(' This is 1st text of the child element.')
      ->text(' This is 2nd text of the child element.', true)
      ->child('div')
      ->attribute('data', 'child-01-01')
      ->text('This is a child element.')
      ->text(' This is 1st text of the child element.')
      ->text(' This is 2nd text of the child element.')
      ->child('div')
      ->attribute('data', 'child-01-01-01')
      ->text('This is a child element.')
      ->text(' This is 1st text of the child element.')
      ->text(' This is 2nd text of the child element.')
      ->getTop()
      ->child('p')
      ->attribute('data', 'child-02')
      ->text('This is a child element.')
      ->text(' This is 1st text of the child element.')
      ->text(' This is 2nd text of the child element.')
      ->getParent()
		->output();

	echo $toolkit;

} catch (TypeError $e) {
	var_dump($e);
} catch (Error $e) {
	var_dump($e);
}