<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
$pagetitle = "添加修改洽谈记录";
include trig_mvc_template::admin_template("header");
?>
<script type="text/javascript"> 
<!--
	$(function(){
		$.formValidator.initConfig({formid:"dataForm",autotip:true,onerror:function(){}});
		$("#title").formValidator({onshow:"请输入洽谈标题",onfocus:"请输入洽谈标题",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少一个字符"});

		tinyMCE.init({
	        language:"zh-cn",
			mode:"exact",
			elements:"content",
	        plugins:"inlinepopups,preview,fullscreen,paste,media",
	            theme:"advanced",
	            theme_advanced_buttons1:"code,fullscreen,preview,removeformat,|,bold,italic,underline,strikethrough,|," +
	                "formatselect,fontsizeselect,|,forecolor,backcolor",
	            theme_advanced_buttons2:"bullist,numlist,|,outdent,indent,blockquote,|,justifyleft,justifycenter," +
	                "justifyright,justifyfull,|,link,unlink,charmap,|,pastetext,pasteword,|,undo,redo",
	            theme_advanced_buttons3 : "",
			relative_urls : false,
	        remove_script_host : false,
	        theme_advanced_toolbar_location:"top",
	        theme_advanced_toolbar_align:"left"
		});
		
		$("#sendto").click(function() {
			$("#container_pop").show();
			$("#container_pop_title").html('选择发送对象');
			//ajax获取数据
			ajaxGetData();
			//拖动
			var offset=$("#sendto").offset();
			var topoff=offset.top-200;
			var leftoff=offset.left;
			
			$("#container_pop").css({left:leftoff+"px",top:topoff+"px"});
			LinkDrag("container_pop","container_pop_head",leftoff,topoff);
		});
		$("#container_pop_close").click(function() {
			$("#container_pop").hide();
			return false;
		});
		$("#checkall").click(function() {
			var checked=$("#checkall").attr("checked");
			$("input:[name='uids']").each(function(){
					$(this).attr("checked",checked);
			});
		});
	});
	function ajaxGetData() {
		var usertype=$("#usertype").val();
		var keyword=$("#keyword").val();
		if(keyword=="输入用户名") {
			keyword="";
		}
		$.get('index.php?mod=admin&c=user&a=ajax_userlist&usertype='+encodeURI(usertype)+'&keyword='+encodeURI(keyword),function(s){
			$("#container_content").html(s);
		});
	}
	function add_sendlist() 
	{
		var uids="";
		$("input:[name='uids']").each(function(){
			if($(this).attr("checked"))
			{
				uids+=$(this).val()+",";
			}
		});
		$("#sendto").val(uids);
		$("#container_pop").hide();
	}
//-->
</script>

<div id="container_pop" class="layer_global" style="width: 520px;display:none;">
    <div id="container_pop_wrap" class="layer_global_main">
        <div id="container_pop_head" class="layer_global_title" style="cursor: move;">
            <h3>
            <span id="container_pop_title">选择发送对象</span>
            <span style="font-weight: normal;"></span>
            </h3>
            <button id="container_pop_close" href="javascript:void(0)" title="关闭" >
            </button>
        </div>
        <div class="layer_global_content" style="clear:both;">
            <div style="height:22px; border-bottom:1px solid #E0E4E9;padding:12px 10px;">
            	<select id="usertype" name="usertype">
                	<option value="">选择用户组</option>
					<?php if(is_array($ugroup_list)) { 
						foreach ($ugroup_list as $k=>$v) {
					?>
					<option value="<?php echo $k;?>" <?php if($usertype==$k) { ?> selected="selected" <?php } ?>><?php echo $v;?></option>
					<?php }} ?>
                </select>
                <input type="text" value="输入用户名" name="keyword" id="keyword" onfocus="if(this.value=='输入用户名') this.value='';"  />
                <input type="button" value="搜索" onclick="ajaxGetData();" class="submit" />
            </div>
            <div id="container_content" style="height:200px;overflow:auto;">
                
            </div>
            <div style="height:22px; border-top:1px solid #E0E4E9;padding:12px 10px;">
                <input type="checkbox" name="checkall" id="checkall" /> 全选
                <input type="button" value="加入发送框" onclick="add_sendlist();" class="submit" />
            </div>
        </div>
    </div>
</div>

<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> </div>
<div class="pageContent">
  <table width="100%">
    <form action="<?php echo trig_mvc_route::get_uri();?>" method="post" id="dataForm" enctype="multipart/form-data">
    <input type="hidden" name="talkid" value="<?php echo $value['talkid']; ?>" />
    <input type="hidden" name="action" value="1" />
    <input type="hidden" name="oldattachment" value="<?php echo $value['attachment']; ?>" />
	<tr>
      <td width="20%">标题</td>
      <td width="80%"><input type="text" name="title" id="title" value="<?php echo $value['title']; ?>" size="50" /> (*)</td>
    </tr>
    <tr>
      <td width="20%">主要内容</td>
      <td width="80%"><textarea name="content" id="content"" cols="60" rows="10"><?php echo $value['content']; ?></textarea></td>
    </tr>
    <tr>
      <td width="20%">上传附件</td>
      <td width="80%"><input type="file" name="attachment" id="attachment" /> </td>
    </tr>
	<tr>
      <td width="20%">发送对象</td>
      <td width="80%"><textarea name="sendto" id="sendto"" cols="60" rows="6"><?php echo $value['sendto']; ?></textarea></td>
    </tr>
    <tr>
      <td width="100%" colspan="2" align="center"><input type="submit" value="提交" class="button" /></td>
    </tr>
    </form>
</table>
</div>
</div>
<?php
include trig_mvc_template::admin_template("footer");
?>