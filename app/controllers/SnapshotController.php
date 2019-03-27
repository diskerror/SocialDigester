<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 6/27/18
 * Time: 1:43 AM
 */

class SnapshotController extends \Phalcon\Mvc\Controller
{
	use PropertiesTrait;

	public function indexAction()
	{
		$this->assets->addJs('js/snapshot.js');

		$id = preg_replace('/[^0-9a-fA-F]/', '', $this->dispatcher->getParam('id'));

		$snapshot = $this->mongo->snapshots->find(['_id' => $id]);

		if (!$snapshot) {
			return $this->response->redirect('/', true);
		}

		$this->view->setVar('created', $snapshot->created);
		$this->view->setVar('track', $snapshot->track->toArray());
		$this->view->setVar('tagCloud', $snapshot->tagCloud->toArray());
		$this->view->setVar('Code\Summary', $snapshot->summary->toArray());
		$output = $this->view->render('index');

		return $output;
	}

}
