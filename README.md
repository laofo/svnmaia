
关于Maia(迈亚)SVN用户管理系统
svnMaia系统专注于管理svn权限、svn用户，基于PHP+apache+mysql的系统。
Maia(迈亚)SVN用户管理系统系统的安装
参阅：安装说明
使用说明
参阅： 目录管理员使用说明
版本更新
参阅src代码根目录中的【ChangLog.txt】：http://code.taobao.org/p/svnmaia/src/trunk/ChangeLog.txt

安装说明
假设你的系统已经支持PHP、apache、mysql（如果没有配置，请百度一下参阅相关文章），则部署“迈亚SVN用户管理系统（下简称svnMaia）” 非常简单。
1.下载源码
可以通过svn co命令，从本站的源码中获取合适版本（tags/子目录）。放置到你的apache的documentRoot目录下。
2. 修改目录的权限
代码下载之后，确保apache的运行用户对该目录具有可写权限。或者你为setup/、config/、scheme/目录单独赋予写权限： chmod o=rwx setup config scheme
3.访问setup.php页面，生成config.inc文件
访问 http://***.com/svnMaia/index.php 页面（如果出来的页面为空白或者报错，则表明你的系统没有正确安装php），根据页面提示填写参数，生成svnMaia配置文件config.inc。
更多请参阅附件的pdf安装说明。
