<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 6/27/18
 * Time: 1:43 AM
 */

class SnapshotController extends \Phalcon\Mvc\Controller
{
	public function indexAction()
	{
		$this->assets->addJs('js/snapshot.js');

		$snapshot = $this->db->snapshots->find(['_id' => (int)$this->dispatcher->getParam('id')]);

		if (!$snapshot) {
			return $this->response->redirect('/',TRUE);
		}

		$this->view->setVar('created', $snapshot->created);
		$this->view->setVar('track', $snapshot->track->toArray());
		$this->view->setVar('tagCloud', $snapshot->tagCloud->getArrayForRest());
		$this->view->setVar('summary', $snapshot->summary->toArray());
		$output = $this->view->render('index');

		return $output;
	}

}
