<?php


class IndexController extends \Phalcon\Mvc\Controller
{
	public function indexAction()
	{
//		$cacheConfig                 = $this->config->index_cache;
//		$cacheConfig->back->frontend = Phalcon\Cache\Frontend\Factory::load($cacheConfig->front);
//		$cache                       = Phalcon\Cache\Backend\Factory::load($cacheConfig->back);
//
//		$output = $cache->get('');
//
//		if ($output === null) {
			$this->assets->addJs('js/clouds.js');

			$this->view->setVar('track', (array)$this->config->twitter->track);

			$output = $this->view->render('index');
//			$cache->save('', $output);
//		}

		return $output;
	}

	public function tagCloudAction()
	{
//		$cacheConfig                 = $this->config->tag_cloud_cache;
//		$cacheConfig->back->frontend = Phalcon\Cache\Frontend\Factory::load($cacheConfig->front);
//		$cache                       = Phalcon\Cache\Backend\Factory::load($cacheConfig->back);
//
//		$output = $cache->get('');
//
//		if ($output === null) {
			$obj = Code\Tally\TagCloud::getHashtagsFromTallies($this->config->word_stats);
			$this->view->setVar('obj', $obj->toArray());
			$output = $this->view->render('js');
//			$cache->save('', $output);
//		}

		return $output;
	}

	public function tagCloudAllAction()
	{
//		$cacheConfig                 = $this->config->tag_cloud_cache;
//		$cacheConfig->back->frontend = Phalcon\Cache\Frontend\Factory::load($cacheConfig->front);
//		$cache                       = Phalcon\Cache\Backend\Factory::load($cacheConfig->back);
//
//		$output = $cache->get('');
//
//		if ($output === null) {
			$obj = Code\Tally\TagCloud::getAllHashtagsFromTallies($this->config->word_stats);
			$this->view->setVar('obj', $obj->toArray());
			$output = $this->view->render('js');
//			$cache->save('', $output);
//		}

		return $output;
	}

	public function textWordsAction()
	{
//		$cacheConfig                 = $this->config->tag_cloud_cache;
//		$cacheConfig->back->frontend = Phalcon\Cache\Frontend\Factory::load($cacheConfig->front);
//		$cache                       = Phalcon\Cache\Backend\Factory::load($cacheConfig->back);
//
//		$output = $cache->get('');
//
//		if ($output === null) {
			$obj = Code\Tally\TagCloud::getText($this->config->word_stats);
			$this->view->setVar('obj', $obj->toArray());
			$output = $this->view->render('js');
//			$cache->save('', $output);
//		}

		return $output;
	}

	public function summaryAction()
	{
		$cacheConfig                 = $this->config->summary_cache;
		$cacheConfig->back->frontend = Phalcon\Cache\Frontend\Factory::load($cacheConfig->front);
		$cache                       = Phalcon\Cache\Backend\Factory::load($cacheConfig->back);

		$output = $cache->get('');

		if ($output === null) {
			$obj = Code\Summary::get($this->config->word_stats);
			$this->view->setVar('obj', $obj);
			$output = $this->view->render('js');
			$cache->save('', $output);
		}

		return $output;
	}


	public function infoAction()
	{
		phpinfo();
	}

}
