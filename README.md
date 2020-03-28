## Linux下项目目录一览

**效果图如下:**

![avatar](http://q7v4u7f98.bkt.clouddn.com/ProjectsView/images/view.gif?e=1585333621&token=dFBLp0OyocKL_GCDqednYgngoNCSBGo0ubPJzPME:zL3cVsNSikjzLovWK7uINuyMKoU=)

> 运行环境要求PHP7.1+。

+ 直接修改配置文件conf/dir.php代码:
    ```php
    'project_path'      => '/var/www/projects/',
    'project_folder'    => 'projects/'   // 所有项目的父级目录名称
    ```

+ 项目直接克隆`git clone`到与projects同一级目录。并执行`composer install`。

+ 目录结构如下：
    ```
    /
    │
    └───data
        │
        └───var
            │   
            └───www
                │   
                └───index(此项目)
                │   
                └───projects(你要管理的项目父级目录)
                    │   
                    └───Project1
                    │   
                    └───Projects2
                    │   
                    └───Projects3
    ```
![avatar](http://q7v4u7f98.bkt.clouddn.com/ProjectsView/images/folder.png)

+ 配置nginx.conf如下：

    ```
    server {
        listen       80;
        server_name  localhost;

        root   /data/var/www/index/public;
        index index.php index.html index.htm;

        include enable-php.conf;

        #access_log  logs/host.access.log  main;
        location ^~/projects/{
            root   /data/var/www/;
            index index.php index.html index.htm;
            include enable-php.conf;
        }
    }
    ```
+ enable-php.conf内容如下：

    ```
    location ~ \.php($|/)
    {   
        if (!-e $request_filename) {
            rewrite ^(.*)$ /index.php?s=/$1 last;
            break;
        }

        set $path_info $fastcgi_path_info;
        try_files $fastcgi_script_name =404;
        # try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass  127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param PATH_INFO $fastcgi_path_info;
        # fastcgi_param PATH_INFO       $path_info;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi.conf;
        include fastcgi_params;
    }
    ```

