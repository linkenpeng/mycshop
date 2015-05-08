<?php
trig_mvc_template::$title = "用户管理";
?>
<form action="<?php echo trig_mvc_route::get_uri();?>" method="post">
    <input name="<?php echo M;?>" type="hidden" value="<?php echo $_GET[M];?>" />
    <input name="<?php echo C;?>" type="hidden" value="<?php echo $_GET[C];?>" />
    <input name="<?php echo A;?>" type="hidden" value="<?php echo $_GET[A];?>" />
    <div class="panel admin-panel">
        <div class="panel-head"><strong>用户列表</strong></div>
        <div class="padding border-bottom">
            <div class="form-inline">
                <div class="form-group">
                    <div class="label"><label for="username">用户名：</label></div>
                    <div class="field">
                        <input type="text" placeholder="用户名" size="20" name="keyword" id="keyword" class="input" value="<?php echo $keyword?>">
                    </div>
                </div>
                <div class="form-group">
                    <select id="usertype" class="input" name="usertype">
                        <option value="">选择分类</option>
                        <?php if(is_array($ugroup_list)) {
                            foreach ($ugroup_list as $k=>$v) {
                                ?>
                                <option value="<?php echo $k;?>" <?php if($usertype==$k) { ?> selected="selected" <?php } ?>><?php echo $v;?></option>
                            <?php }} ?>
                    </select>
                </div>
                <div class="form-button"><button type="submit" class="button bg-main"><?php echo trig_func_common::lang("action","search")?></button></div>
            </div>
            <div class="float-right">
                <a class="button border-blue button-small" href="<?php echo trig_mvc_route::get_uri("user","add");?>">
                    <span class="icon-plus text-blue"></span>
                    <?php echo trig_func_common::lang("action","add")?>
                </a>
            </div>
        </div>
        <table class="table table-hover">
            <tr>
                <th width="100">账号</th>
                <th width="200">用户类型</th>
                <th width="200">用户姓名</th>
                <th width="300">所在地</th>
                <th width="80">创建时间</th>
                <?php if($_SESSION['usertype']==ADMIN_USER_TYPE) {?>
                    <th width="100"><?php echo trig_func_common::lang("action","operation")?> </th>
                <?php }?>
            </tr>
            <?php if(is_array($list)) {
                foreach ($list as $value) {
                    ?>
                    <tr>
                        <td><?php echo $value['username']; ?></td>
                        <td><?php echo $ugroup_list[$value['usertype']]; ?></td>
                        <td><?php echo $value['realname'];?></td>
                        <td><?php echo $value['province'].$value['city'].$value['country'];?></td>
                        <td><?php echo date("Y-m-d H:i:s",$value['regtime']);?></td>
                        <?php if($_SESSION['usertype']==ADMIN_USER_TYPE) {?>
                            <td>
                                <a href="<?php echo trig_mvc_route::get_uri("user","edit","admin",array('uid'=>$value['uid']));?>" ><span class="icon-edit text-blue"></span><?php echo trig_func_common::lang("action","edit")?></a>
                                <?php if($value['uid']!=1) {?>
                                    <a href="<?php echo trig_mvc_route::get_uri("user","delete","admin",array('uid'=>$value['uid']));?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><span class="icon-trash-o text-red"></span><?php echo trig_func_common::lang("action","delete")?></a>
                                <?php }?>
                            </td>
                        <?php }?>
                    </tr>
                <?php }} ?>
            <tr>
                <td colspan=8>
                    <div class="page"><?= trig_helper_html::page_info($p) ?></div>
                    <div class="run-info"><?= trig_helper_html::run_info(array('startTime' => START_TIME, 'endTime' => trig_func_common::mtime())) ?></div>
                </td>
            </tr>
        </table>
    </div>
</form>
