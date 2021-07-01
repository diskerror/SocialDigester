<?php

class GetController extends \Phalcon\Mvc\Controller
{
	use PropertiesTrait;

	public function indexAction()
	{
	}

	public function hashtagsAction()
	{
		$obj = Logic\Tally\TagCloud::getHashtags($this->mongodb, $this->config->word_stats);
		$this->view->setVar('obj', $obj->toArray());
		return $this->view->render('js');
	}

	public function textAction()
	{
		$obj = Logic\Tally\TagCloud::getText($this->mongodb, $this->config->word_stats);
		$this->view->setVar('obj', $obj);
		return $this->view->render('js');
	}

}
