<?php

namespace addons\cms\controller\api;

use addons\cms\model\Sora;
use think\Validate;

class Template
{
    protected $noNeedLogin = ['*']; //登录限制
    public function index()
    {
        return '验证器和增删改查';
    }

    public function checkValidate() //$this->validate();
    {
        //示例数据
        $data = [
            'name'  => 'SQSora',
            'age'   => 0,
            'email' => ''
        ];
        $rule = [   //验证规则
            'name'  => 'require|max:25',
            'age'   => 'number|between:0,1024',
            'email' => 'email',
        ];
        $msg = [    //没有定义msg，系统显示默认提示信息
            'name.require' => '名称必须',
            'name.max'     => '名称最多不能超过25个字符',
            'age.number'   => '年龄必须是数字',
            'age.between'  => '年龄只能在0-1024之间',
            'email'        => '邮箱格式错误',
        ];
        $validate = new Validate($rule, $msg);
        $result = $validate->check($data);
        if (!$result) {
            $this->error($validate->getError());
        };
        // if ($result) {
        //     return true;
        // } else {
        //     $this->error($validate->getError());
        // }
    }

    public function add()
    {
        $data = $this->request->param('', '', 'trim,xss_clean');
        unset($data['id']);

        $this->checkValidate($data);
        $ProData = [
            '附加的字段' => '附加的值',
        ];
        $Pushdata =  array_merge_recursive($data, $ProData);
        Sora::create($Pushdata, true);
        $this->success('创建成功');
        
        //修改或者创建
        if ($SoraModel  = Sora::where('user_id', $this->auth->id)->find()) {
            $SoraModel->isUpdate(true)->save($data);
        } else {
           Sora::create($data, true);
        };
    }

    public function edit()
    {
        $data = $this->request->param('', '', 'trim,xss_clean');
        unset($data['id']);

        $this->checkValidate($data);
        // 示例数据   
        $data = [
            'id'  => 1,
            'name' => 'SQSora',
            'age'   => 1024,
        ];

        //只修改
        if ($sora = Sora::where('id', $data['id'])->find()) {
            $sora->allowField(true)->isUpdate(true)->save($data);
            $this->success('修改成功');
        } else {
            $this->error('id不存在');
        }

        //修改或者创建
        if ($SoraModel  = Sora::where('user_id', $this->auth->id)->find()) {
            $SoraModel->isUpdate(true)->save($data);
        } else {
            $SoraModel = Sora::create($data, true);
        };

    }

    public function del()
    {
        $this->request->param('id') ? $id = $this->request->param('id') : $this->error('参数错误');
        if ($sora = Sora::where('id', $id)->find()) {
            $sora->delete();
            $this->success('删除成功');
        } else {
            $this->error('id不存在');
        }
        $this->success('成功',  $sora);
    }

    public function getData()
    {
        $id = $this->request->param('id');
        $this->success('获取成功', Sora::where('id', $id)->find());
    }

    public function filterData()
    {
        $isFilter = $this->request->param('isfilter', '');
        //1.关键字过滤,为空不过滤
        Sora::whereLike('title', '%' . $isFilter . '%');
        
        //2.用数组控制查询条件
        $a = ['id' => 109];
        return Sora::where($a)->select();

    }
}
