SQSora 2022年5月9日 FastAdmin + TP5



 $\color{#00FFFF}{---}$ 
# **PHP** **TP5语法**  
### 接收参数
```php
//变量名(不写则接收整个对象),默认值,过滤方法 
//trim:过滤空格
//FastAdmin从1.2.0版本开始已经内置了xss_clean函数用于清除过滤请求中可能的危险字段
$data = $this->request->post('', '', 'trim,xss_clean');
```
$\color{#00FFFF}{----}$ 

### find select
```php
//find() VS select()    F为一维数组，S为二维数组
```
$\color{#00FFFF}{----}$ 

### 获取特定值
```php
//查询后只获取特定值， 0lny find
$user_order = User::where('id', $this->auth->id)->value('special_order');
```
$\color{#00FFFF}{----}$ 

### 关联查询
```php
//关联特定的表，需要Model里面有关联的方法
->([with])
```
$\color{#00FFFF}{----}$ 

### 字段自定义排序 特定排序
```php
//将数据按照特定的格式进行排序 
$user_order = 1,8,7,4
->orderRaw('field(需要排序的字段,' . $user_order . ')')
```
$\color{#00FFFF}{----}$ 

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
$\color{#00FFFF}{----}$ 

### create  写入数据
```php
//array      $data  要保存的数据数组
//array|true $field 允许保存字段，true表示数据库有的字段才保存
模型名称::create($data = [], $field = null)
```
$\color{#00FFFF}{----}$ 

### 合并数组 数组合并 数组拼接
```php
array_merge($array1, $array2);//两个数组有重复key时，后面的会覆盖前面
array_merge_recursive($array1, $array2);  //不会进行键名覆盖，而是将多个相同键名的值递归组成一个数组。
```
$\color{#00FFFF}{----}$ 
### JSON json json_encode  json_decode
```php
json_decode($data, true)    //转换为JSON
json_encode($data, JSON_UNESCAPED_UNICODE)   //???转换为中文并能保存中文到数据库???
```
$\color{#00FFFF}{----}$ 


# **controller**

## **API**


## **backend**


# **Model**

### __模型初始化设置__
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
$\color{#00FFFF}{----}$ 
### __关联查询数据库表__
    public function User()
    {
        //关联查询的目标模型路径,关联的外键,目标模型的主键,别名定义(已经废弃),JOIN类型 -> 预载入方式
        return $this->belongsTo('addons\cms\model\User', 'user_id', 'id')->setEagerlyType(0);
    }
$\color{#00FFFF}{----}$ 

# **View**

## **HTML**

## **JS**


******
******


# **其他**

## config.php文件配置相关
```
//调用插件的配置,与config.php中的配置同名 'name' => 'value'
$config =  get_addon_config('cms'); //获取cms插件类的配置
$变量 = $config['name1'];   //value1
$变量 = $config['name2'];
```
