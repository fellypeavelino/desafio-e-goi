<?php

namespace Util;

use \Laminas\View\Model\JsonModel;
/**
 *
 */
class UtilResponse
{

    public function __construct(){}

    public function responseApi($status, $return)
    {
      $viewModel = new JsonModel();
      $viewModel->setVariable('success', $status);
      if ($status) {
        $viewModel->setVariable('data', $return);
      }else{
        $viewModel->setVariable('error', $return);
      }
      return $viewModel;
    }

    public function responseToken()
    {
      $result = ["msg"=>"token invalido"];
      echo \Laminas\Json\Json::encode($result);
      die;
    }
}
