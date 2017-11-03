
<h1>下载依赖</h1>

<p>npm install --save-dev</p>

<p>composer  install</p>

<h1>运行</h1>

<p>填写数据库</p>

复制一份 根目录下的 .env.example 文件 ,重命名为 .env

<p>用 cmd 运行到 项目的根目录，也就是 .env.example 文件的位置，运行 《ren .env.example .env 》 然后在.env文件里 填写数据库链接 如下：你自己准备好数据库</p>
           
<p>DB_CONNECTION=mysql</p>
<p>DB_HOST=IPLocation</p>
<p>DB_PORT=3306</p>
<p>DB_DATABASE=mysqlName</p>
<p>DB_USERNAME=mysqlUserName</p>
<p>DB_PASSWORD=mysqlUserPassword</p>
<p>php artisan serve //运行</p>


<p>以下步骤不用运行</p>

<p>php artisan make:controller Admin/HomeController   //生成控制器</p>

<p>php artisan make:model Article  --migration    //生成模型时生成数据库迁移</p>

<p>php artisan make:migration create_article_table    //生成 表</p>
