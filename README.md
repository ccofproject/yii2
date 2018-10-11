<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Advanced Project Template</h1>
    <br>
</p>

1-目录
===========

根目录包含以下子目录：

- `backend` - [backend （后端应用）](structure-applications.md).
- `common` - [common （所有应用程序共有的文件）](structure-applications.md).
- `console` - [console （命令行应用）](structure-applications.md).
- `environments` - [environment （环境配置）](structure-environments.md).
- `frontend` - [frontend （前端应用）](structure-applications.md).

根目录包含一组文件。

- `.gitignore` 包含由git版本系统忽略的目录列表。 如果你需要的东西从来没有到你的源代码存储库，添加它。
- `composer.json` - Composer配置文件 [Configuring Composer](start-composer.md).
- `init` - 初始化脚本描述文件 [Configuration and environments](structure-environments.md).
- `init.bat` - Windows下的初始化脚本描述文件.
- `LICENSE.md` - 许可信息。 把你的项目许可证放到这里。 特别是开源醒目。
- `README.md` - 安装模板的基本信息。 请考虑将其替换为有关您的项目及其安装的信息。
- `requirements.php` - 安装使用 Yii 需求检查器。
- `yii` - 控制台应用程序引导。
- `yii.bat` - Windows下的控制台应用程序引导.


2-预定义路径别名
=======================

- `@yii` - 框架目录。
- `@app` - 当前运行的应用程序的基本路径。
- `@common` - 公共目录。
- `@frontend` - 前端Web应用程序目录。
- `@backend` - 后端Web应用程序目录。
- `@console` - 控制台目录。
- `@runtime` - 当前正在运行的Web应用程序的runtime目录。
- `@vendor` - Composer vendor 目录.
- `@bower` - vendor 目录下的 [bower packages](http://bower.io/).
- `@npm` - vendor 目录下的 [npm packages](https://www.npmjs.org/).
- `@web` - 当前运行的Web应用程序的 base URL。
- `@webroot` - 当前运行的Web应用程序的web根目录。

特定于高级应用程序的目录结构的别名
(`@common`,  `@frontend`, `@backend`, 以及 `@console`) 在 `common/config/bootstrap.php` 文件中定义.


3-应用
============

高级模板中有三个应用程序：前端，后端和控制台。 前端通常是呈现项目本身到最终用户。 后端是管理面板，分析和其他诸如此类的功能。 控制台通常用于cron作业和低级服务器管理。 它也在应用程序部署期间使用，并处理数据迁移和资源包。

还有一个 `common` 目录，其中包含多个应用程序使用的文件。 例如，`User` Model。

前端和后端都是Web应用程序，并且都包含 `web` 目录。 这个目录就是web服务器要映射域名的目录。

每个应用程序都有自己的命名空间和别名对应其名称。 这同样适用于公共目录。


4-配置和环境
==============================

典型的配置方法有多个问题：

- 每个团队成员都有自己的配置选项。 提交此配置将影响其他团队成员。
- 生产数据库密码和API密钥不应该存储在存代码库中。
- 有多个服务器环境：开发，测试，生产。 每个应该有自己的配置。
- 为每种情况定义所有配置选项非常重复，需要花费太多时间来维护。

为了解决这些问题，Yii介绍了一个简单的环境概念。 每个环境由 `environments` 目录下的一组文件表示。  `init` 命令用于初始化一个环境。 它真正做的是将所有内容从环境目录复制到所有应用程序所在的根目录。

默认情况下有两个环境： `dev` 和 `prod` 。 第一个是开发环境。 默认打开所有开发调试工具。 第二个是生产环境。 默认关闭调试和开发工具。

通常环境包含应用程序引导文件，如 `index.php` 和配置文件后缀 `-local.php` 。 这些是通常在 `dev` 环境中的团队成员的个人配置或特定服务器的配置。 例如，生产数据库连接可以在 `prod` 环境 `-local.php` 配置中。 这些本地配置被添加到 `.gitignore` ，从不推送到源代码仓库。

为了避免重复配置彼此覆盖。 例如，前端读取配置以如下顺序：

- `common/config/main.php`
- `common/config/main-local.php`
- `frontend/config/main.php`
- `frontend/config/main-local.php`

参数按以下顺序读取：

- `common/config/params.php`
- `common/config/params-local.php`
- `frontend/config/params.php`
- `frontend/config/params-local.php`

后面的配置文件覆盖前者。


5-创建从后端到前端的链接
=======================================

通常需要创建从后端应用程序到前端应用程序的链接。 由于前端应用程序可能包含自己的URL管理器规则，因此您需要通过将后端应用程序命名为不同的方式来复制后端应用程序：

```php
return [
    'components' => [
        'urlManager' => [
            // here is your normal backend url manager config
        ],
        'urlManagerFrontend' => [
            // here is your frontend URL manager config
        ],

    ],
];
```

完成后，您可以获取指向前端的URL，如下所示：

```php
echo Yii::$app->urlManagerFrontend->createAbsoluteUrl(...);
```

为了不复制粘贴前端规则，你可以先将它们移动到单独的 `urls.php` 文件中：

```php
return [
    // ...
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => require 'urls.php',
        ],
        // ...

    ],
    // ...
];
```

之后，你可以将它包含在 `urlManagerFrontend` 规则中。


6-添加更多应用程序
========================

虽然有单独的前端和后端是常见的，有时它是不够的。 例如，您可能需要额外的应用程序，例如博客。 使用如下方式创建：

1. 复制 `frontend` 至 `blog`, `environments/dev/frontend` 至 `environments/dev/blog` 以及 `environments/prod/frontend`
至 `environments/prod/blog`.
2. 调整命名空间和路径以 `blog` 开头（替换 `frontend`）.
3. 在 `common\config\bootstrap.php` 中添加 `Yii::setAlias('blog', dirname(dirname(__DIR__)) . '/blog');`.

