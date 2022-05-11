SQSora 2022年5月9日 PHP TP5 FastAdmin MySQL WeChatPush  



 $\color{#00FFFF}{---}$ 
# **PHP** **TP5语法**  
### 接收参数
```php
//变量名(不写则接收整个对象),默认值,过滤方法 
//trim:过滤空格
//FastAdmin从1.2.0版本开始已经内置了xss_clean函数用于清除过滤请求中可能的危险字段
$data = $this->request->post('', '', 'trim,xss_clean');
```
 

### find select
```php
//find() VS select()    F为一维数组，S为二维数组
```
 

### 获取特定值
```php
//查询后只获取特定值， 0lny find
$user_order = User::where('id', $this->auth->id)->value('special_order');
```
 

### 关联查询
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
 

### 字段自定义排序 特定排序
```php
//将数据按照特定的格式进行排序 
$user_order = 1,8,7,4
->orderRaw('field(需要排序的字段,' . $user_order . ')')
```


### SQL去重查询
```php
//group,显示结果为所有字段，对单一字段进行了去重操作
模型->group('id')->select();

//distinct,只能对field()的单一字段去重
模型->distinct(true)->field('id')->select();
```
 

### 验证器  validate 过滤  @[TP5官方文档](https://static.kancloud.cn/manual/thinkphp5/129352.html)
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
 

### create  写入数据
```php
//array      $data  要保存的数据数组
//array|true $field 允许保存字段，true表示数据库有的字段才保存
模型名称::create($data = [], $field = null)
```
 

### 合并数组 数组合并 数组拼接
```php
array_merge($array1, $array2);//两个数组有重复key时，后面的会覆盖前面
array_merge_recursive($array1, $array2);  //不会进行键名覆盖，而是将多个相同键名的值递归组成一个数组。
```
 
### JSON json转换
```php
json_decode($data, true)    //转换为JSON
json_encode($data, JSON_UNESCAPED_UNICODE)   //???转换为中文并能保存中文到数据库???
```
 

# **controller**

## **API**

### 接收传参 POST
```php
$data = $this->request->post('', '', 'trim,xss_clean'); //接收Object传参,默认值,过滤参数
```
 



## **backend**


# **Model**

### 模型初始化设置
```
    // 表名
    protected $name = 'company_attention';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名 绑定对应字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

```
 
### __关联查询数据库表__
    public function User()
    {
        //关联查询的目标模型路径,关联的外键,目标模型的主键,别名定义(已经废弃),JOIN类型 -> 预载入方式
        return $this->belongsTo('addons\cms\model\User', 'user_id', 'id')->setEagerlyType(0);
    }
 

# **View**

## **HTML**

### **index.html**

### **add.html AND Edit.html**
>[@FastAdmin组件](https://doc.fastadmin.net/doc/component.html)
>>表单认证[@FastAdmin组件](https://doc.fastadmin.net/doc/179.html)
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
```js
sortName: 'weigh', //按照权重排序
```


******
******


# **其他**

$\color{#00FFFF}{Ctrl + Shift + V 预览文件}$

## config.php文件配置相关
```
//调用插件的配置,与config.php中的配置同名 'name' => 'value'
$config =  get_addon_config('cms'); //获取cms插件类的配置
$变量 = $config['name1'];   //value1
$变量 = $config['name2'];
```

## 微信消息推送相关
```php
    public function push($userid, $template_id, $data, $event = null)
    {   
        $data = [
            'first' => ['value' => "副标题内容", 'color' => "#fc0101"], //类似副标题
            'keyword1' => ['value' => 'name'],
            'keyword2' => ['value' =>'哈哈'],
            'remark' => ['value' =>'备注']
        ]
        $pushData = [
            'touser'       => '目标用户的openid',
            'template_id'  => $template_id    //'tHRoi4rIN9k1R...'//在公众平台模板裤的模板ID
            // 'url' => $Gourl, //模板消息里“查看详情”的链接
            'topcolor'     => "#FF0000",       //顶部颜色,可不写.$data类同
            'data'         => $data,           //模板消息里的变量替换内容
            "miniprogram"  => [
                "appid"    => "小程序的appid",//给模板消息绑定小程序
                "pagepath" => "小程序的页面路径"
            ],
        ];
        $get_access_token = $this->get_access_token(); //获取公众号token
        $json_data = json_encode($pushData); //转化成json数组让微信可以接收
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $get_access_token; //模板消息请求URL
        $res = $this->curl_post($url, urldecode($json_data));
        $res = json_decode($res, true);
        if ($res['errcode'] == 0 && $res['errmsg'] == "ok") {
            //发送成功后操作
        } else {
            //发送失败后操作
        }
    }
```
```php 
    //将Wechat access_token 保存到数据库中缓存
   public function get_access_token()
    {
        $appid = 'appid';
        $secret = 'AppSecret';
        $cachedata =  Db::name('access_token')
            ->where('id', 1874)
            ->where('time', '>', time())
            ->find();
        if ($cachedata) {
            return $cachedata['access_token'];
        } else {
            //数据库token过期，重新到微信获取
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret;
            $res = $this->curl_get($url);
            $res = json_decode($res, true);
            $time = time() + 6999;  //微信默认7200秒过期
            $data = [
                'access_token' => $res['access_token'],
                'time' => $time,
            ];
            Db::name('access_token')->where('id', 1874)->update($data);
            return $res['access_token'];
        }
    }
```
```php
    /**
     * 发起POST请求
     * @param string $url 请求地址
     * @param string $data 请求参数
     * @param string string $cookiePth
     */
    function curl_post($url, $data = [], $cookiePath = '')
    {
        $ch = curl_init(); // 初始化
        curl_setopt($ch, CURLOPT_URL, $url); // 抓取指定网页
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1); // POST提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // 请求参数
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiePath); // 连接结束后保存cookie信息的文件
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiePath); // 包含cookie信息的文件
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 禁用后cURL将终止从服务端进行验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 检查服务器SSL证书中是否存在一个公用名
        $res = curl_exec($ch); // 执行一个cURL会话
        curl_close($ch); // 关闭一个cURL会话
        return $res;
    }
```
