<?php


class IndexController extends \Phalcon\Mvc\Controller
{
	public function indexAction()
	{
		$indexCache                 = $this->config->index_cache;
		$indexCache->back->frontend = Phalcon\Cache\Frontend\Factory::load($indexCache->front);
		$cache                      = Phalcon\Cache\Backend\Factory::load($indexCache->back);

		$output = $cache->get('');

		if ($output === null) {
			$this->assets->addJs('js/cloud1.js');

			$this->view->setVar('track', (array)$this->config->twitter->track);

			$output = $this->view->render('index');
			$cache->save('', $output);
		}

		return $output;
	}

	public function tagCloudAction()
	{
		$tagCloudCache                 = $this->config->tag_cloud_cache;
		$tagCloudCache->back->frontend = Phalcon\Cache\Frontend\Factory::load($tagCloudCache->front);
		$cache                         = Phalcon\Cache\Backend\Factory::load($tagCloudCache->back);

		$output = $cache->get('');

		if ($output === null) {
			$obj = Code\Tally\TagCloud::getHashtags($this->config->word_stats);
			$this->view->setVar('obj', $obj->toArray());
			$output = $this->view->render('js');
			$cache->save('', $output);
		}

		return $output;
	}

	public function summaryAction()
	{
		$obj = Code\Summary::get($this->config->word_stats);
		$this->view->setVar('obj', $obj);
		return $this->view->render('js');
	}


	public function infoAction()
	{
		phpinfo();
	}

}
