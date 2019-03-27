<?php

class ErrorController extends \Phalcon\Mvc\Controller
{
	use PropertiesTrait;

	public function indexAction()
	{
	}

	public function show404Action()
	{
		$this->response->setStatusCode(404, "Not Found")->sendHeaders();
		return $this->view->render('404');
	}

}
