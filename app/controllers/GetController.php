<?php

use Logic\ConfigFactory;
use Phalcon\Mvc\Controller;

class GetController extends Controller
{
	public function indexAction()
	{
	}

	public function hashtagsAction()
	{
		$obj = Logic\Tally\TagCloud::getHashtags(ConfigFactory::get()->word_stats);
		$this->view->setVar('obj', $obj->toArray());
		return $this->view->render('js');
	}

	public function textAction()
	{
		$obj = Logic\Tally\TagCloud::getText(ConfigFactory::get()->word_stats);
		$this->view->setVar('obj', $obj);
		return $this->view->render('js');
	}

}
