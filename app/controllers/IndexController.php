<?php


use Logic\ConfigFactory;
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
			$this->view->setVar('track', ConfigFactory::get()->twitter->track->toArray());
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
			$obj = Logic\Tally\TagCloud::getHashtagsFromTallies(ConfigFactory::get());
			$this->view->setVar('obj', $obj->toArray());
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
			$obj = Logic\Tally\TagCloud::getAllHashtagsFromTallies(ConfigFactory::get());
			$this->view->setVar('obj', $obj->toArray());
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
			$obj = Logic\Tally\TagCloud::getText(ConfigFactory::get());
			$this->view->setVar('obj', $obj->toArray());
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
			$obj = Logic\Tally\TagCloud::getUserMentionsFromTallies(ConfigFactory::get());
			$this->view->setVar('obj', $obj->toArray());
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
			$obj = Logic\Summary::get(ConfigFactory::get()->mongo_db);
			$this->view->setVar('obj', $obj);
			$output = $this->view->render('js');
//			$cache->save('', $output);
//		}

		return $output;
	}


	public function infoAction()
	{
//		phpinfo();
	}

}
