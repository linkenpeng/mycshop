<?php
$pagetitle="管理后台首页";
include trig_mvc_template::admin_template("header");
?>
<div class="pageMain">
<div class="pageTitle">当前位置：登陆首页 </div>
<div class="pageContent">
  <table width="100%">
    <tr>
      <td width="80%">
              欢迎你：<?php echo $_SESSION['admin_username'];?> 登录次数：<?php echo $user_info['logins'];?> 登陆IP：<?php echo get_IP();?> 现在时间：<? echo date("Y-m-d H:i:s",time());?> 登陆时间: <? echo date("Y-m-d H:i:s",$user_info['last_login']);?>
      </td>
    </tr>
</table>

<table width="100%">
    <tr class="odd">
      <th colspan="2">服务器的有关参数</th>
      <th colspan="2">组件支持有关参数</th>
    </tr>
    <tr class="odd">
      <td width="22%">服务器名：</td>
      <td width="38%"><?php echo $_SERVER["SERVER_NAME"]; ?></td>
      <td width="20%">mysql数据库：</td>
      <td width="20%"><?php echo showResult(function_exists("mysql_close"))?></td>
    </tr>
    <tr>
      <td width="22%">服务器IP：</td>
      <td width="38%"><?php echo $_SERVER["LOCAL_ADDR"]; ?></td>
      <td width="20%">odbc数据库：</td>
      <td width="20%"><?php echo showResult(function_exists("odbc_close"))?></td>
    </tr>
    <tr>
      <td width="22%">服务器端口：</td>
      <td width="38%"><?php echo $_SERVER["SERVER_PORT"]; ?></td>
      <td width="20%"> SQL Server数据库：</td>
      <td width="20%"><?php echo showResult(function_exists("mssql_close"))?></td>
    </tr>
    <tr>
      <td width="22%">服务器时间：</td>
      <td width="38%"><?php echo date("Y年m月d日H点i分s秒")?></td>
      <td width="20%">msql数据库：</td>
      <td width="20%"><?php echo showResult(function_exists("msql_close"))?></td>
    </tr>
    <tr>
      <td width="22%">PHP版本：</td>
      <td width="38%"><?php echo PHP_VERSION?></td>
      <td width="20%">SMTP：</td>
      <td width="20%"><?php echo showResult(get_magic_quotes_gpc("smtp"))?></td>
    </tr>
    <tr>
      <td width="22%">WEB服务器版本：</td>
      <td width="38%"><?php echo $_SERVER["SERVER_SOFTWARE"]; ?></td>
      <td width="20%">图形处理 GD Library：</td>
      <td width="20%"><?php echo showResult(function_exists("imageline"))?></td>
    </tr>
    <tr>
      <td width="22%">服务器操作系统：</td>
      <td width="38%"><?php echo PHP_OS?></td>
      <td width="20%">XML：</td>
      <td width="20%"><?php echo showResult(get_magic_quotes_gpc("XML Support"))?></td>
    </tr>
    <tr>
      <td width="22%">脚本超时时间：</td>
      <td width="38%"><?php echo get_cfg_var("max_execution_time")?>
        秒</td>
      <td width="20%">FTP：</td>
      <td width="20%"><?php echo showResult(get_magic_quotes_gpc("FTP support"))?></td>
    </tr>
    <tr>
      <td width="22%">站点物理路径：</td>
      <td width="38%"><?php echo realpath("../")?></td>
      <td width="20%">Sendmail：</td>
      <td width="20%"><?php echo showResult(get_magic_quotes_gpc("Internal Sendmail Support for Windows 4"))?></td>
    </tr>
    <tr>
      <td width="22%">脚本上传文件大小限制：</td>
      <td width="38%"><?php echo get_cfg_var("upload_max_filesize")?get_cfg_var("upload_max_filesize"):"不允许上传附件"?></td>
      <td width="20%">显示错误信息：</td>
      <td width="20%"><?php echo showResult(get_cfg_var("display_errors"))?></td>
    </tr>
    <tr>
      <td width="22%">POST提交内容限制：</td>
      <td width="38%"><?php echo get_cfg_var("post_max_size")?></td>
      <td width="20%">使用URL打开文件：</td>
      <td width="20%"><?php echo showResult(get_cfg_var("allow_url_fopen"))?></td>
    </tr>
    <tr>
      <td width="22%">服务器语种：</td>
      <td width="38%"><?php echo getenv("HTTP_ACCEPT_LANGUAGE")?></td>
      <td width="20%">压缩文件支持(Zlib)：</td>
      <td width="20%"><?php echo showResult(function_exists("gzclose"))?></td>
    </tr>
    <tr>
      <td width="22%">脚本运行时可占最大内存：</td>
      <td width="38%"><?php echo get_cfg_var("memory_limit")?get_cfg_var("memory_limit"):"无"?></td>
      <td width="20%">ZEND支持(1.3.0)：</td>
      <td width="20%"><?php echo showResult(function_exists("zend_version"))?></td>
    </tr>
</table>

</div>
</div>
<?php
include trig_mvc_template::admin_template("footer");
?>