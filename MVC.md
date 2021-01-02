---
 show: step
version: 1.0 
---

# MVC简介

## 一、实验介绍

本节实验内容主要介绍了什么是 MVC 框架，以及 MVC 框架的工作流程，然后介绍了开发过程中遵循的一般规范。

### 实验知识点

实验中会涉及到以下知识点：

- MVC 概念
- 开发规范
- 命名空间

## 二、实验步骤

实验主要包括以下几个步骤：

- MVC 简介
- 创建目录
- 开发规范
- 命名空间

### **MVC 简介**


MVC 是 Model-View-Controller 的简称，用一种业务逻辑、数据、页面显示分离的方法组织代码，将业务逻辑聚集到一个部件里面，与用户交互的页面可以不受业务逻辑限制，可随时更改和切换。

![](http://labfile.oss.aliyuncs.com/courses/1076/4-1.png)

上图是一个 MVC 框架处理请求的流程，Controller 接收请求，并组织转发请求到 Model，Model 实现逻辑代码和数据交互，返回结果给 Controller，Controller 执行页面处理并返回结果给请求。

因此，一个 MVC 必要的部分有：
 - **入口文件**（如 index.php），注册应用，定义常量等
 - **框架核心**，启动框架，加载配置文件，请求路由解析
 - **路由**，解析 URL 到控制器中的方法
 - **控制器**，执行具体的请求操作

通常一个完整的 MVC 框架还包括配置文件，各种类库，如 Cache，Session，Error 等，与数据库交互的 Model 层，可以添加对象关系映射 ORM，在路由解析的时候还可以注册插件（插件非常有用，可以在路由解析前和解析后实现一些功能，如登陆验证）。此外，有时候还会在框架中添加需有有用的辅助类和函数，如 URL 类，HTML 类等。

####  MVC 基本概念视频介绍: 

```video
http://labfile.oss-cn-hangzhou.aliyuncs.com/courses/1099/week1/1.MVC%E7%AE%80%E4%BB%8B.mp4
```



### 框架简介

我们把要实现的框架命名为 `tinyphp`，顾名思义是一个非常轻量级的 PHP 框架。

#### 架构设计

框架的整体架构，如图所示

![](http://labfile.oss.aliyuncs.com/courses/1076/1-1.png)

入口文件创建应用（实例化核心类）和注册自动加载，然后进行启动项操作，启动项中可以进行插件注册或者环境变量定义等，根据是否注册插件，在路由解析前后执行插件方法。

路由解析的时候可以加载自定义路由，解析完成后调用控制器方法。

####  架构设计视频讲解:

```video
http://labfile.oss-cn-hangzhou.aliyuncs.com/courses/1099/week1/2.%E6%A1%86%E6%9E%B6%E7%AE%80%E4%BB%8B.mp4
```




#### 实现思路

从 `tinyphp` 的架构图可以看出，要实现该框架需要

- 在入口文件创建应用，同时注册自动加载。
- 应用核心类两个主要方法，构造函数 `__construct()` 用来注册启动项，`run()` 方法执行插件方法，路由解析，控制器方法调用等。
- 插件需要实现两个方法，路由前执行和路由后执行，并且插件在启动项中注册。
- 路由解析前需要检测是否有自定义路由，如果有先执行自定义路由，在进行路由解析，需要注意的是，自定义路由优先级高于默认路由，并且会覆盖默认路由。
- 控制器中可以进行数据交互，有两种方式，一种直接操作模型类，需要自己写 SQL 语句，另一种是数据对象映射 ORM，可以使用内置方法实现数据操作。

#### 关键模块

架构图的最顶部有三个模块，分别是

- 分发器
- 容器
- 盒子

分发器的作用是，进行全局管理，包括所有类的实例化操作，公共方法的定义等，容器和盒子是分发器的子类，它们的区别在于，容器中的实例可以重复利用，盒子中的实例每次会重新创建。

### **创建目录**

我们在 `/home/shiyanlou` 目录下创建一个名为 `tinyphp` 的文件夹作为框架名称。

```linux
$ cd /home/shiyanlou
$ mkdir tinyphp
```
在 `tinyphp` 中需要创建一系列目录用来存放应用代码和框架本身的代码

```linux
$ cd tinyphp
$ mkdir app core public

$ cd app
$ mkdir controllers models views conf
```
创建好后目录结构为 `/home/shiyanlou/tinyphp`

```linux
tinyphp
  - app
    - conf
    - controllers
    - models
    - views
  - public
  - core
```
其中文件夹 `app` 用来存放所有与应用相关的代码，`core` 用来存放框架本身的代码，`public` 存放入口文件（如 index.php）和公共资源（如 JS，CSS）。

####  目录创建视频讲解: 

```video
http://labfile.oss-cn-hangzhou.aliyuncs.com/courses/1099/week1/3.%E5%88%9B%E5%BB%BA%E7%9B%AE%E5%BD%95.mp4
```





```checker
- name: 检查是否创建目录
  script: |
    #!/bin/bash
    ls -l /home/shiyanlou/tinyphp
  error: | 
    我们发现您还没有创建好目录
```

### **开发规范**

在开发过程中，我们需要遵循一定的规范，这里采用 PHP-FIG 制订的规范，具体内容可以参见 <a href="https://psr.phphub.org" target="_blank">PHP 标准规范</a>，简单列举一些规范

- 基础规范
 - 类和方法开始中括号`{`必须另起一行，结束中括号`}`也需独占一行
 - 方法内中括号`{`可以接在代码后面，需要一个空格隔开
 - 换行缩进使用 4 个空格而不是`Tab`
 - 方法名后直接加()，不能有空格
 - 方法参数两边不能有空格

- 命名规范
 - 类的命名必须遵循大写开头的驼峰命名规范
 - 方法名称必须遵循小写开头驼峰命名规范
 - 类中的常量所有字母都必须大写，单词间用下划线分隔
 - 文件名与类名相同，包括大小写

示例

```php
<?php

/**
 * 类命名遵循大写开头的驼峰命名规范
 *
 * 中括号另起一行
 */
class IndexController
{
    //常量必须大写
    const ENV = 1;
    
    //单词间用下划线分隔
    const LOCAL_TIME = 2;
    
    /**
     * 方法命名遵循小写开头的驼峰命名规范
     *
     * 中括号另起一行
     *
     * 参数两边不能有空格，中间可以有空格
     */
    public function getHome($a, $b)
    {
        /**
         * 中括号直接接在条件后面，中间空一格
         * 
         */
        if (ENV == 1) {
            return "Hello";
        }
        return "Welcome";
    }
}
```

#### 开发规范视频讲解:

```video
http://labfile.oss-cn-hangzhou.aliyuncs.com/courses/1099/week1/4.%E7%BC%96%E7%A0%81%E8%A7%84%E8%8C%83%E5%92%8C%E5%91%BD%E5%90%8D%E7%A9%BA%E9%97%B4.mp4
```



### **命名空间**

建议在所有类中使用`namespace`，主要用来解决
- 用户编写的代码与PHP内部的类/函数/常量或第三方类/函数/常量之间的名字冲突
- 为很长的标识符名称创建一个别名，提高源代码的可读性

命名空间的命名规则为类的上一级目录的单数形式，如

`core` 目录下的类命名空间为 `namespace core;`，`app/controllers` 目录下的类命名空间为 `namespace controller`。当然命名空间可以自定义，但是需要在自动加载中映射路径（下节实验会详细介绍）

## 三、总结

本结主要介绍了 MVC 框架的概念和工作流程，以及一些开发规范和命名空间。创建了一个 `tinyphp` 的目录作为框架名称，并在该目录下创建了一些目录用于框架代码和应用代码的隔离。

**提醒：停止实验后，注意保存环境，下次实验中需要继续使用**





---
show: step
version: 1.0 
---


# 自动加载和框架核心

## 一、实验介绍

本节实验内容主要介绍了类的自动加载和框架核心类 Application，以及启动框架时加载用户自定义启动项。

### 实验知识点

实验中会涉及到以下知识点：

- 类自动加载
- 面向对象

## 二、实验步骤

实验主要包括以下几个步骤：

1. 类自动加载
2. 框架核心
3. 注册启动项

### 框架类简介

所有与框架本身相关的代码放在 `core` 目录下，通常会有以下几个类

- `Application`。框架的核心类，其主要方法 `run()` 用于启动项执行，插件方法执行，路由解析，调用控制器方法，输出结果。
- `Config`。和配置文件相关，主要执行加载配置文件和读取配置文件。
- `Controller`。所有控制器的父类，定义一些公共控制器方法，与页面渲染，错误定义等，
- `Model`。模型类，用于和数据库交互，如连接，增删改差等操作。
- `Plugin`。所有插件类的父类，定义了两个抽象方法，路由前方法和路由后方法，所有插件必须实现这两个方法。
- `Router`。路由解析类，可以根据自身设置路由解析模式。
- `View`。视图类，用于视图解析，数据加载等操作。

此外，在该目录下还有一个专门用于分发器的目录 `dispatcher`，主要包括三个类

- `Dispatcher`。所有类的父类，定义了全局操作方法。
- `Container`。可以保存子类实例，通常所有类继承该类。
- `Box`。不能保存子类实例，每次需要重新创建，如数据对象 ORM 需要继承该类，因为每次操作需要初始化所有变量。

####  框架类视频讲解 

```video
http://labfile.oss-cn-hangzhou.aliyuncs.com/courses/1099/week1/5.%E6%A0%B8%E5%BF%83%E7%B1%BB%E6%96%87%E4%BB%B6%E4%BB%8B%E7%BB%8D.mp4
```

### 类自动加载:

在使用类的时候会用 `require` 或 `include` 将类加载进来，但是每次都手动加载，会导致一系列问题，比如类重复加载，管理困难等。所以解决上述问题，我们使用单独的一个文件来完成所有类的自动加载。

#### spl_autoload_register() 

该函数可以注册任意数量的自动加载器，当使用尚未被定义的类（class）和接口（interface）时自动去加载。通过注册自动加载器，脚本引擎在 PHP 出错失败前有了最后一个机会加载所需的类。

>尽管 `__autoload()` 函数也能自动加载类和接口，但更建议使用 spl_autoload_register() 函数。 `spl_autoload_register()` 提供了一种更加灵活的方式来实现类的自动加载（同一个应用中，可以支持任意数量的加载器，比如第三方库中的）。因此，不再建议使用 __autoload() 函数，在以后的版本中它可能被弃用。


在 `core` 目录下创建一个 `Autoload.php` 文件

```linux
$ cd tinyphp/core
$ touch Autoload.php
```
编辑 `Autoload.php`

```php
<?php
$path = [
    'core' => 'core',
    'app' => 'app',
    'controller' => 'app/controllers',
    'model' => 'app/models',
    'view' => 'app/views',
    'dispatcher' => 'core/dispatcher',
];

/**
 * 参数 $class 表示自动加载的类名
 * 
 * 匿名函数中使用 use 可以使用外部变量
 */
spl_autoload_register(function($class) use ($path) {

    //解析类名，如果使用了命名空间，则会查找 $path 中对于的路径
    $position = strripos($class,'\\');

    $key = substr($class,0,$position);
    $value = $path[$key] ?? '';

    $file = substr($class,$position+1).'.php';

    require APP_PATH.'/'.$value.'/'.$file;
});
```
`spl_autoload_register()` 主要实现类自动加载，参数使用的是匿名函数，通过关键字 `use` 可以使用外部变量 $path，其作用是提供命名空间和路径的对应关系，例如使用 `namespace controller`，对应的类在路径 `app/controllers`。

使用方式为，在入口文件 index.php 中，直接 require 该文件即可。

```php
<?php

define('APP_PATH',dirname(__DIR__));

require APP_PATH.'/core/Autoload.php';
```

```checker
- name: 检查是否编辑文件 Autoload.php
  script: |
    #!/bin/bash
    grep spl_autoload_register /home/shiyanlou/tinyphp/core/Autoload.php
  error: | 
    我们发现您还没有编辑 Autoload.php
```

####  类自动加载视频讲解: 

```video
http://labfile.oss-cn-hangzhou.aliyuncs.com/courses/1099/week1/6.%E8%87%AA%E5%8A%A8%E5%8A%A0%E8%BD%BD.mp4
```




### 框架核心

在`core` 文件夹下创建一个名为 `Application.php` 并编辑内容

```php
<?php

namespace core;

class Application
{
    public function run()
    {
    }
}

```

`Application` 类中 `run()` 方法主要实现核心请求，如路由解析，控制器调用等。

```checker
- name: 检查是否编辑文件
  script: |
    #!/bin/bash
    grep run /home/shiyanlou/tinyphp/core/Application.php
  error: | 
    我们发现您还没有编辑 Application.php
```





### 注册启动项

有时候需要在框架启动时，额外加载一些应用配置或用户定义的方法，比如定义常量，加载自定义辅助函数等。

我们在应用目录 `app` 中创建一个启动脚本 `Bootstrap.php`，规定该脚本内所有以 `init` 的方法都会被调用，并在 `Application` 类的构造函数中调用该脚本

创建 `app/Bootstrap.php`，编辑内容

```php
<?php 

namespace app;

class Bootstrap
{
    /**
     * 所有以 init 开始的函数都会被依次调用
     *
     */
    public function initConst()
    {
        echo 'Bootstrap : 1'.PHP_EOL;
    }
    
    public function initHelper()
    {
        echo 'Bootstrap : 2'.PHP_EOL;
    }
}
```
在 `Application` 类中添加启动项

- 在构造函数 `__construct()` 中获取 `Bootstrap` 实例。
- 在 `run()` 方法中遍历 `Bootstrap` 类中以 `init` 开头的方法并执行。

```php
<?php
//这里用...省略之前的代码，在实际操作中，需要完整的代码，不然会报错
...
    public $boot;
    
    public function __construct()
    {
        /**
         * \app\为命名空间，
         * 第一个\表示在根目录，否在表示在当前命名空间下的子目录
         * 
         * 例如，app\Bootstrap() 表示在类在 tinyphp/core/app 目录下
         * \app\Bootstrap() 则表示在 tinyphp/app 目录下
         */
        $this->boot = new \app\Bootstrap();
    }
    public function run()
    {
        /**
         * 执行启动项
         * 所有init开头的方法都会被调用
         *
         */
        foreach(get_class_methods($this->boot) as $func) {
            if (0 === strpos($func, 'init')) {
                call_user_func([$this->boot,$func]);
            }
        }
    }
...
```
```checker
- name: 检查是否创建 Bootstrap.php
  script: |
    #!/bin/bash
    ls -l /home/shiyanlou/tinyphp/app/Bootstrap.php
  error: | 
    我们发现您还没有创建 Bootstrap.php
```

### 测试启动项

在入口文件 index.php 中启动框架，并执行 `run()` 方法

```php
<?php

use \core\Application;

define('APP_PATH',dirname(__DIR__));

require APP_PATH.'/core/Autoload.php';

(new Application())->run();
```

进入 `public` 目录，两种方式执行 php
- 命令行

```linux
$ php index.php
Bootstrap : 1
Bootstrap : 2
```
- 使用 PHP 内置服务器

```linux
$ php -S localhost:8080
```
在浏览器输入 http://localhost:8080 , 结果如下

```html
Bootstrap : 1Bootstrap : 2
```
从结果可以看出，`Application` 类在实例化的时候执行构造函数，构造函数中完成了启动项的方法调用。

```checker
- name: 检查是否编辑 index.php
  script: |
    #!/bin/bash
    ls -l /home/shiyanlou/tinyphp/public/index.php
  error: | 
    我们发现您还没有编辑 index.php
```

####  框架核心+启动项视频讲解: 

```video
http://labfile.oss-cn-hangzhou.aliyuncs.com/courses/1099/week1/7.%E6%A1%86%E6%9E%B6%E6%A0%B8%E5%BF%83%E5%92%8C%E5%90%AF%E5%8A%A8%E9%A1%B9.mp4
```



## 三、总结

本节实现了一个简单的类自动加载和框架的核心类，在启动时加载用户启动项，在 `app/Bootstrap.php` 中所有以 `init` 开头的方法都会被依次调用。



---
show: step
version: 1.0 
---

# 分发器

## 一、实验介绍

在本节实验之前，我们都是通过 `new` 关键字创建对象，如入口文件的 `new Application()`，以及 `Application` 类的构造器中 `new Bootstrap()`。这节实验主要介绍分发器的概念，通过分发器来实现全局类的管理和实例化操作。

### 实验知识点

实验中会涉及到以下知识点：

- 分发器
- 抽象类
- 延迟静态绑定
- Static 关键字

## 二、实验步骤

- 容器和盒子
- 使用规则
- 分发器类
- 使用分发器

### **容器和盒子**

我们来看一个概念`分发器`，英文叫`dispather`，它的主要作用是管理所有类的实例化操作。在程序中，有时需要大量创建对象实例，有时需要使用相同的实例，因此如果重复创建实例会额外给程序带来开销和管理的负担。所以我们使用了分发器的概念，用于专门管理所有类的实例化操作。

- 容器 Container。容器中的实例可以重复使用
- 盒子 Box。盒子中的实例不能重复使用，每次会创建新的实例

在 `core` 目录下创建目录 `dispatcher`，并创建文件 Dispatcher.php，Container.php，Box.php

```linux
$ cd tinyphp/core
$ mkdir dispatcher
$ cd dispatcher
$ touch Dispatcher.php Container.php Box.php
```

Dispatcher 是一个抽象类，定义了一个抽象方法 `getInstance()`，用于获取子类实例。子类 Container 和 Box 分别继承 Dispatcher 并实现 `getInstance()` 方法，不同点在于，容器中该方法需要存放子类实例，盒子中直接进行实例化操作。

```checker
- name: 检查是否创建文件
  script: |
    #!/bin/bash
    ls -l /home/shiyanlou/tinyphp/core/dispatcher/Container.php
  error: | 
    我们发现您还没有创建 Container.php
```

### Dispatcher 类

`Dispatcher` 类主要实现：

- 定义抽象方法 `getInstance()`

```php
abstract function getInstance();
```
注意抽象方法必须使用关键字 `abstract` 声明，并且不能包含函数体（中括号包括的部分）

- 接收所有静态方式请求 `__callStatic()` 和定义初始化函数 `init()`

```php
public static function __callstatic($method, $args)
{
    //1
    $instance = static::getInstance();

    //2
    if (method_exists($instance, 'init')) {
        call_user_func_array([$instance, 'init'], $args);
    }
     
    //3
    return call_user_func_array(array($instance, $method), $args);
}
```
该方法的作用是，分发器入口。所有通过静态方式调用类方法都会执行该函数，如 `Config::get('db')`。

1，第一行 `static::getInstance();` 执行子类的 `getInstance()` 方法，因此需要在 `Container` 和 `Box` 中分别实现该方法。

2，定义初始化函数，所有子类如果定义了 `init()` 方法都会被执行，注意和构造函数 `__construct()` 的区别，初始化函数在每次方法调用前都会被执行，构造函数只在实例化的时候（ `new` 操作）才执行。

3，通过 `call_user_func_array()` 调用自身存在的方法，该方法需要声明为 `protected`

- 使用延迟静态绑定实例化子类 

```php
public static function newObject()
{
    return new Static;
}
```
该函数的作用是实例化操作，在子类 `Container` 和 `Box` 中调用，`new Static` 返回创建具体执行操作的该子类实例，如 `Config::get()` 那么返回 `Config` 类的实例，`Router::start()` 返回 `Router` 类的实例。

- 定义获取类实例函数 `register()`

```php
public static function register()
{
    return static::getInstance();
}
```
该函数获取子类实例。

具体代码实现如下

```php
<?php

namespace dispatcher;

/**
 * 抽象类，定义了抽象方法 getInstance()
 *
 */
abstract class Dispatcher
{
    //抽象方法，子类必须实现
    abstract function getInstance();

    /**
     * 魔术方法。调用不存在的静态方法时调用
     *
     */
    public static function __callstatic($method, $args)
    {
        $instance = static::getInstance();

        /**
         * 定义初始化函数，在每次方法调用前都会调用
         * 注意和构造函数 __construct 区分，构造函数是在
         * 类实例化的时候调用
         *
         */
        if (method_exists($instance, 'init')) {
            call_user_func_array([$instance, 'init'], $args);
        }

        return call_user_func_array(array($instance, $method), $args);
    }

    /**
     * 实例化子类
     * 延迟静态绑定
     *
     */
    protected function newObject()
    {
        return new Static;
    }
    
    /**
     * 获取子类实例
     *
     */
    private function register()
    {
        return static::getInstance();
    }

}
```

```checker
- name: 检查是否编辑 Dispatcher.php
  script: |
    #!/bin/bash
    grep register /home/shiyanlou/tinyphp/core/dispatcher/Dispatcher.php
  error: | 
    我们发现您还没有编辑 Dispatcher.php
```

### Container 类

`Container` 类继承 `Dispatcher`，同时实现 `getInstance()` 方法。
该类使用一个静态变量 `$container` 来保存所有类实例，当再次调用该实例时直接返回，无需在实例化操作。

编辑 `Container.php`

```php
<?php

namespace dispatcher;

/**
 * 继承 Dispatcher 必须实现 getInstance() 方法
 *
 */
class Container extends Dispatcher
{
    //存储子类实例
    public static $container = [];

    /**
     * 实现父类抽象方法
     *
     * 如果容器中已存在子类实例，直接返回
     */
    public function getInstance()
    {
        //获取子类名称
        $class = get_called_class();

        if (!isset(self::$container[$class]) ||
                !self::$container[$class] instanceof $class) {

            self::$container[$class] = self::newObject();
        }
        return self::$container[$class];
    }
}
```

`get_called_class()` 获取调用子类的名称。


```checker
- name: 检查是否编辑 Container.php
  script: |
    #!/bin/bash
    grep getInstance /home/shiyanlou/tinyphp/core/dispatcher/Container.php
  error: | 
    我们发现您还没有编辑 Container.php
```

### Box 类

和 `Container` 类一样，`Box` 继承 `Dispatcher`，同时实现 `getInstance()` 方法

编辑 `Box.php`

```php
<?php

namespace dispatcher;

class Box extends Dispatcher
{
    /**
     * 该类的子类都会重新实例化
     *
     */
    public function getInstance()
    {
        return self::newObject();
    }
}
```

```checker
- name: 检查是否编辑 Box.php
  script: |
    #!/bin/bash
    grep getInstance /home/shiyanlou/tinyphp/core/dispatcher/Box.php
  error: | 
    我们发现您还没有编辑 Box.php
```


#### 分发器视频讲解: 

```video
http://labfile.oss-cn-hangzhou.aliyuncs.com/courses/1099/week1/8.%E5%88%86%E5%8F%91%E5%99%A8%E7%AE%80%E4%BB%8B.mp4
```

### 使用分发器

#### 使用规则

- 一个类要使用分发器，必须继承 `Container` 或 `Box` 类。
- 类方法通过静态方式调用，同时`::`符号后面直接接的方法必须声明为 `protected`。
- 通过 `register()` 方法获取类实例。

一个简单的例子
``` php
<?php

use dispatcher\Container;

class Test extends Container
{
    protected function sayHi()
    {
        echo 'Hi';
    }
}
```
可以通过 `Test::sayHi();` 调用类方法（非静态方法）。通过 `Test::register();` 可以获取 `Test` 类实例。

上节实验中实现了框架核心类 `core/Application.php`，现在在 `Application` 类中使用分发器，继承 `Container`，通过 `register()` 方法获取实例。

编辑 `core/Application.php`
```php
<?php

namespace core;

use dispatcher\Container;

class Application extends Container
{
...//内容省略
}
```
#### 在核心类中使用

- 入口文件

修改 `public/index.php`

```php
<?php

use \core\Application;

define('APP_PATH',dirname(__DIR__));

require APP_PATH.'/core/Autoload.php';

Application::register()->run();
```

- 启动文件

修改 `app/Bootstrap.php`

```php
<?php

namespace app;

use dispatcher\Container;

class Bootstrap extends Container
{
    public function initConst()
    {
        echo 'Bootstrap : 1'.PHP_EOL;
    }

    public function initHelper()
    {
        echo 'Bootstrap : 2'.PHP_EOL;
    }
}
```
- 核心类 `Application`

编辑 `core/Application.php`

```php
<?php

namespace core;

use dispatcher\Container;

class Application extends Container
{
    public $boot;
    
    public function __construct()
    {
        $this->boot = \app\Bootstrap::register();
    }
    ...
}
```
在 `public` 执行

```linux
$ php index.php
Bootstrap : 1
Bootstrap : 2
```

```checker
- name: 检查是否编辑 Application.php
  script: |
    #!/bin/bash
    grep register /home/shiyanlou/tinyphp/core/Application.php
  error: | 
    我们发现您还没有在 Application.php 中使用分发器
```



#### 分发器使用讲解: 
```video
http://labfile.oss-cn-hangzhou.aliyuncs.com/courses/1099/week1/9.%E5%88%86%E5%8F%91%E5%99%A8%E4%BD%BF%E7%94%A8.mp4
```



## 三、总结

本结主要介绍了分发器的概念，实现了一个简单的类管理机制，可以通过静态方式调用对象方法。分发器类 `Container` 和 `Box` 的区别在于是否保存子类实例。通常情况下使用 `Container`，但是在数据对象中需要继承 `Box`





---
show: step
version: 1.0 
---

# 配置文件

## 一、实验介绍

本节实验内容主要介绍配置文件使用规则，然后创建了一个内置 `Config` 类来解析用户配置文件。 

### 实验知识点

本节实验中会涉及到以下知识点：

- 数组
- 循环
- 分发器使用

## 二、实验步骤

- 配置文件路径
- 定义规则
- 使用规则
- Config 类

### 配置文件路径

通常一个框架会提供一种获取配置文件的操作类，该类的作用是，加载用户自定义配置文件，读取和设置配置文件等操作。

我们规定用户配置文件目录为 `tinyphp/app/conf`，在该目录下，`config.php` 是默认的配置文件，此外，还可以在该目录下创建其他配置文件，例如 `db.php`，`server.php` 等，所有其他文件也会被自动加载。

### 定义规则

配置文件内容直接返回数组。示例

`app/conf/config.php`

```php
<?php
return [
    'default' => [
        'controller' => 'index',
        'action' => 'index',
        'router' => 'querystring',
    ],
    'debug' => true,
    'cache' => 'redis',
];
```

`app/conf/db.php`

```php
<?php
return [
    'mysql' => [
        'host'      => '127.0.0.1',
        'username'  => '',
        'password'  => '',
        'db'        => '',
        'port'      => 3306,
        'charset'   => 'utf8',
        'prefix'    => ''
    ],
    'redis' => [
        'scheme' => '',
        'host'   => '',
        'port'   => '',
    ]
];

```







### 使用规则

通过 `Config::get($key)` 来获取配置。

- `config.php` 作为默认配置文件可以直接获取该配置文件内容，多维数组通过`.`分隔，例如

``` php
<?php

Config::get('debug');
Config::get('default.route');

```
- `db.php` 或其他自定义配置文件，需要添加文件名前缀，例如

``` php
<?php

Config::get('db.mysql.host');
Config::get('db.redis.scheme');
```

#### 配置文件+使用规则视频讲解: 

```video
http://labfile.oss-cn-hangzhou.aliyuncs.com/courses/1099/week1/10.%E9%85%8D%E7%BD%AE%E6%96%87%E4%BB%B6%E8%A7%84%E5%88%99.mp4
```


### Config 类

`Config` 类主要实现以下功能：

- 加载用户配置文件

```php
<?php

namespace core;

use dispatcher\Container;

class Config extends Container
{
    private $conf;
    
    public function __construct()
    {
        $conf = [];
        $path = APP_PATH.'/app/conf/';
        foreach (scandir($path) as $file) {
            if (substr($file,-4) != '.php') {
                continue;
            }
            $filename = $path.$file;
            
            if ($file == 'config.php') {
                //1
                $conf += require $filename;
            } else {
                //2
                $k = explode('.', $file)[0];
                $conf[$k] = require $filename;
            }
        }
        $this->conf = $conf;
    }
```

由于继承 `Container`，构造函数只执行一次，因此在 `__construct()` 中加载配置文件可以避免重复加载。

除了 `config.php` 外的其他配置文件，需要用文件名作为 `key`。

- 解析多维 key 和使用默认值

```php
//1
protected function get($key = null, $default=null)
{
    //2
    if (empty($key)) {
        return $this->conf;
    }
    //3
    if (strpos($key, '.')) {
        $conf = $this->conf;
        foreach (explode('.', $key) as $k) {
            $conf = $conf[$k] ?? $default;
        }
        return $conf;
    }
    //4
    return $this->conf[$key] ?? $default;
}
```

1. 使用 `::` 执行方法时，该方法需要声明为 `protected`，如 `Config::get($key);`。
2. 如果 `$key` 为空，则返回所有配置。
3. 通过 `.` 来解析多维数组配置。
4. 如果为找到对应值，根据是否设置默认值返回结果。

具体代码如下

```php
<?php

namespace core;

use dispatcher\Container;

class Config extends Container
{
    private $conf;
    
    /**
     * 构造函数，获取配置文件
     * 
     */
    public function __construct()
    {
        $conf = [];
        $path = APP_PATH.'/app/conf/';
        foreach (scandir($path) as $file) {
            if (substr($file,-4) != '.php') {
                continue;
            }
            $filename = $path.$file;
            
            if ($file == 'config.php') {
                $conf += require $filename;
            } else {
                $k = explode('.', $file)[0];
                $conf[$k] = require $filename;
            }
        }
        $this->conf = $conf;
    }

    /**
     * 获取配置
     *
     */
    protected function get($key = null,$default=null)
    {
        //返回所有配置
        if (empty($key)) {
            return $this->conf;
        }
        //通过.获取层级关系
        if (strpos($key, '.')) {
            $conf = $this->conf;
            foreach (explode('.', $key) as $k) {
                $conf = $conf[$k] ?? $default;
            }
            return $conf;
        }
        return $this->conf[$key] ?? $default;
    }
}
```

可以在框架中任何地方使用 `Config::get($key, $default_value);` 来获取配置文件。

#### Config类视频讲解: 
```video
http://labfile.oss-cn-hangzhou.aliyuncs.com/courses/1099/week1/11.%E9%85%8D%E7%BD%AE%E6%96%87%E4%BB%B6%E7%B1%BB%E5%92%8C%E4%BD%BF%E7%94%A8.mp4
```



```checker
- name: 检查是否编辑 Config.php
  script: |
    #!/bin/bash
    grep get /home/shiyanlou/tinyphp/core/Config.php
  error: | 
    我们发现您还没有编辑 Config.php
```

## 三、总结

本结主要介绍了配置文件的规则，和实现这些规则的 `Config` 类，该类继承 `Container`，只在第一次使用时进行实例化操作，因此在构造函数中加载所有配置文件，之后所有操作配置文件读取相当于数组查找。





---
show: step
version: 1.0 
---

# 反射和依赖注入

## 一、实验介绍

本节实验主要介绍 PHP 的高级特性反射和依赖注入

### 实验知识点

实验中会涉及到以下知识点：

- 反射
- 依赖注入
- 使用分发器

## 二、实验步骤

实验主要包括以下几个步骤

- 使用反射调用方法
- 依赖注入

### 反射

反射是操纵面向对象范型中元模型的API，可以导出或提取出关于类、方法、属性、参数等的详细信息，包括注释。

由于在框架中所有类都继承 `Dispatcher`，因此可以在 `Dispatcher` 中实现一个 `call()` 方法来代替 `call_user_func_array()`

编辑 `core/dispatcher/Dispatcher.php`，添加 `call()` 方法，该方法使用反射来操作类方法和参数。

```php
<?php
...
    protected function call($method, $args = null)
    {
        $object = get_called_class();
        $reflect = new \ReflectionMethod($object, $method);
    
        $params = [];
        foreach ($reflect->getParameters() as $need) {
            if (!$need->isDefaultValueAvailable()
            && !isset($args[$need->name])) {
                Throw new \Exception('action [ '.$method.' ] needs params [ $'.$need->name.' ]');
            }
            $params[$need->name] = $args[$need->name] ?? $need->getDefaultValue();
            }
        }
        return $reflect->invokeArgs($object::register(), $params);
    }
...
```
使用方式为 `类名::call(方法名,参数)`，在 `core/Application.php` 中替换 `call_user_func_array()`

```php
<?php
...
    public function run()
    {
        ...
        
        //echo call_user_func_array([$controller::register(),$action],$args);
        
        echo $controller::call($action,$args);
    }
...
```

### 依赖注入

有时我们需要在方法参数中使用某个类实例，如果使用 `call_user_func_array()` 则会报错，例如

在 `UserController` 的 `getInfo()` 方法中注入 `Config` 类

```php
<?php

namespace controller;

use core\Container;
use core\Config;

class UserController extends Controller
{
    public function getInfo(Config $conf)
    {
        echo '<pre>';
        print_r($conf);
    }
    
}
```
浏览器输入（注意打开 PHP 内置服务器）http://localhost:8080/user/info

结果如下

```html
Fatal error: Uncaught ArgumentCountError: Too few arguments to function controller\UserController::getInfo(), 0 passed and exactly 1 expected in ...
```

此时，可以使用反射实现依赖注入，在遍历参数的时候，检测参数类型，如果为对象，则将该对象实例存入参数中。

修改 `call()` 方法，添加依赖注入

```php
<?php
...
    protected function call($method, $args = null)
    {
        $object = get_called_class();
        $reflect = new \ReflectionMethod($object, $method);
    
        $params = [];
        foreach ($reflect->getParameters() as $need) {
            //依赖注入
            if ($obj = $need->getClass()->name) {
                    $params[$need->name] = $obj::register();
            //默认参数
            } else {
                if (!$need->isDefaultValueAvailable()
                && !isset($args[$need->name])) {
                    Throw new \Exception('action [ '.$method.' ] needs params [ $'.$need->name.' ]');
                }
                $params[$need->name] = $args[$need->name] ?? $need->getDefaultValue();
            }
        }
        return $reflect->invokeArgs($object::register(), $params);
    }
...
```
遍历参数的时候，使用 `getClass()->name` 来判断参数类型，如果参数为对象，则返回对象名，否则返回 `null`。

刷新浏览器，结果如下

```html
core\Config Object
(
    [conf:core\Config:private] => Array
        (
            [default] => Array
                (
                    [controller] => index
                    [action] => index
                    [route] => querystring
                )

            [debug] => 1
            [db] => Array
                (
                    [pdo] => Array
                        (
                            [ms] => mysql
                            [host] => 127.0.0.1
                            [user] => root
                            [password] => mysql@zz1530
                            [database] => shiyanlou
                        )

                )

        )

)
```
在调用 `getInfo()` 方法的时候，将 `Config` 类注入到该方法中。

#### 魔术方法 __call()

使用依赖注入后，声明为 `protected` 的方法无法通过实例调用，因此在 `Dispatcher` 类中添加魔术方法 __call()

```php
public function __call($method, $args)
{
    return call_user_func_array([static::getInstance(),$method],$args);
}
```
这个方法保证以前通过 `::` 方式调用的方法，也可以通过实例调用，如

```php
Config::get('db');
```
添加依赖注入后，可以通过实例调用 `get()` 方法。

```php
public function getIndex(Config $conf)
{
    $conf->get('db');
}
```

```checker
- name: 检查是否编辑文件 Dispatcher
  script: |
    #!/bin/bash
    grep getClass /home/shiyanlou/tinyphp/core/dispatcher/Dispatcher.php
  error: | 
    我们发现您还没有编辑 Dispatcher.php
```

#### 反射依赖注入视频讲解: 

```video
http://labfile.oss-cn-hangzhou.aliyuncs.com/courses/1099/week1/17.mp4
```


## 三、总结

本结主要反射和依赖注入，通过反射可以在方法中添加对象，使用的时候避免再去实例化操作





---
show: step
version: 1.0 
---

# 路由解析

## 一、实验介绍

本节实验内容主要介绍两种默认路由，查询字符串和路径 URL，然后介绍了控制器的命名规范和使用规则。

### 实验知识点

实验中会涉及到以下知识点：

- 路由概念
- HTTP 请求方式
- 使用分发器
- call_user_func_array()

## 二、实验步骤

本节实验包括以下几个步骤

- 路由方式
- 控制器
- 路由解析
- 测试路由
- 调用控制器方法

### 路由方式

路由是一个框架中必不可少的组件，其作用是把 URL 按照预定规则解析到特定控制器中。

我们在这里定义了两种路由规则：

- 查询字符串。在路径后面使用问号加参数，多个参数用 `&` 分隔。在配置文件使用 `querystring` 表示

``` linux
#控制器/方法?参数1=值1&参数2=值2
http://domain/user/info?name=php&chapter=10
```
- 路径，以路径的形式将参数和值添加到后面，中间用 `/` 分隔。配置中使用 `restful`

``` linux
#控制器/方法/参数1/值1/参数2/值2
https://domain/user/info/name/php/chapter/100
```

### 控制器

#### 主控制器

在目录 `core` 创建 `Controller.php`，该类继承 `Container`

``` php
<?php

namespace core;

use dispatcher\Container;

class Controller extends Container
{
    
}
```
主控制器可以添加控制器公共方法，如页面渲染 `render()`，错误代码等，所有控制器必须继承主控制器。由于主控制器继承 `Container`，因此，控制器也是分发器的子类，可以通过 `register()` 获取实例。

#### 控制器类

- 类命名规则

控制器命名遵循大写开头的驼峰命名规则，并且默认添加后缀 `Controller`，控制器文件命名和类命名一样，如控制器类 `UserController`，其文件命名为 `UserController.php`。

- 方法命名规则

方法命名遵循小写开头的驼峰命名规则，并且默认添加`请求方式`（如，get，post，put等）前缀，如 `getIndex()`，`postUpdate()`。

以上例 `UserController` 为例

``` php
<?php

namespace controller;

use core\Controller;

class UserController extends Controller
{
    /**
     * HTTP 请求方式为 GET 时有效
     * url 为 /user/info
     *
     */
    public function getInfo()
    {
        
    }

    /**
     * HTTP 请求方式为 POST 时有效
     * url 为 /user/update
     *
     */
    public function postUpdate()
    {
        
    }
}
```

```checker
- name: 检查是否编辑 Controller.php
  script: |
    #!/bin/bash
    grep extends /home/shiyanlou/tinyphp/core/Controller.php
  error: | 
    我们发现您还没有编辑 Controller.php
```

### 路由解析 

在 `core` 目录下创建 `Router.php`

```linux
$ cd tinyphp/core
$ touch Router.php
```
在构造函数中定义变量

```php

<?php

namespace core;

use dispatcher\Container;

class Router extends Container
{
    public $method;
    public $uri;
    public $path;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->path = $_SERVER['PATH_INFO'];
    }
}
```

常见 `$_SERVER` 字段

1. `$_SERVER['PATH_INFO']` URL的路径信息，如 /user/info
2. `$_SERVER['REQUEST_METHOD']` 请求方法，如 POST，GET
3. `$_SERVER['REQUEST_URI']` 完整 URL，如 /user/info?id=1&name=Lucy

在 `start()` 方法中解析 URL

```php
protected function start()
{
    /**
     * 也可以写成 Config::get('default.route','querystring');
     *
     */
    $route = Config::get('default.route') ?? 'querystring';

    //解析 controller 和 action
    $path = explode('/',trim($this->path,'/'));

    if (empty($path[0])) {
        $path[0] = Config::get('default.controller','index');
    }
    $controller = ucfirst($path[0]).'Controller';

    //获取请求方法
    $method = strtolower($this->method);
    $action = $method.ucfirst($path[1] ?? Config::get('default.action','index'));
    //获取参数
    $args = [];
    if (method_exists($this,$route)) {
        $args = call_user_func_array([$this,$route],[$this->uri]);
    }
    return ['controller'=>$controller,'action'=>$action,'args'=>$args];
}
```
`querystring()` 参数解析

```php

private function querystring($url)
{
    $urls = explode('?', $url);
    if (empty($urls[1])) {
        return [];
    }
    $param_arr = [];
    $param_tmp = explode('&', $urls[1]);
    if (empty($param_tmp)) {
        return [];
    }
    foreach ($param_tmp as $param) {
        if (strpos($param, '=')) {
            list($key,$value) = explode('=', $param);
            //变量名是否复合规则
            if (preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $key)) {
                $param_arr[$key] = $value;
            }
        }
    }
    return $param_arr;
}
```
querystring 的参数为 `?` 后面的部分，多个参数用 `&` 分隔。

`restful()` 参数解析

```php
private function restful($url)
{
    $path = explode('/', trim(explode('?', $url)[0], '/'));
    $params = [];
    $i = 2;
    while (1) {
        if (!isset($path[$i])) {
            break;
        }
        $params[$path[$i]] = $path[$i+1] ?? '';
        $i = $i+2;
    }
    return $params;
}

```
restful 的参数为方法后面的路径。

完整代码如下： 

```php
<?php

namespace core;

use dispatcher\Container;

class Router extends Container
{
    public $method;
    public $uri;
    public $path;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->path = $_SERVER['PATH_INFO'];
    }

    protected function start()
    {
        $route = Config::get('default.route') ?? 'querystring';
    
        //解析 controller 和 action
        $path = explode('/',trim($this->path,'/'));
    
        if (empty($path[0])) {
            $path[0] = Config::get('default.controller','index');
        }
        $controller = ucfirst($path[0]).'Controller';
    
        //获取请求方法
        $method = strtolower($this->method);
        $action = $method.ucfirst($path[1] ?? Config::get('default.action','index'));
        
        //获取参数
        $args = [];
        if (method_exists($this,$route)) {
            $args = call_user_func_array([$this,$route],[$this->uri]);
        }
        return ['controller'=>$controller,'action'=>$action,'args'=>$args];
    }
    
    /**
     * 查询字符串参数
     * ？后，参数通过&&分隔
     *
     */
    private function querystring($url)
    {
        $urls = explode('?', $url);
        if (empty($urls[1])) {
            return [];
        }
        $param_arr = [];
        $param_tmp = explode('&', $urls[1]);
        if (empty($param_tmp)) {
            return [];
        }
        foreach ($param_tmp as $param) {
            if (strpos($param, '=')) {
                list($key,$value) = explode('=', $param);
                //变量名是否复合规则
                if (preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $key)) {
                    $param_arr[$key] = $value;
                }
            }
        }
        return $param_arr;
    }
    /**
     * 路径参数
     * 控制器/方法/参数1/值1/参数2/值2
     *
     */
    http://domain/user/info/name/entner?name=php&chapter=10
    private function restful($url)
    {
        $path = explode('/', trim(explode('?', $url)[0], '/'));
        $params = [];
        $i = 2;
        while (1) {
            if (!isset($path[$i])) {
                break;
            }
            $params[$path[$i]] = $path[$i+1] ?? '';
            $i = $i+2;
        }
        return $params;
    }
}
```

路由调用方式为

```php
<?php

$router = Rouer::start();
```
返回结果包括控制器，方法和参数


```checker
- name: 检查是否编辑 Router.php
  script: |
    #!/bin/bash
    grep querystring /home/shiyanlou/tinyphp/core/Router.php
  error: | 
    我们发现您还没有编辑 Router.php
```

### 测试路由
在配置文件 `app/conf/config.php` 中设置默认路由为 `querystring`，

```php
<?php

return [
    'default' => [
        'controller' => 'index',
        'action' => 'index',
        'route' => 'querystring',//还可以设置为 restful
    ],
    'view' => [
        'dir' => 'layout',
        'file' => 'base',
    ]
];
```

在 `core/Application.php` 文件中 `run()` 方法实现路由调用

```php
<?php
...
public function run()
{
    $router = Router::start();
    echo '<pre>';
    print_r($router);
}
...
```
启动 PHP 内置服务器
```linux
$ cd tinyphp/public
$ php -S localhost:8080
```
在浏览器中输入 http://localhost:8080/course/document?name=php&&chapter=10
输出结果为

```linux
Array
(
    [controller] => CourseController
    [action] => getDocument
    [args] => Array
        (
            [name] => php
            [chapter] => 10
        )
)
```
同理可以测试 `restful` 路由规则。

### 调用控制器方法

路由解析后，获得需要调用的控制器名，方法和参数。由于控制器继承分发器后，可以通过 `register()` 获取实例，编辑 `core/Applicaiton.php`

```php
<?php

...
public function run()
{
    $router = Router::start();
    //注意使用命名空间
    $controller = "controller\\".$router['controller'];
    $action = $router['action'];
    $args = $router['args'];
    
    echo call_user_func_array([$controller::register(),$action],$args);
}
...
```
```checker
- name: 检查是否编辑 Application.php
  script: |
    #!/bin/bash
    grep call_user_func_array /home/shiyanlou/tinyphp/core/Application.php
  error: | 
    我们发现您还没有在 run() 方法内调用控制器
```

通过这种方式可以实现方法调用，但是无法控制方法参数，比如，有时候我们需要在方法参数中使用某个对象实例，术语称为依赖注入，即把需要使用的实例注入到方法中，那么可以通过PHP的高级特性反射来实现。


#### 路由解析视频讲解:

```video
http://labfile.oss-cn-hangzhou.aliyuncs.com/courses/1099/week1/12.%E8%B7%AF%E7%94%B1%E8%A7%A3%E6%9E%90.mp4
```

## 三、总结

本结主要介绍了两种路由方式，可以在配置文件中设置，然后介绍了控制器的命名和使用规则，其中方法名添加 HTTP 请求方式 `GET`，`POST` 等作为前缀。路由解析完成后通过 `call_user_func_array()` 函数实现方法的调用。





---
show: step
version: 1.0 
---


# 视图

## 一、实验介绍

本节实验介绍了 MVC 框架中视图 View 和使用 ob 缓存页面

### 实验知识点

本节实验中会涉及到以下知识点：

- ob 缓存
- 数据提取

## 二、实验步骤

实验主要包括以下步骤：

- 视图目录
- View 类
- 调用规则

### 视图目录

#### 定义路径
我们把所有视图文件放在 `app/views` 目录下，并且按照控制器分类，所有该控制器下的视图文件都放在以控制器命名的文件夹下，例如，`CourseController` 中的视图文件存放目录为 `app/view/course`。

#### 公共页面

此外，我们在 `app/views` 目录下创建一个公共页面，所有控制器页面包含在公共页面中，默认路径为 `app/views/layout/base.php`，可以在配置文件 `app/config.php` 配置其他路径

```php
<?php

return [
    'default' => [
        'controller' => 'index',
        'action' => 'index',
        'route' => 'querystring',
    ],
    'view' => [
        'dir' => 'layout',
        'file' => 'base',
    ]
];
```

`base.php` 中的内容可以自定义，但是必须输出 `$content` 变量，例如
```html
<!DOCTYPE html>
<html>
<head lang="zh">
    <meta charset="UTF-8">
    <title>实验楼</title>
</head>
<body>
<?=$content?>
</body>
</html>
```
所有其他视图文件内容，都保存在 `$content` 中输出。



#### 视图路径视频讲解: 

```video
http://labfile.oss-cn-hangzhou.aliyuncs.com/courses/1099/week1/13.%E8%A7%86%E5%9B%BE%E7%AE%80%E4%BB%8B.mp4
```


### View 类

在 `core` 目录下创建 `View.php`

`View` 类中主要方法 `display()` 主要实现页面加载和缓冲输出的功能。

`display()` 接受 4 个参数

- `string $folder`，视图路径，默认为控制器命名的文件夹。
- `string $filename`，视图文件名。
- `array $data`，传给页面的数据，通过 `extract()` 函数可以将该数组以键名作为变量名，获取键值。例如

```php
<?php

$data = ['a'=>1, 'b'=>2];

extract($data);

echo $a;//1
echo $b;//2
```
- `bool $useLayout`，是否加载公共页面，默认加载。

具体代码如下：

```php
<?php

namespace core;

use dispatcher\Container;

class View extends Container
{
    protected function display(string $folder, string $filename,array $data, $useLayout = false)
    {
        $view_path = APP_PATH.'/app/views/';
        
        $file = $view_path.$folder.'/'.$filename.'.php';

        if (!empty($data)) {
            extract($data);
        }
        ob_start();
        require $file;
        $content = ob_get_clean();

        //加载公共页面 
        if ($useLayout) {
            $conf = Config::get('view');
            
            $common = $view_path.'/'.($conf['dir'] ?? 'layout') .'/'.($conf['file'] ?? 'base').'.php';
            
            ob_start();
            require $common;
            $content = ob_get_clean();
        }
        return $content;
    }
```

注意 `ob_start()` 的使用，通常情况下，使用 require 或 include 加载文件，会立马输出文件内容，此时我们还有其他程序未处理完成，那么可以通过`ob` 来将加载的文件缓存起来，等到需要的时候，通过 `ob_get_clean()` 把缓存的内容输出给变量，然后在返回给页面。

```checker
- name: 检查是否编辑 View.php
  script: |
    #!/bin/bash
    grep display /home/shiyanlou/tinyphp/core/View.php
  error: | 
    我们发现您还没有编辑 View.php
```

### 调用规则

在全局控制器 `Controller` 中实现以下方法

- `display()`，执行 View 类的 display() 方法

```php
<?php
//1
private function display(string $file, array $data, bool $useLayout = true)
{
    //2
    $controller = get_called_class();
    
    $folder = substr($controller,strripos($controller,'\\')+1,-10);
    
    //3
    return View::display(strtolower($folder), $file, $data, $useLayout);
}
```
1. `display()` 为内部方法，故声明为 `private`。接收 3 个参数，分别是文件名 `$file`，数据 `$data`，是否使用公共页面 `$useLayout`。
2. 使用控制器名作为视图目录，`get_called_class()` 获取类名，包括命名空间，所以需要将命名空间部分删除。
3. 调用 `View` 类。

- `render()`

```php
public function render(string $file, array $data = [])
{
    return $this->display($file, $data);
}
```
可以在各个控制器中调用 `$this->render();`。
接收两个参数，渲染的文件名和数据。

- `renderFile()`

```php
public function renderFile(string $file, array $data = [])
{
    return $this->display($file, $data, false);
}

```

在控制器中调用 `$this->renderFile();`。
同样接收两个参数，文件名和数据，但是在调用 `display()` 方法时，传递第三个参数 `false`，因为该方法只加载视图文件本身，不加载公共页面。

具体代码如下：

```php
<?php

namespace core;

use dispatcher\Container;

class Controller extends Container 
{
    /**
     * @param string $file 
     * @param array $data
     *
     */
    public function render(string $file, array $data = [])
    {
        return $this->display($file, $data);
    }

    /**
     * 不加载公共资源
     *
     * @param string $file 
     * @param array $data
     */
    public function renderFile(string $file, array $data = [])
    {
        return $this->display($file, $data, false);
    }
    
    /**
     * 调用 View 类
     * @param string $file 
     * @param array $data
     * @param bool $userLayout
     */
    private function display(string $file, array $data, bool $useLayout = true)
    {
        //默认使用控制器名作为视图目录
        $controller = get_called_class();
        
        $folder = substr($controller,strripos($controller,'\\')+1,-10);
        
        return View::display(strtolower($folder), $file, $data, $useLayout);
    }
}
```

### 测试视图

#### 控制器调用

在控制器 `IndexController.php` 中编写两个方法 `getOne()` 和 `getAll()`

```php
<?php

namespace controller;

use core\Controller;

class IndexController extends Controller
{
    public function getOne()
    {
        return $this->renderFile('index');
    }
    
    public function getAll()
    {
        return $this->render('index');
    }
}
```
#### 编写视图

- 公共页面

编辑 `app/views/layout/base.php`

```php
This is header!
<br>
<?=$content?>
<br>
This is footer!
```

注意公共页面中需要输出 `$content` 变量。

- 控制器页面

创建目录 `app/views/index`

```linux
$ cd app/views/
$ mkdir index
```
编辑 `app/views/index/index.php`

```php
This is index file!
```

启动 PHP 内置服务器

```linux
$ cd /home/shiyanlou/tinyphp/public
$ php -S localhost:8080
```
浏览器输入 http://localhost:8080/index/one

```html
This is index file!
```
输入 http://localhost::8080/index/all

```html
This is header!
This is index file!
This is footer!
```

```checker
- name: 检查是否编辑 Controller.php
  script: |
    #!/bin/bash
    grep renderFile /home/shiyanlou/tinyphp/core/Controller.php
  error: | 
    我们发现您还没有编辑 Controller.php
```


#### 视图类视频讲解: 
```video
http://labfile.oss-cn-hangzhou.aliyuncs.com/courses/1099/week1/14.%E8%A7%86%E5%9B%BE%E7%B1%BB%E5%92%8C%E4%BD%BF%E7%94%A8.mp4
```



## 三、总结

本结主要介绍了视图的目录和公共视图，并在主控制器中实现了两个方法 `render()` 和 `renderFile()` 来选择单独输出页面还是和公共页面一起输出





---
show: step
version: 1.0 
---


# 模型

## 一、实验介绍

本节实验介绍了 PDO，使用 PDO 连接数据库，使用预处理执行数据库增删改查。

### 实验知识点

本节实验中会涉及到以下知识点：

- PDO
- 预处理语句
- MySQL

## 二、实验步骤

- 创建数据库和表
- Model 类
- 使用预处理语句
- 数据增删改查

### 创建数据库和表

启动 MySQL，账号为root，密码为空
```linux
$ sudo service mysql start
$ mysql -uroot -p
```
创建数据库 `shiyanlou` 和表 `users`
```linux
mysql> create database shiyanlou;
Query OK, 1 row affected (0.01 sec)

mysql> use shiyanlou;
Database changed

mysql> create table users (id int not null auto_increment primary key, name varchar(50) not null,age int not null,gender int not null, height int not null, weight int not null)engine=innodb default charset=utf8;
Query OK, 0 rows affected (0.02 sec)

mysql> insert into users (`name`,`age`,`gender`,`height`,`weight`) values ("Tom Li",15,1,165,50),("Tom Zhang",16,1,165,50),("Jack Li",15,1,170,55),("Lucy Wang",15,0,150,40),("Max Wang",15,0,155,45);
Query OK, 5 rows affected (0.00 sec)
Records: 5  Duplicates: 0  Warnings: 0

mysql> select * from users;
+----+-----------+-----+--------+--------+--------+
| id | name      | age | gender | height | weight |
+----+-----------+-----+--------+--------+--------+
|  1 | Tom Li    |  15 |      1 |    165 |     50 |
|  2 | Tom Zhang |  16 |      1 |    165 |     50 |
|  3 | Jack Li   |  15 |      1 |    170 |     55 |
|  4 | Lucy Wang |  15 |      0 |    150 |     40 |
|  5 | Max Wang  |  15 |      0 |    155 |     45 |
+----+-----------+-----+--------+--------+--------+
5 rows in set (0.00 sec)
```



#### 数据库建表视频讲解: 

```video
http://labfile.oss-cn-hangzhou.aliyuncs.com/courses/1099/week1/15.%E5%88%9B%E5%BB%BA%E6%95%B0%E6%8D%AE%E5%BA%93%E5%92%8C%E8%A1%A8.mp4
```


### Model 类

模型主要于数据库交互，如增删改查。这里使用 PDO 操作 MySQL，PDO 提供了一个数据访问抽象层，这意味着，不管使用哪种数据库，都可以用相同的函数（方法）来查询和获取数据，同时 PDO 提供了预处理语句，对 SQL 注入等安全问题有很大提升。

#### 数据库配置

编辑 `app/conf/db.php`

```php
<?php

return [
    'pdo' => [
        'ms' => 'mysql',
        'host' => '127.0.0.1',
        'user' => 'root',
        'password' => '',
        'database' => 'shiyanlou',
    ]
];
```
#### 连接数据库

在 `core` 目录下创建 `Model.php`

`Model` 类继承 `Container`，在构造函数中进行连接操作

```php
<?php

namespace core;

use dispatcher\Container;

class Model extends Container
{
    private $db;

    /**
     * 构造函数数据库初始化连连接接
     *
     *
     */
    public function __construct()
    {
        $conf = Config::get('db.pdo');
        try {
            $this->db = new \PDO($conf['ms'].":host=".$conf['host'].";dbname=".$conf['database'], $conf['user'], $conf['password']);
        } catch (PDOException $e) {
            die ("Error!: " . $e->getMessage());
        }
    }
}
```
PDO 类接收 3 个参数

- 数据库地址和数据库名 `$dbms:host=$host;dbname=$database`
- 用户名 `$username`
- 密码 `$password`

#### 预处理语句

通常使用 PDO 的预处理语句代码为

```php
<?php

$db = new PDO($link, $username, $password); 
$stmt = $db->prepare("select * from users where id = ?");
$stmt->execute([3]);
$res = $stmt->fetchAll(PDO::FETCH_CLASS);
```
预处理语句中使用 `?` 占位，在 `execute()` 传入值，注意传入的值必须是数组。

`PDO::FETCH_CLASS` 表示获取结果通过名字返回，如果为使用该参数，则返回的结果包括数字索引和名字两种结果。

在 `Model` 类中创建三个方法分别对应上面的操作

`prepare()`

```php
protected function prepare($sql)
{
    $this->stmt = $this->db->prepare($sql);
    return $this;
}
```
返回 `$this` 的作用是，可以继续操作该对象，例如 `Model::prepare('select * from users where id = ?')->bind([3])->get();`。

`bind()`

```php
public function bind(array $data)
{
    $this->stmt->execute($data);
    return $this;
}
```
`get()`
```php
public function get()
{
    return $this->stmt->fetchAll(\PDO::FETCH_CLASS);
}
```
全部代码如下：

```php
<?php

namespace core;

use dispatcher\Container;

class Model extends Container
{
    private $db;
    private $stmt;
    private $_bind;

    /**
     * 构造函数数据库初始化连连接接
     *
     *
     */
    public function __construct()
    {
        $conf = Config::get('db.pdo');
        try {
            $this->db = new \PDO($conf['ms'].":host=".$conf['host'].";dbname=".$conf['database'], $conf['user'], $conf['password']);
        } catch (PDOException $e) {
            die ("Error!: " . $e->getMessage());
        }
    }
    
    /**
     * 使用 prepare 语句
     *
     */
    protected function prepare($sql)
    {
        $this->stmt = $this->db->prepare($sql);
        return $this;
    }
    /**
     * 绑定参数
     *
     */
    public function bind(array $data)
    {
        $this->stmt->execute($data);
        return $this;
    }
    
    /**
     * 获取结果
     *
     */
    public function get()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_CLASS);
    }
...
```

```checker
- name: 检查是否编辑 Model.php
  script: |
    #!/bin/bash
    grep PDO /home/shiyanlou/tinyphp/core/Model.php
  error: | 
    我们发现您还没有编辑 Model.php
```

### 插入数据

创建 User 控制器，编辑 `app/controllers/UserController.php`
```php
<?php

namespace controller;

use core\Controller;
use core\Model;

class UserController extends Controller
{
    /**
     *  在 users 表中插入一条数据
     *
     */
    public function postInsert()
    {
        $keys = array_keys($_POST);
        $values = array_values($_POST);
        $i=0;
        $prepare = '';
        while(isset($keys[$i])) {
            $prepare .= '?,';
            $i++;
        }
        return Model::prepare("insert into users (".implode($keys,',').") values (".trim($prepare,',').")")
            ->bind($values)
            ->get();
    }
}
```
在命令行使用 curl 执行 post 操作

```linux
$ curl -d "name=Lily&age=12&gender=0&weight=52&height=170" localhost:8080/user/insert
1
```
返回1表示插入成功

### 查询数据

```php
<?php
...
/**
 * 获取 id 为 1 的数据
 *
 */
public function getOne()
{
    $res = Model::prepare("select * from users where id = ?")
            ->bind([1])
            ->get();
    print_r($res);
}
/**
 * 获取所有数据
 *
 */
public function getAll()
{
    $res = Model::prepare("select * from users")->bind([])->get();
    print_r($res);
}
...
```
浏览器输入 http://localhost:8080/user/one
```html
Array
(
    [0] => stdClass Object
        (
            [id] => 1
            [name] => Tom Li
            [age] => 15
            [weight] => 50
            [height] => 165
            [gender] => 1
        )

)
```
输入 http://localhost:8080/user/all
```html
Array
(
    [0] => stdClass Object
        (
            [id] => 1
            [name] => Tom Li
            [age] => 15
            [weight] => 50
            [height] => 165
            [gender] => 1
        )

    [1] => stdClass Object
        (
            [id] => 2
            [name] => Tom Zhang
            [age] => 16
            [weight] => 50
            [height] => 165
            [gender] => 1
        )
)
...#后面省略
```
### 更新数据

```php
<?php
...
    public function postUpdate()
    {
        return Model::prepare("update users set age = ? where name = ?")
            ->bind([$_POST['age'],$_POST['name']])
            ->get();
    }
...    
```

使用 `curl` 模拟post请求
```linux
$ curl -d "name=Lily&age=20" localhost:8080/user/update
1
```
返回 1 表示更新成功


### 删除数据

```php
<?php
...
    public function postDelete()
    {
        return Model::prepare("delete from users where name = ?")
            ->bind([$_POST['name']])
            ->get();
    }
```
命令行执行
```linux
$ curl -d "name=Lily" localhost:8080/user/delete
1
```
返回 1 表示删除成功




#### Model类视频讲解: 

```video
http://labfile.oss-cn-hangzhou.aliyuncs.com/courses/1099/week1/16.%E6%A8%A1%E5%9E%8B%E7%B1%BB%E5%92%8C%E4%BD%BF%E7%94%A8.mp4
```





## 三、总结

本结主要介绍了使用 PDO 操作 MySQL，使用预处理执行增删改查操作。



## 挑战：对象数据映射 ORM

### 介绍

我们在 `模型` 实验中已经介绍了使用 PDO 来连接 MySQL 和执行增删改查操作。缺点是需要在控制器中写 SQL 语句，有时切换数据库后，SQL 语句不兼容会导致重新切换成本变高，因此我们经常会使用 ORM 来操作数据库。

ORM（Object Relational Mapping）对象关系映射，是通过使用描述对象和数据库之间映射的元数据，将面向对象语言程序中的对象自动持久化到关系数据库中。本质上就是将数据从一种形式转换到另外一种形式。ORM作为一种中间件，将数据库操作和应用隔离，使开发者只需关心应用本身忽略各个数据库之间操作不同带来的额外开发负担。


### 下载代码

将 `tinyphp.tar.gz` 下载到目录 `/home/shiyanlou`

```linux
wget http://labfile.oss.aliyuncs.com/courses/1076/tinyphp.tar.gz
```
项目中 `core/Orm.php` 以及实现了一部分查询操作，如

```
//select * from users;
User::find()->all();

//select * from users limit 1;
User::find()->one();
```

其中模型继承 Orm 类，一个模型对应一张表，表名称默认为该模型的小写复数形式，例如，User 表示 users 表，可以在模型中通过 table 属性重置表名。User 后的第一个方法（该例中的 find() ）需要使用（`::`）调用，并且必须声明为 protected，User 后面其他方法（如 all()，one() 以及需要实现的 where()，or()，and() 等方法需声明为public ）。

现在需要实现查询条件，where，and，or 如

```
//select * from users where gender = 1 and age > 15;
User::find()->where(['gender'=>1])->and(['>','age','15'])->all();

//select * from users where height < 160 and weight > 70;
User::find()->where('height < 160')->and(['>','weight','70'])->all();

//select * from users where name like '%Whang'' or age != 18;
User::find()->where(['like','name','%Wang'])->or(['in','age',[15,20]])->all();

```

### 目标

1. 启动并登陆数据库
```
sudo service mysql start
mysql -uroot -p
```
2. 创建数据库 `shiyanlou` 和表 `users`
```
mysql> create database shiyanlou;
mysql> use shiyanlou;
mysql> create table users (id int not null primary key auto_increment, name varchar(100) not null, age int not null, gender int not null, height int not null, weight int not null) engine=innodb default charset=utf8;
```
3. 插入数据
```
mysql> insert into users (`name`, `age`, `gender`, `height`, `weight`) values ('Tom Li', 15, 1, 165, 50), ('Tom Zhang', 16, 1, 165, 50), ('Jack Li', 15, 1, 170, 55), ('Lucy Wang', 15, 0, 150, 40), ('Max Wang', 15, 0, 155, 45);
```

4. 在 Orm.php 中添加 where()，and()，or() 方法，其他文件不修改。
5. 启动 PHP 内置服务器，端口为 8080。
6. 访问 `http://localhost:8080/user/info` 返回正确结果

### 提示语

1. where条件包括 >，<，=!，=，between，in，like，支持以下两种方式：
  - 字符串，如 `where('name like Tom%')`
  - 数组，如 `where(['like','name','Tom'])`，其中第一个参数为条件类型，第二个参数为字段名，第三个参数为值。如果条件类型为in，between，则第三个参数必须为数组，如 `where(['in','id',[1,2,3,4]])`，`where(['between','age',[18,20]])`
2. where()，and()和or()参数都是where条件，但是and()，or()可以执行多次，并且只能在where()后执行。
3. 使用预处理语句，值用 `?` 代替。 
4. 使用 PHP 内置服务器。

### 知识点

1. 面向对象
2. 字符串和数组操作
3. MySQL操作和SQL语句

