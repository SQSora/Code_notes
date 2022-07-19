SQSora 2022年5月9日 PHP TP5 FastAdmin MySQL WeChatPush  



 $\color{#00FFFF}{---}$ 
# **PHP** **TP5语法**  
* ### 接收参数
```php
//变量名(不写则接收整个对象),默认值,过滤方法 
//trim:过滤空格
//FastAdmin从1.2.0版本开始已经内置了xss_clean函数用于清除过滤请求中可能的危险字段
$data = $this->request->post('', '', 'trim,xss_clean');
unset($data['id']);
```
 
* ### find select
```php
// find() VS select()    F为一维数组，S为二维数组
```

* ### 查询后获取特定值
```php
//查询后只获取特定值, Olny find() value只能获取一个
$user_order = User::where('id', $this->auth->id)->value('special_order');
```
* ### join查询后获取特定值,并自定义名称
```php
//查询后只获取特定值
$commentList = Comment::alias('c')
                ->join('cms_archives a', 'c.aid = a.id')
                ->where(['c.user_id' => $this->auth->id])
                ->field(['c.content', 'c.createtime' => 'comment_createtime'])
                ...
```


* ### 关联查询
```php
//关联特定的表，需要Model里面有关联的方法
->with(['Model1,Model2'])

//闭包关联查询,过滤特殊字符
->with([
   'Model1' => function ($query) {
        $query->withField('id,name,email....');
    }
])
```

* ### 字段自定义排序 特定排序
```php
//将数据按照特定的格式进行排序 
$user_order = 1,8,7,4
->orderRaw('field(需要排序的字段,' . $user_order . ')')
```

* ### SQL去重查询
```php
//group,显示结果为所有字段，对单一字段进行了去重操作
模型->group('id')->select();

//distinct,只能对field()的单一字段去重
模型->distinct(true)->field('id')->select();
```

* ### 验证器  validate 过滤  @[TP5文档](https://static.kancloud.cn/manual/thinkphp5/129352.html)
```php
//对前端提交的传输进行处理 
$rule = [
    'title|标题'   => 'require|length:3,100',
    'type|类型'    => 'require',
];
$msg = [
    'title.require'   => '标题不能为空',
    'title.length'    => '标题长度限制在3~100个字符',
    'type.require'    => '类型不能为空',
];
$validate = new Validate($rule, $msg);
$result = $validate->check($row);
```

* ### create  写入数据
```php
//array      $data  要保存的数据数组
//array|true $field 允许保存字段，true表示数据库有的字段才保存
模型名称::create($data = [], $field = null)
```

* ### 更新数据或新增数据
```php
    if ($SoraModel  = Sora::where('user_id', $this->auth->id)->find()) {
        $SoraModel->isUpdate(true)->save($data);
    } else {
        $SoraModel = Sora::create($data, true);
    };
```

* ### 合并数组 数组合并 数组拼接
```php
array_merge($array1, $array2);//两个数组有重复key时，后面的会覆盖前面
array_merge_recursive($array1, $array2);  //不会进行键名覆盖，而是将多个相同键名的值递归组成一个数组。
```
 
* ### JSON json转换
```php
json_decode($data, true)    //转换为JSON
json_encode($data, JSON_UNESCAPED_UNICODE)   //???转义为中文并能保存中文到数据库???
```

# **controller 控制器**


* ### 前置操作 (里面好像都得写小写，即使方法名有大写，也得小写)
    + [@文档](https://static.kancloud.cn/manual/thinkphp5/118050)
```php
    protected $beforeActionList = [
        'first',                                //在执行所有方法前都会执行first方法
        'second' =>  ['except'=>'hello'],       //除hello方法外的方法执行前都要先执行second方法
        'three'  =>  ['only'=>'hello,data'],    //只有hello/data方法执行前先执行three方法
    ];
```
* ### 接收传参 param POST GET
```php
$data = $this->request->param('', '', 'trim,xss_clean'); //接收Object传参,默认值,过滤参数
$data = $this->request->post('', '', 'trim,xss_clean'); //只能POST传参
$data = $this->request->get('', '', 'trim,xss_clean'); //只能GET传参
unset($data['id']); //接收全部数据并写入时候过滤恶意的id字段
```



# **Model 模型**

* ### 模型初始化设置
```php
    // 表名
    protected $name = 'user';   //前缀在config/database.php中设置

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名 绑定对应字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

```
 
* ### __关联查询数据库表__
```php
    public function User()
    {
        //关联查询的目标模型路径,关联的外键,目标模型的主键,别名定义(已经废弃),JOIN类型 -> 预载入方式
        return $this->belongsTo('addons\cms\model\User', 'user_id', 'id')->setEagerlyType(0);
    }
```
* ### __获取器 获取数据的字段值后自动进行处理__
```php
    public function getstatusAttr($value)   //status为数据库字段
    {
        $status = [0=>'禁用',1=>'正常',2=>'待审核'];
        return $status[$value];
        // 获取原始字段数据
        echo $user->getData('status');
        // 获取全部原始数据
        dump($user->getData());
    }
```
* ### __修改器 数据赋值时进行处理__
```php
    public function setNameAttr($value)//Name为数据库字段
    {
        return strtolower($value);
    }
```

# **View 前端视图**

## **HTML**

* ### **index.html**
        暂无
* ### **add.html AND Edit.html**
    + [@FastAdmin组件](https://doc.fastadmin.net/doc/component.html)
    + [@SelectPage](https://doc.fastadmin.net/doc/178.html)
    + [@表单认证](https://doc.fastadmin.net/doc/179.html)
```html
<!--绑定动态下拉 SelectPage
    1. class先加上 class=" selectpage"
    2. data-rule=""     设置验证规则
    3. data-field=""    你要显示的字段" 默认展示“name”字段
    4. data-source=""   数据来源
 -->
<input id="c-name" data-rule="required" data-source="category/selectpage" class="form-control selectpage" name="row[name]" type="text" value="{$row.user_id|htmlentities}">
```


## **JS**
* ### 表格参数
```js
sortName: 'weigh', //按照权重排序

```
* ### 使用JS处理后台的前端页面逻辑
```js
add: function () {                              //给add.html页面添加事件
    $("#button_id").click(function () {         //给这个ID绑定点击事件
        var text = $("#input_box1").val();      //获取输入框的值
            //一些处理逻辑
        $("#input_box2").val(text);             //给输入框赋值
    };
}
edit: function () {  //edit.html页面添加事件
    //处理逻辑
}
```


******
******


# **其他**

$\color{#00FFFF}{Ctrl + Shift + V 预览文件}$

* ## config.php文件配置相关
```
//调用插件的配置,与config.php中的配置同名 'name' => 'value'
$config =  get_addon_config('cms'); //获取cms插件类的配置
$变量 = $config['name1'];   //value1
$变量 = $config['name2'];
```
* ## __正则表达式匹配链接__
```php
// $str = '<section class="_editor"><p><img src="http://img.index.png" "  alt="image.png"></p></section>';
$str = '<p><br></p><div class="pgc-img"><img src="https://hostname.com" img_width="621" img_height="760" alt="123" inline="0"><p class="pgc-img-caption"></p></div><p data-track="1"><br></p><div class="pgc-img"><img src="https://127.0.0.1" img_width="625" img_height="650" alt="123" inline="0">';

preg_match_all('/\bsrc\b\s*=\s*[\'\"]?([^\'\"]*)[\'\"]?/i', $str, $url);    //正则表达式匹配链接
// preg_match_all("/(src)=([\"|']?)([^ \"'>]+\.(gif|jpg|jpeg|bmp|png))\\2/i", $str, $url);    //正则表达式匹配 为src= .... 和（gif|jpg|jpeg|bmp|png)后缀的链接

return $url;
```

* ## __微信消息推送 微信公众号 微信通知 微信模板__