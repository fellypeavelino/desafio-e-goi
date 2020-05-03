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

    private $auth = null;

    public function __construct()
    {
      $this->auth = $this->getRequest()->getHeaders("Authorization", null);
    }

    public function indexAction()
    {
      /*
      let data = new FormData();
      //data.append("id",0);
      data.append("category_id",0);
      data.append("name","felipe");
      data.append("created",(new Date).toISOString());
      data.append("modified",(new Date).toISOString());
      fetch('/add', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': 'e-goi',
          },
          //body: JSON.stringify({id: 1, category_id: 0, name:"poliana","modified":(new Date).toISOString()})
          body: data
      });
      */
      return (new UtilResponse)->responseApi(true,$this->auth);
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
          if (count($data) == 0) {
            $content = $this->getRequest()->getContent();
            $data    = json_decode($content, true);
          }else {
            $data["created"]  = $request->getPost("created", date('Y-m-d\TH:i:s.u'));
            $data["modified"] = $request->getPost("modified", date('Y-m-d\TH:i:s.u'));
          }
          $id = end ($list) ? (end ($list))->id : count($list);
          $category = new CategoryTable(
            ($id+1), $data["category_id"],
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
      } catch (\Exception $e) {
        $this->getResponse()->setStatusCode(500);
        return (new UtilResponse)->responseApi(false,$e->getMessage());
      }
    }

    public function listCategoryAction()
    {
      try {
        $list = (new UtilData())->getData();
        return (new UtilResponse)->responseApi(true,$list);
      } catch (\Exception $e) {
        $this->getResponse()->setStatusCode(500);
        return (new UtilResponse)->responseApi(false,$e->getMessage());
      }

    }

    public function listCategoryByIdAction()
    {
      try {
        $id = $this->params()->fromRoute('id', 0);
        $list = (new UtilData())->getData();
        $result = new \stdClass;
        foreach ($list as $key => $obj) {
          if ($obj->id == $id) {
            $result = $obj;
          }
        }
        return (new UtilResponse)->responseApi(true,$result);
      } catch (\Exception $e) {
        $this->getResponse()->setStatusCode(500);
        return (new UtilResponse)->responseApi(false,$e->getMessage());
      }

    }

    public function updateCategoryAction()
    {
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
          return (new UtilResponse)->responseApi(true,$GLOBALS['PATCH_CATEGORY_DATA']);
        }else{
          throw new \Exception("Request is not put.", 1);
        }
      } catch (\Exception $e) {
        $this->getResponse()->setStatusCode(500);
        return (new UtilResponse)->responseApi(false,$e->getMessage());
      }
    }


    public function deleteCategoryAction()
    {
      try {
        $utilData = new UtilData();
        $list = $utilData->getData();
        $request = $this->getRequest();
        if ($request->getMethod() == "DELETE") {
          $content = $this->getRequest()->getContent();
          $data  = json_decode($content, true);
          if (isset($data["id"])) {
            $listFilter = [];
            foreach ($list as $key => $obj) {
              if ($obj->id != $data["id"]) {
                array_push($listFilter,$obj);
              }
            }
            $utilData->setData($listFilter);
            return (new UtilResponse)->responseApi(true,"Categoria removida.");
          }else{
            throw new \Exception("Não há id.", 1);
          }
        }else{
          throw new \Exception("Request is not delete.", 1);
        }
      } catch (\Exception $e) {
        $this->getResponse()->setStatusCode(500);
        return (new UtilResponse)->responseApi(false,$e->getMessage());
      }
    }

    public function outputAction()
    {
      try {
        $id = $this->params()->fromRoute('id', 0);
        $list = (new UtilData())->getData();
        $result = new \stdClass;

        foreach ($list as $key => $obj) {
          if ($obj->id == $id) {
            unset($obj->category_id);
            $result = $obj;
          }
        }

        $result->subcategorias = [];
        foreach ($list as $key => $obj) {
          if ($obj->category_id == $result->id) {
            unset($obj->category_id);
            array_push($result->subcategorias, $obj);
          }
        }

        return (new UtilResponse)->responseApi(true,$result);
      } catch (\Exception $e) {
        $this->getResponse()->setStatusCode(500);
        return (new UtilResponse)->responseApi(false,$e->getMessage());
      }
    }

    public function outputAllAction()
    {
      try {
        $id = $this->params()->fromRoute('id', 0);
        $list = (new UtilData())->getData();
        $newList = [];
        foreach ($list as $key => $obj) {
          unset($obj->category_id);
          $result = $obj;
          $result->subcategorias = [];
          foreach ($list as $k => $o) {
            if ($o->category_id == $result->id) {
              unset($o->category_id);
              array_push($result->subcategorias, $o);
            }
          }
          array_push($newList, $result);
        }


        return (new UtilResponse)->responseApi(true,$newList);
      } catch (\Exception $e) {
        $this->getResponse()->setStatusCode(500);
        return (new UtilResponse)->responseApi(false,$e->getMessage());
      }
    }

}
