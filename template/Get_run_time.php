<?php

namespace addons\cms\controller\api;

class Get_run_time
{
    public function index() //Server-Sent Events 服务器发送事件
    {
        $Stime = 0;
        $Etime = 0;
        $Ttime = 0;
        $Stime = microtime(true); //获取程序开始执行的时间
        //echo $Stime."<br/>";
        for ($i = 1; $i <= 10000000; $i++) {
            //为了实现有一定的时间差,所以用了一个FOR来消耗一些资源.
        }
        $Etime = microtime(true); //获取程序执行结束的时间
        echo $Etime."<br/>";
        $Ttime = $Etime - $Stime; //计算差值
        echo $Ttime."<br/>";
        $str_total = var_export($Ttime, TRUE);
        if (substr_count($str_total, "E")) { //为了避免1.28746032715E-005这种结果的出现,做了一下处理.
            $float_total = floatval(substr($str_total, 5));
            $Ttime = $float_total / 100000;
        }
        echo $Ttime . '秒';
    }
}
