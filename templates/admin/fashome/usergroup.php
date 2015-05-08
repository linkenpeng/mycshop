<?php
trig_mvc_template::$title = "用户组管理";
?>
<form action="<?php echo trig_mvc_route::get_uri();?>" method="post">
    <input name="<?php echo M;?>" type="hidden" value="<?php echo $_GET[M];?>" />
    <input name="<?php echo C;?>" type="hidden" value="<?php echo $_GET[C];?>" />
    <input name="<?php echo A;?>" type="hidden" value="<?php echo $_GET[A];?>" />
    <div class="panel admin-panel">
        <div class="panel-head"><strong>用户组列表</strong></div>
        <div class="padding border-bottom">
            <div class="form-inline">
                <div class="form-group">
                    <div class="field">
                    </div>
                </div>
                <div class="form-button"></div>
            </div>
            <div class="float-right">
                <a class="button border-blue button-small" href="<?php echo trig_mvc_route::get_uri("usergroup","add");?>">
                    <span class="icon-plus text-blue"></span>
                    <?php echo trig_func_common::lang("action","add")?>
                </a>
            </div>
        </div>
        <table class="table table-hover">
            <tr>
                <th width="200">用户类型</th>
                <th width="200">备注</th>
                <th width="200">添加时间</th>
                <?php if($_SESSION['usertype']==ADMIN_USER_TYPE) {?>
                    <th width="100"><?php echo trig_func_common::lang("action","operation")?> </th>
                <?php }?>
            </tr>
            <?php if(is_array($list)) {
                foreach ($list as $value) {
                    ?>
                    <tr>
                        <td><?php echo $value['name']; ?></td>
                        <td><?php echo $value['description'];?></td>
                        <td><?php echo date("Y-m-d H:i:s",$value['dateline']);?></td>
                        <?php if($_SESSION['usertype']==ADMIN_USER_TYPE) {?>
                            <td>
                                <a href="<?php echo trig_mvc_route::get_uri("usergroup","edit","admin",array('ugid'=>$value['ugid']));?>" ><span class="icon-edit text-blue"></span><?php echo trig_func_common::lang("action","edit")?></a>
                                <a href="<?php echo trig_mvc_route::get_uri("usergroup","delete","admin",array('ugid'=>$value['ugid'])); ?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><span class="icon-trash-o text-red"></span><?php echo trig_func_common::lang("action","delete")?></a>
                                <a href="<?php echo trig_mvc_route::get_uri("usergroup","permission","admin",array('ugid'=>$value['ugid'])); ?>" ><span class="icon-cog text-green"></span>权限管理</a>
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
