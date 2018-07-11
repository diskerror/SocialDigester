<?php

class GetController extends \Phalcon\Mvc\Controller
{
	public function indexAction()
	{
	}

	public function hashtagsAction()
	{
		$obj = Tally\TagCloud::getHashtags($this->db->tweets, $this->config->word_stats);
		$this->view->setVar('obj', $obj->toArray());
		return $this->view->render('js');
	}

	public function textAction()
	{
		$obj = Tally\TagCloud::getText($this->db->tweets, $this->config->word_stats);
		$this->view->setVar('obj', $obj);
		return $this->view->render('js');
	}

}
