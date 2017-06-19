<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
/**
 * ThinkPHP Yar控制器类
 */
class TestController extends Controller{

   /**
     * 架构函数
     * @access public
     */
    public function test($input) {
        $this->ajaxReturn('dd','dddd',1);
    }

    
}
