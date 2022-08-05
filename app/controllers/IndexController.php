<?php


use Logic\TextGroup;
use Logic\UserNameGroup;
use Logic\WordCloud;
use Phalcon\Mvc\Controller;
use Service\StdIo;

class IndexController extends Controller
{
	public function indexAction()
	{
		$this->assets->addJs('js/clouds.js');
		$this->view->setVar('track', require $this->config->configPath . '/SearchTerms.php');
		return $this->view->render('index');
	}

	public function tagCloudAction()
	{
		$totals  = Logic\Tally\Hashtags::get($this->config, 60);
		$grouped = TextGroup::normalize($totals);
		$obj     = WordCloud::build($grouped)->toArray();

		$obj = array_slice($obj, 0, 32);
		ksort($obj, SORT_NATURAL | SORT_FLAG_CASE);

		$this->view->setVar('obj', $obj);
		return $this->view->render('js');
	}

	public function tagCloudAllAction()
	{
		$totals  = Logic\Tally\HashtagsAll::get($this->config, 180);
		$grouped = TextGroup::normalize($totals);
		$obj     = WordCloud::build($grouped)->toArray();

		$obj = array_slice($obj, 0, 32);
		ksort($obj, SORT_NATURAL | SORT_FLAG_CASE);

		$this->view->setVar('obj', $obj);
		return $this->view->render('js');
	}

	public function textWordsAction()
	{
		$totals  = Logic\Tally\TextWords::get($this->config, 180);
		$grouped = TextGroup::normalize($totals, 'strtolower');
		$obj     = WordCloud::build($grouped)->toArray();

		$obj = array_slice($obj, 0, 48);
		ksort($obj, SORT_NATURAL | SORT_FLAG_CASE);

		$this->view->setVar('obj', $obj);
		return $this->view->render('js');
	}

	public function userMentionsAction()
	{
		$um    = Logic\Tally\UserMentions::get($this->config, 180);
		$um    = Logic\UserNameGroup::normalize($um);
		$cloud = WordCloud::build($um);
		Logic\Tally\UserMentions::changeLink($cloud);

		$obj = array_slice($cloud->toArray(), 0, 48);
		ksort($obj, SORT_NATURAL | SORT_FLAG_CASE);

		$this->view->setVar('obj', $obj);
		return $this->view->render('js');
	}

	public function retweetsAction()
	{
		$totals = Logic\Tally\Retweets::get($this->config, 300);
		$totals = UserNameGroup::normalize($totals);
		$cloud  = WordCloud::build($totals);
		Logic\Tally\UserMentions::changeLink($cloud);

		$obj = array_slice($cloud->toArray(), 0, 24);
		ksort($obj, SORT_NATURAL | SORT_FLAG_CASE);

		$this->view->setVar('obj', $obj);
		return $this->view->render('js');
	}

	public function usersAction()
	{
		ini_set('memory_limit', 512 * 1024 * 1024);
		$totals = Logic\Tally\Users::get($this->config, 3600);
		$totals = UserNameGroup::normalize($totals);
		$cloud  = WordCloud::build($totals);
		Logic\Tally\UserMentions::changeLink($cloud);

		$obj = array_slice($cloud->toArray(), 0, 24);
		ksort($obj, SORT_NATURAL | SORT_FLAG_CASE);

		$this->view->setVar('obj', $obj);
		return $this->view->render('js');
	}

	public function summaryAction()
	{
		$obj = Logic\Summary::get($this->config, 60, 3);

		$this->view->setVar('obj', $obj);
		return $this->view->render('js');
	}


	public function infoAction()
	{
//		phpinfo();
		var_export($GLOBALS);
	}

}
