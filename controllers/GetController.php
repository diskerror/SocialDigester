<?php

class GetController extends \Phalcon\Mvc\Controller
{
	public function indexAction()
	{
	}

	public function hashtagsAction()
	{
		$tally = new Tally\TagCloud\Hashtags($this->mongo);
		$this->view->setVar('obj', $tally->get($this->config->word_stats));
		return $this->view->render('js');
	}

	public function textAction()
	{
		$tally = new Tally\TagCloud\Text($this->mongo);
		$this->view->setVar('obj', $tally->get($this->config->word_stats));
		return $this->view->render('js');
	}

}
