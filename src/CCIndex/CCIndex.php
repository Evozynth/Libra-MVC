<?php
/**
 * Standard controller layout
 *
 * @package LibraCore
 */
class CCIndex implements IController {
	
	/**
	 * Implementing interface IController. All controllers must have an index action.
	 */
	public function Index() {
		global $li;
		$li->data['title'] = "The index controller";
		$li->data['main'] = '<h1>The index controller</h1>';
	}
	
}