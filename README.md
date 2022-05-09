SQSora 2022年5月9日 FastAdmin + TP5



 $\color{#00FFFF}{---}$ 
# **TP5语法**  

```php
//查询后只获取特定值， 0lny find
$user_order = User::where('id', $this->auth->id)->value('special_order');
```
```php
//关联特定的表，需要Model里面有关联的方法
->([with])
```

```php
//将数据按照特定的格式排序 
$user_order = 1,8,7,4
 ->orderRaw('field(special.id,' . $user_order . ')')
```


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
