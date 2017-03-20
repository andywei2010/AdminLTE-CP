# AdminLTE-CP
基于 Codeigniter 3.0.6 + Smarty 3 + AdminLTE + SeaJs 2.2 + Art-Template + RBAC 的后台管理系统

### 使用
1.`git clone https://github.com/andywei2010/AdminLTE-CP.git`

2.`新建 gitlibs_cp 库，将 gitlibs_cp.sql 导入 mysql ，并修改 app/config/database.php 配置项`

3.`将 Nginx | Apache 虚拟主机指向根目录`

### 注
`如果使用 Apache ，请打开 httpd.conf / mod_rewrite 配置项`

`如使用 Nginx，配置以下几个环境变量：`

`fastcgi_param SITE_ENV 'development'`

`fastcgi_param SITE_CACHE_DIR 'c:\tmp\cache'`

`fastcgi_param SITE_LOG_DIR 'c:\tmp\log'`

### Done
