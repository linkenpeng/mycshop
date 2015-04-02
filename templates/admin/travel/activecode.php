<?php
$pagetitle="激活码管理";
include trig_func_common::admin_template("header");
?>
<script type="text/javascript"> 
<!--
	$(function(){
		$("input[name='checkall']").click(function() {
			if(this.checked) {
                $("input[name='checkall']").attr("checked",true);
				$("input[name='ids[]']").attr("checked",true);
            } else {
				$("input[name='checkall']").attr("checked",false);
                $("input[name='ids[]']").attr("checked",false);
            }
		});
		
		//排序
		$("#order a").click(function(){
			var orderby = $(this).attr("orderby");
			var order = $(this).attr("order");
			$("#order a span").html("");
			$("#orderby_value").val(orderby);
			$("#order_value").val(order);
			if(order == "asc") {
				$(this).find("span").html("↑");
				$(this).attr("order","desc");
				
			} else {
				$(this).find("span").html("↓");
				$(this).attr("order","asc");
			}
			$("#searchform").submit();
		});
	})
//-->
</script>

<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo trig_func_common::get_uri("activecode","add");?>">添加</a>
</div>
<div class="pageContent">
  <form action="<?php echo trig_func_common::get_uri();?>" method="get" id="searchform">
	  <input name="<?php echo M;?>" type="hidden" value="<?php echo $_GET[M];?>" />
	  <input name="<?php echo C;?>" type="hidden" value="<?php echo $_GET[C];?>" />
	  <input name="<?php echo A;?>" type="hidden" value="<?php echo $_GET[A];?>" />	  
	  <input name="orderby_value" id="orderby_value" type="hidden" value=""  />	  
	  <input name="order_value" id="order_value" type="hidden" value="" />	  
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
	  <tr>
		
		<td align="right" >
		所属景区: <input name="scenename" type="text" size="12" value="<?php echo $scenename?>" />
		激活码: <input name="activecode" type="text" size="12" value="<?php echo $activecode?>" />
		生成时间:
		从 <input name="startdate" id="startdate" type="text" size="20" value="<?php echo $startdate?>" class="Wdate" onClick="WdatePicker()" />
		到 <input name="enddate" id="enddate" type="text" size="20" value="<?php echo $enddate?>" class="Wdate" onClick="WdatePicker()" /> 
		每页数据:<input name="pagesize" type="text" size="3" value="<?php echo $pagesize?>" />
		<input type="submit" name="Submit" value="<?php echo trig_func_common::lang("action","search")?>" />
		
		</td>
	  </tr>
	 </table>
  </form>
	
	 <form action="<?php echo trig_func_common::get_uri("activecode","batch","admin");?>" method="post">
	 <table width="100%">
		<tr id="order">
		  <th width="20"><input type="checkbox" name="checkall" id="checkall" /></th>
		  <th width="100">
		  <a href="javascript:void(0);" orderby="cardnum" order="<?php echo $order == 'asc' ? 'desc' : 'asc';?>">会员号
		  <span>
		  <?php 	  
		  if($orderby=='cardnum') echo $order == 'asc' ? '↑' : '↓';
		  ?>
		  </span>
		  </a>
		  </th>
		  <th width="40">
		  <a href="javascript:void(0);" orderby="activecode" order="<?php echo $order == 'asc' ? 'desc' : 'asc';?>">激活码
		  <span>
		  <?php 	  
		  if($orderby=='activecode') echo $order == 'asc' ? '↑' : '↓';
		  ?>
		  </span>
		  </a>
		  </th>
		  <th width="100">所属景区</th>
		  <th width="40"><a href="javascript:void(0);" orderby="usednum" order="<?php echo $order == 'asc' ? 'desc' : 'asc';?>">使用次数
		  <span>
		  <?php 	  
		  if($orderby=='usednum') echo $order == 'asc' ? '↑' : '↓';
		  ?>
		  </span></a></th>
		  <th width="100">批号</th>
		  <th width="80">生成时间</th>		  
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="center">
		  <input type="checkbox" name="ids[]" value="<?php echo $value['id']; ?>" />
		  </td>
		  <td align="center">
		  <?php echo $value['cardnum']; ?>
		  </td>
		  <td align="center">
		  <?php echo $value['activecode']; ?>
		  </td>
		  <td align="center"><?php echo $value['scenename']; ?></td>
		  <td align="center"><?php echo $value['usednum']; ?></td>
		  <td align="center">
		  <?php echo isset($batlist[$value['batchid']]) ? : ''; ?>
		  
		  [<a href="<?php echo trig_func_common::get_uri("activecode","delbatch","admin");?>&batchid=<?php echo $value['batchid']; ?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');" >删除此批</a>]
		  
		  </td>
		  <td align="center"><?php echo date("Y-m-d H:i",$value['dateline'])?></td>
		</tr>
		<?php }} ?>
		<tr>
			<td align="center">
				<input type="checkbox" name="checkall" id="checkall" />
			</td>
			<td colspan=5 align="left">				
				<select name="op" id="op">
					<option value="del">删除</option>
				</select>
				<input type="submit" onclick="return confirm('确定删除吗?')" value="批量操作" />
			</td>
		</tr>
		<tr>
			<td colspan=6 align="center">
				<div class="page">
					<?php echo trig_func_common::lang("page","total")?><b><?php echo $count?></b><?php echo trig_func_common::lang("page","item")?> <b><?php echo $nowpage?>/<?php echo $p->totalpage?></b><?php echo trig_func_common::lang("page","page")?> <?php echo $p->show(); ?>
				</div>
			<?php
				$endTime = trig_func_common::mtime();
				$totaltime = sprintf("%.3f",($endTime - START_TIME));
				echo trig_func_common::lang("page","thispage").trig_func_common::lang("common","excute").trig_func_common::lang("common","time").($totaltime).trig_func_common::lang("common","second");
			?>
			</td>
		</tr>
	</table>
	</form>
</div>
</div>
<?php
include trig_func_common::admin_template("footer");
?>