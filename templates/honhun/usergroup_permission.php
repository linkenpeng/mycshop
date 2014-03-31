<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
$pagetitle = "用户组权限管理";
include admin_template("header");
?>
<script type="text/javascript"> 
<!--
	$(function(){
		$("#checkall").click(function(){
		   if($(this).attr("checked")==true)
		   {
			 $("input[name='menuid[]']").each(function(){
				$(this).attr("checked",true);
			 });
		   }
		   else
		   {
			  $("input[name='menuid[]']").each(function(){
				$(this).attr("checked",false);
			 });
			}
      });
	})
//-->
</script>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> </div>
<div class="pageContent">
<form action="<?php echo get_uri();?>" method="post" id="dataForm">
    <input type="hidden" name="ugid" value="<?=$value['ugid']?>" />
    <input type="hidden" name="action" value="1" />
  <table width="100%">
    <tr>
      <td colspan="2"><h3>[<?=$value['name']?>] 权限设置</h3></td>
    </tr>
    <?php if(is_array($list)) { 
			foreach ($list as $value) {
	?>
	<tr>
      <td>
			<input type="checkbox" name="menuid[]" <?php if(in_array($value['menuid'],$permissions)) echo "checked";?> value="<?=$value['menuid']?>" /><?=$value['name']?>
	  </td>
      <td>
		<?php if(is_array($value['subs'])) {
			foreach ($value['subs'] as $val2) {
		?>
			<input type="checkbox" name="menuid[]" <?php if(in_array($val2['menuid'],$permissions)) echo "checked";?> value="<?=$val2['menuid']?>" /><?=$val2['name']?>
		<?php 	}
		 } ?>
	  </td>
    </tr>
	<?php }} ?>
    <tr>
	  <td  width="130"><input type="checkbox" name="checkall" id="checkall"  />全选 </td>
      <td><input type="submit" value="提交" class="button" /></td>
    </tr>
</table>
 </form>
</div>
</div>
<?php
include admin_template("footer");
?>