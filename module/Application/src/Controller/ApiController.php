<?php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Application\Model\CategoryTable;
use \Util\UtilResponse;
use \Util\UtilData as UtilData;

class ApiController extends AbstractActionController
{

    public function __construct()
    {
      $auth = $this->getRequest()->getHeaders("Authorization", null);
    }

    public function indexAction()
    {/*
      let data = new FormData();
      //data.append("id",0);
      data.append("category_id",0);
      data.append("name","felipe");
      data.append("created",(new Date).toISOString());
      data.append("modified",(new Date).toISOString());
      fetch('/add', {
          method: 'POST',
          headers: {
            //'Accept': 'application/json',
            //'Content-Type': 'application/json'
          },
          //body: JSON.stringify({id: 1, category_id: 0, name:"poliana","modified":(new Date).toISOString()})
          body: data
      });
      */
      return new JsonModel();
    }

    public function addCategoryAction()
    {
      $viewModel = new JsonModel();
      try {
        $utilData = new UtilData();
        $list = $utilData->getData();
        $request = $this->getRequest();
        if ($request->isPost()) {
          $data = $request->getPost();
          $data["created"]  = $request->getPost("created", date('Y-m-d\TH:i:s.u'));
          $data["modified"] = $request->getPost("modified", date('Y-m-d\TH:i:s.u'));
          if (count($data) == 0) {
            $content = $this->getRequest()->getContent();
            $data    = json_decode($content, true);
          }
          $category = new CategoryTable(
            (count($list)+1), $data["category_id"],
            $data["name"], $data["created"],
            $data["modified"]
          );
          array_push($list, $category);
          $utilData->setData($list);
          $viewModel->setVariable('success', true);
          $viewModel->setVariable('data', count($list));
          return $viewModel;
        }else{
          throw new \Exception("Request is not post.", 1);
        }
      } catch (\Exception $th) {
        $this->getResponse()->setStatusCode(500);
        $viewModel->setVariable('success', false);
        $viewModel->setVariable('error', $th->getMessage());
        return $viewModel;
      }
    }

    public function listCategoryAction()
    {
      $viewModel = new JsonModel();
      try {
        $list = (new UtilData())->getData();
        return new JsonModel(["data" => $list]);
      } catch (\Exception $e) {
        $this->getResponse()->setStatusCode(500);
        $viewModel->setVariable('success', false);
        $viewModel->setVariable('error', $th->getMessage());
        return $viewModel;
      }

    }

    public function listCategoryByIdAction()
    {
      $viewModel = new JsonModel();
      try {
        $id = $this->params()->fromRoute('id', 0);
        $list = (new UtilData())->getData();
        $result = new \stdClass;
        foreach ($list as $key => $obj) {
          if ($obj->id == $id) {
            $result = $obj;
          }
        }
        return new JsonModel(["data" => $result]);
      } catch (\Exception $e) {
        $this->getResponse()->setStatusCode(500);
        $viewModel->setVariable('success', false);
        $viewModel->setVariable('error', $th->getMessage());
        return $viewModel;
      }

    }

    public function updateCategoryAction()
    {
      $viewModel = new JsonModel();
      $utilData = new UtilData();
      try {
        $request = $this->getRequest();
        if ($request->getMethod() == "PUT") {
          $list = $utilData->getData();
          $content = $this->getRequest()->getContent();
          $data  = json_decode($content, true);
          $listFilter = array_map(function ($obj) use ($data, $utilData){
            if ($obj->id == $data['id']) {
              $obj = $utilData->patchData($obj, $data);
              $GLOBALS['PATCH_CATEGORY_DATA'] = $obj;
              return $obj;
            }else{
              return $obj;
            }
          }, $list);

          $utilData->setData($listFilter);
          return new JsonModel([$GLOBALS['PATCH_CATEGORY_DATA']]);
        }else{
          throw new \Exception("Request is not put.", 1);
        }
      } catch (\Exception $e) {
        $this->getResponse()->setStatusCode(500);
        $viewModel->setVariable('success', false);
        $viewModel->setVariable('error', $e->getMessage());
        return $viewModel;
      }
    }

}
