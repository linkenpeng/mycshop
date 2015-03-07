<table width="100%">
		<tr>
		  <th width="50">账号(id)</th>
		  <th width="80">用户类型</th>
		  <th width="80">用户姓名</th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td>
		  <input type="checkbox" name="uids" value="<?php echo $value['uid']; ?>" />
		  <?php echo $value['username']; ?>(<?php echo $value['uid']; ?>)
		  </td>
		  <td><?php echo $ugroup_list[$value['usertype']]; ?></td>
		  <td><?php echo $value['realname'];?></td>
		</tr>
		<?php }} ?>
</table>