<?php

use Phalcon\Mvc\Controller;
use Resource\MongoCollections\Snapshots;

class SnapshotController extends Controller
{
	public function indexAction()
	{
		$this->assets->addJs('js/snapshot.js');

		$snapshot = (new Snapshots($this->config->mongo_db))
			->find(['_id' => (int) $this->dispatcher->getParam('id')]);

		if (!$snapshot) {
			return $this->response->redirect('/', true);
		}

		$this->view->setVar('created', $snapshot->created);
		$this->view->setVar('track', $snapshot->track->toArray());
		$this->view->setVar('tagCloud', $snapshot->tagCloud->toArray());
		$this->view->setVar('Logic\Summary', $snapshot->summary->toArray());
		$output = $this->view->render('index');

		return $output;
	}

}
