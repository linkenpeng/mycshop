<?php
$pagetitle="管理后台首页";
include admin_template("header");
?>
<div class="pageMain">

<div class="pageContent">

<table width="100%">
    <tr class="odd">
      <td width="50%">
			<div class="pageTitle">需要处理的订单</div>
			<ul>
				<?php if(is_array($undeal_order_list)) { 
					foreach ($undeal_order_list as $value) {
				?>
				<li><?=$value['ordername']?></li>
				<?php }} ?>
			</ul>
	  </td>
      <td width="50%">
			<div class="pageTitle">我的订单</div>
			<ul>
				<?php if(is_array($my_order_list)) { 
					foreach ($my_order_list as $value) {
				?>
				<li><?=$value['ordername']?></li>
				<?php }} ?>
			</ul>
	  </td>
    </tr>
	 <tr class="odd">
      <td width="50%">
			<div class="pageTitle">我的洽谈记录</div>
			<ul>
				<li>我的洽谈记录</li>
			</ul>
	  </td>
      <td width="50%">
			<div class="pageTitle">我的安排记录</div>
			<ul>
				<li>我的安排记录</li>
			</ul>
	  </td>
    </tr>
</table>

</div>
</div>
<?php
include admin_template("footer");
?>