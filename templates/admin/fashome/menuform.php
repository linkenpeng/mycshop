<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
trig_mvc_template::$title = "添加修改菜单";
?>
<div class="tab-head">
    <h5 id="icon-small" class="doc-h4">当前位置：<?php echo trig_mvc_template::$title;?></h5>
</div>
<div class="tab-body">
    <br>
    <div id="tab-set" class="tab-panel active">
        <form action="<?php echo trig_mvc_route::get_uri();?>"  class="form-x" method="post" id="dataForm">
            <input type="hidden" name="menuid" value="<?php out($value, 'menuid'); ?>" />
            <input type="hidden" name="action" value="1" />
            <input type="hidden" name="parentid" value="<?php out($value,'parentid'); ?>" />
            <?php
              if(!empty($up_menus)) {
            ?>
                  <div class="doc-demoview doc-viewad0">
                      <div class="view-body">
                          <div class="form-group">
                              <div class="label"><label for="parentid">父菜单名称</label></div>
                              <div class="field">
                                  <select name="parentid" class="input" id="parentid">
                                      <?php
                                      foreach($up_menus as $up_menu) { ?>
                                          <option value="<?php echo $up_menu['menuid']; ?>" <?php if($value['parentid'] == $up_menu['menuid']) echo 'selected'; ?>><?php echo $up_menu['name']; ?></option>
                                      <?php } ?>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="label"><label for="name">菜单名称</label></div>
                              <div class="field">
                                  <input type="text" size="30" name="name" id="name" class="input" placeholder="请输入菜单名" data-validate="required:请输入菜单名" value="<?php out($value,'name'); ?>">
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="label"><label for="icon">图标Icon</label></div>
                              <div class="field">
                                  <input type="text" size="30" name="icon" id="icon" class="input" value="<?php out($value,'icon'); ?>">
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="label"><label for="level">菜单级别</label></div>
                              <div class="field">
                                  <select name="level" class="input" id="level">
                                      <?php foreach($levels as $level) { ?>
                                          <option value="<?php echo $level; ?>" <?php if(isset($value['level'])&&$value['level'] == $level) echo 'selected'; ?>><?php out($level); ?></option>
                                      <?php } ?>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="label"><label for="model">模型</label></div>
                              <div class="field">
                                  <input type="text" size="30" name="model" id="model" class="input" placeholder="请输入模型" data-validate="required:请输入模型" value="<?php out($value,'model'); ?>">
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="label"><label for="ctrl">控制器</label></div>
                              <div class="field">
                                  <input type="text" size="30" name="ctrl" id="ctrl" class="input" placeholder="请输入控制器" data-validate="required:请输入控制器" value="<?php out($value,'ctrl'); ?>">
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="label"><label for="act">动作</label></div>
                              <div class="field">
                                  <input type="text" size="30" name="act" id="act" class="input" placeholder="请输入动作" data-validate="required:请输入动作" value="<?php out($value,'act'); ?>">
                              </div>
                          </div>
                          <div class="form-button"><button type="submit" class="button bg-main">提交</button></div>
                      </div>
                  </div>
        <?php } ?>
        </form>
    </div>
</div>