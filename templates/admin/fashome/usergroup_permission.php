<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
trig_mvc_template::$title = "用户组权限管理";
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
<div class="pageTitle_left"></div>当前位置：<?php echo trig_mvc_template::$title;?> </div>
<div class="pageContent">
<form action="<?php echo trig_mvc_route::get_uri();?>" method="post" id="dataForm">
    <input type="hidden" name="ugid" value="<?php echo $value['ugid']; ?>" />
    <input type="hidden" name="action" value="1" />
    <div class="table-responsive">
        <table class="table table-bordered">
            <tbody>
            <tr>
                <th>&nbsp;</th><th>超小屏幕 <small>(手机&lt;760px)</small></th><th>小屏幕 <small>(平板≥760px)</small></th><th>中屏幕 <small>(桌面≥1000px)</small></th><th>大屏幕 <small>(宽屏≥1200px)</small></th></tr>
            <tr><td>.container最大值</td><td>自动</td><td>740px</td><td>980px</td><td>1180px</td></tr>
            <tr><td>格子样式</td><td>.xl-(*)</td><td>.xs-(*)</td><td>.xm-(*)</td><td>.xb-(*)</td></tr>
            <tr><td>响应效果</td><td>总是水平排列</td><td colspan="3">水平排列，当屏幕小于这些阈值时堆叠在一起</td></tr>
            <tr><td>格子数</td><td colspan="4">12</td></tr>
            <tr><td align="center" colspan="5"><strong>响应显示及隐藏：</strong>控制在对应屏幕下的显示和隐藏</td></tr>
            <tr><td>显示响应</td><td>.show-l</td><td>.show-s</td><td>.show-m</td><td>.show-b</td></tr>
            <tr>
                <td>隐藏响应</td><td>.hidden-l</td><td>.hidden-s</td><td>.hidden-m</td><td>.hidden-b</td>
            </tr>
            </tbody>
        </table>
    </div>
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