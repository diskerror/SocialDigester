<?php

class IndexController extends \Phalcon\Mvc\Controller
{
	public function indexAction()
	{
		$indexCache = $this->config->index_cache;
		$indexCache->back->frontend = Phalcon\Cache\Frontend\Factory::load($indexCache->front);
		$cache = Phalcon\Cache\Backend\Factory::load($indexCache->back);

		$output = $cache->get('');

		if ($output === null) {
			$this->assets
				->addCss('css/jqcloud.min.css')
				->addCss('css/jquery.qtip.min.css')
				->addCss('css/jquery-ui.min.css')
				->addCss('css/jquery-ui.structure.min.css');

			$this->assets
				->addJs('js/jquery-3.3.1.min.js')
				->addJs('js/jqcloud.min.js')
				->addJs('js/cloud1.js')
				->addJs('js/jquery.qtip.min.js')
				->addJs('js/imagesloaded.pkg.min.js')
				->addJs('js/jquery-ui.min.js');

			$this->view->setVar('terms', implode(', ', (array)$this->config->twitter->track));

			$output = $this->view->render('index');
			$cache->save('', $output);
		}

		return $output;
	}

	public function tagCloudAction()
	{
		$tagCloudCache = $this->config->tag_cloud_cache;
		$tagCloudCache->back->frontend = Phalcon\Cache\Frontend\Factory::load($tagCloudCache->front);
		$cache = Phalcon\Cache\Backend\Factory::load($tagCloudCache->back);

		$output = $cache->get('');

		if ($output === null) {
			$obj = Tally\TagCloud::getHashtags($this->db->tweets, $this->config->word_stats);
			$this->view->setVar('obj', $obj->getArrForRest());
			$output = $this->view->render('js');
			$cache->save('', $output);
		}

		return $output;
	}

	public function summaryAction()
	{
		$obj = GenerateSummary::exec($this->db->tweets, $this->config->word_stats);
		$this->view->setVar('obj', $obj);
		return $this->view->render('js');
	}

}
