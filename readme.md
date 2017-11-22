
<h1>运行环境</h1>
<p>PHP版本 >= 5.6.4</p>
<p>PHP扩展：OpenSSL</p>
<p>PHP扩展：PDO</p>
<p>PHP扩展：Mbstring</p>
<p>PHP扩展：Tokenizer</p>
<p>PHP扩展：XML</p>
<p>Laravel 使用 Composer 管理依赖，因此，使用 Laravel 之前，确保机器上已经安装了Composer。</p>

<h1>下载依赖</h1>
<p>npm install --save-dev</p>
<p>composer  install</p>

<h1>运行</h1>

<p>填写数据库</p>

复制一份 根目录下的 .env.example 文件 ,重命名为 .env

<p>用 cmd 运行到 项目的根目录，也就是 .env.example 文件的位置，运行 《run .env.example .env 》 然后在.env文件里 填写数据库链接 如下：你自己准备好数据库</p>
           
<p>DB_CONNECTION=mysql</p>
<p>DB_HOST=IP</p>
<p>DB_PORT=3306</p>
<p>DB_DATABASE=数据库</p>
<p>DB_USERNAME=用户</p>
<p>DB_PASSWORD=密码</p>


<p>php artisan serve //运行</p>

命令提示
-
+ `cnpm install --no-bin-links` Windows下安装依赖，重点是**no bin links**
+ `cnpm run dev`  生成开发版本代码
+ `cnpm run prod` 生成部署版本代码

<p>项目地址</p>
<p>git clone https://github.com/QinBrother163/ToodoGD.git</p>

<p>以下步骤不用运行</p>
<p>php artisan make:controller Admin/HomeController   // 3 生成控制器</p>

<p>php artisan make:model Article  --migration    // 2 生成模型时生成数据库迁移</p>

<p>php artisan make:migration create_article_table    // 1 生成 表</p>