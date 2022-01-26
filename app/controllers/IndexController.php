<?php


use Logic\TextGroup;
use Logic\WordCloud;
use Phalcon\Cache\Backend\Factory as BFactory;
use Phalcon\Cache\Frontend\Factory as FFactory;
use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
	public function indexAction()
	{
//		$cacheConfig                 = $this->config->index_cache;
//		$cacheConfig->back->frontend = FFactory::load($cacheConfig->front);
//		$cache                       = BFactory::load($cacheConfig->back);
//
//		$output = $cache->get('');
//
//		if ($output === null) {
		$this->assets->addJs('js/clouds.js');
		$this->view->setVar('track', $this->config->twitter->track->toArray());
		$output = $this->view->render('index');
//			$cache->save('', $output);
//		}

		return $output;
	}

	public function tagCloudAction()
	{
//		$cacheConfig                 = $this->config->tag_cloud_cache;
//		$cacheConfig->back->frontend = FFactory::load($cacheConfig->front);
//		$cache                       = BFactory::load($cacheConfig->back);
//
//		$output = $cache->get('');
//
//		if ($output === null) {
		$totals  = Logic\Tally\Hashtags::get($this->config->mongo_db, 60);
		$grouped = TextGroup::normalize($totals);
		$obj     = WordCloud::build($grouped)->toArray();

		$obj = array_slice($obj, 0, 32);
		ksort($obj, SORT_NATURAL | SORT_FLAG_CASE);

		$this->view->setVar('obj', $obj);
		$output = $this->view->render('js');
//			$cache->save('', $output);
//		}

		return $output;
	}

	public function tagCloudAllAction()
	{
//		$cacheConfig                 = $this->config->tag_cloud_cache;
//		$cacheConfig->back->frontend = FFactory::load($cacheConfig->front);
//		$cache                       = BFactory::load($cacheConfig->back);
//
//		$output = $cache->get('');
//
//		if ($output === null) {
		$totals  = Logic\Tally\HashtagsAll::get($this->config->mongo_db, 180);
		$grouped = TextGroup::normalize($totals);
		$obj     = WordCloud::build($grouped)->toArray();

		$obj = array_slice($obj, 0, 32);
		ksort($obj, SORT_NATURAL | SORT_FLAG_CASE);

		$this->view->setVar('obj', $obj);
		$output = $this->view->render('js');
//			$cache->save('', $output);
//		}

		return $output;
	}

	public function textWordsAction()
	{
//		$cacheConfig                 = $this->config->tag_cloud_cache;
//		$cacheConfig->back->frontend = FFactory::load($cacheConfig->front);
//		$cache                       = BFactory::load($cacheConfig->back);
//
//		$output = $cache->get('');
//
//		if ($output === null) {
		$totals  = Logic\Tally\TextWords::get($this->config->mongo_db, 180);
		$grouped = TextGroup::normalize($totals, 'strtolower');
		$obj     = WordCloud::build($grouped)->toArray();

		$obj = array_slice($obj, 0, 48);
		ksort($obj, SORT_NATURAL | SORT_FLAG_CASE);

		$this->view->setVar('obj', $obj);
		$output = $this->view->render('js');
//			$cache->save('', $output);
//		}

		return $output;
	}

	public function userMentionsAction()
	{
//		$cacheConfig                 = $this->config->tag_cloud_cache;
//		$cacheConfig->back->frontend = FFactory::load($cacheConfig->front);
//		$cache                       = BFactory::load($cacheConfig->back);
//
//		$output = $cache->get('');
//
//		if ($output === null) {
		$um    = Logic\Tally\UserMentions::get($this->config->mongo_db, 180);
		$um    = Logic\UserNameGroup::normalize($um);
		$cloud = WordCloud::build($um);
		Logic\Tally\UserMentions::changeLink($cloud);

		$obj = array_slice($cloud->toArray(), 0, 48);
		ksort($obj, SORT_NATURAL | SORT_FLAG_CASE);

		$this->view->setVar('obj', $obj);
		$output = $this->view->render('js');
//			$cache->save('', $output);
//		}

		return $output;
	}

	public function summaryAction()
	{
//		$cacheConfig                 = $this->config->summary_cache;
//		$cacheConfig->back->frontend = FFactory::load($cacheConfig->front);
//		$cache                       = BFactory::load($cacheConfig->back);
//
//		$output = $cache->get('');
//
//		if ($output === null) {
		$obj = Logic\Summary::get($this->config->mongo_db, 60, 3);

		$this->view->setVar('obj', $obj);
		$output = $this->view->render('js');
//			$cache->save('', $output);
//		}

		return $output;
	}


	public function infoAction()
	{
		phpinfo();
	}

}
