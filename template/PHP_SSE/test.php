<?php

namespace addons\cms\controller\api;

class Test1 extends Base
{
    public function index() //Server-Sent Events 服务器发送事件
    {
        //发送SSE应答
        header('X-Accel-Buffering: no');
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        for ($i = 0; $i < 100; $i++) {
            $o_data = date("Y-m-d H:i:s");
            $returnStr = json_encode($o_data);
            // $returnStr = json_encode("123");
            echo 'data: ' . $returnStr . "\n\n";
            ob_flush();
            flush();
            //等待1秒钟，开始下一次代码逻辑
            sleep(1);
        }
    }
}
