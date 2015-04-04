<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
$pagetitle = "用户组权限管理";
include trig_mvc_template::admin_template("header");
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
<form action="<?php echo trig_mvc_route::get_uri();?>" method="post" id="dataForm">
    <input type="hidden" name="ugid" value="<?php echo $value['ugid']; ?>" />
    <input type="hidden" name="action" value="1" />
  <table width="100%">
    <tr>
      <td colspan="2"><h3>[<?php echo $value['name']; ?>] 权限设置</h3></td>
    </tr>
    <?php if(!empty($list)) { 
			foreach ($list as $value) {
	?>
	<tr>
      <td>
			<input type="checkbox" name="menuid[]" <?php if(in_array($value['menuid'],$permissions)) echo "checked";?> value="<?php echo $value['menuid']; ?>" /><?php echo $value['name']; ?>
	  </td>
      <td>
		<?php if(!empty($value['subs'])) {
			foreach ($value['subs'] as $val2) {
		?>
			<input type="checkbox" name="menuid[]" <?php if(in_array($val2['menuid'],$permissions)) echo "checked";?> value="<?php echo $val2['menuid']; ?>" /><?php echo $val2['name']; ?>
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
include trig_mvc_template::admin_template("footer");
?>