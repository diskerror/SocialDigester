<?php

use Phalcon\Mvc\Controller;

class GetController extends Controller
{
	public function indexAction()
	{
	}

	public function hashtagsAction()
	{
		$obj = Code\Tally\TagCloud::getHashtags($this->config->word_stats);
		$this->view->setVar('obj', $obj->toArray());
		return $this->view->render('js');
	}

	public function textAction()
	{
		$obj = Code\Tally\TagCloud::getText($this->config->word_stats);
		$this->view->setVar('obj', $obj);
		return $this->view->render('js');
	}

}
