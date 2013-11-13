<?php 
	function page_addForm($data)
	{
		$dataDefine=ml_factory::load_dataDefine($data['_dataDefine']);
		$dataDefine = $dataDefine['field'];
?>
		<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<form action="?dtdfn=<?php echo $data['_dataDefine'] ?>&api=add" method="post">
			<tr>
				<th colspan="2">新增</th>
			</tr>
			
			<tr>
				<td><?php echo $dataDefine['title']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['title']['type'] , 'title' , $dataDefine['title']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['codeSign']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['codeSign']['type'] , 'codeSign' , $dataDefine['codeSign']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['rss']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['rss']['type'] , 'rss' , $dataDefine['rss']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['domain']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['domain']['type'] , 'domain' , $dataDefine['domain']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['site_name']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['site_name']['type'] , 'site_name' , $dataDefine['site_name']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['tags']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['tags']['type'] , 'tags' , $dataDefine['tags']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['language']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['language']['type'] , 'language' , $dataDefine['language']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['spider_time']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['spider_time']['type'] , 'spider_time' , $dataDefine['spider_time']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['spider_type']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['spider_type']['type'] , 'spider_type' , $dataDefine['spider_type']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['charset']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['charset']['type'] , 'charset' , $dataDefine['charset']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['category']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['category']['type'] , 'category' , $dataDefine['category']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['contentName_tagid']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::html_select('contentName_tagid' , $data['aTag']); ?></td>
			</tr>
			
			<tr>
				<td colspan="2"><input type="submit" value="保存"/></td>
			</tr>
			</form>
		</table>
<?php
	}

	function page_editForm($data)
	{
		$dataDefine=ml_factory::load_dataDefine($data['_dataDefine']);
				$dataDefine = $dataDefine['field'];
?>
		<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<form action="?dtdfn=<?php echo $data['_dataDefine'] ?>&api=edit&id=<?php echo $data['row']['id']; ?>" method="post">
			<tr>
				<th colspan="2">编辑</th>
			</tr>
				<tr>
				<td><?php echo $dataDefine['title']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['title']['type'] , 'title' , $dataDefine['title'] , $data['row']['title']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['codeSign']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['codeSign']['type'] , 'codeSign' , $dataDefine['codeSign'] , $data['row']['codeSign']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['rss']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['rss']['type'] , 'rss' , $dataDefine['rss'] , $data['row']['rss']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['domain']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['domain']['type'] , 'domain' , $dataDefine['domain'] , $data['row']['domain']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['site_name']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['site_name']['type'] , 'site_name' , $dataDefine['site_name'] , $data['row']['site_name']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['tags']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['tags']['type'] , 'tags' , $dataDefine['tags'] , $data['row']['tags']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['language']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['language']['type'] , 'language' , $dataDefine['language'] , $data['row']['language']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['spider_time']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['spider_time']['type'] , 'spider_time' , $dataDefine['spider_time'] , $data['row']['spider_time']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['spider_type']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['spider_type']['type'] , 'spider_type' , $dataDefine['spider_type'] , $data['row']['spider_type']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['charset']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['charset']['type'] , 'charset' , $dataDefine['charset'] , $data['row']['charset']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['category']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['category']['type'] , 'category' , $dataDefine['category'] , $data['row']['category']); ?></td>
			</tr>
			<tr>
				<td><?php echo $dataDefine['contentName_tagid']['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::html_select('contentName_tagid' , $data['aTag'] , $data['row']['contentName_tagid'],'','',true); ?></td>
			</tr>
			
			<tr>
				<td colspan="2"><input type="submit" value="保存"/></td>
			</tr>
			</form>
		</table>
<?php
	}

	function page_index($data)
	{
		$dataDefine=ml_factory::load_dataDefine($data['_dataDefine']);
		$dataDefine = $dataDefine['field'];

?>
		<span class="pull-left">
		<?php foreach ($dataDefine['category']['enum'] as $key => $value) { ?>
			<a href="?category=<?php echo $key; ?>"><?php echo $value; ?></a> |
		<?php } ?>
		</span>
		<a href="?dtdfn=<?php echo $data['_dataDefine']; ?>&page=addForm" class="btn btn-success">新增</a>
		<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<tr>
				<th>#</th>
				
				<th>内容领域</th>
				<th>内容名称</th>
				<th>网站名称</th>
				<th>编程标识</th>
				<th>rss</th>
				<th>域名</th>
				<th>网站名称</th>
				<th>标签</th>
				<th>语言</th>
				<th>抓取频率</th>
				<th>抓取方式</th>
				<th>编码</th>
				
				<th>操作</th>
			</tr>
			<?php foreach ($data['rows'] as $key => $row) { ?>
			<tr sid="<?php echo $row['id'] ?>">
				<td><?php echo $row['id']; ?></td>
				
				<td><?php echo $dataDefine['category']['enum'][$row['category']]; ?></td>
				<td><?php echo $data['aTag'][$row['contentName_tagid']]; ?></td>
				
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'title' , $row['title']); ?></td>
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'codeSign' , $row['codeSign']); ?></td>
				<td><a href="<?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'rss' , $row['rss']); ?>">查看</a></td>
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'domain' , $row['domain']); ?></td>
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'site_name' , $row['site_name']); ?></td>
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'tags' , $row['tags']); ?></td>
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'language' , $row['language']); ?></td>
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'spider_time' , $row['spider_time']); ?></td>
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'spider_type' , $row['spider_type']); ?></td>
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'charset' , $row['charset']); ?></td>
				
				<td>
					<a href="adm_wrcArticle.php?srcId=<?php echo $row['id'] ?>">文章列表</a>
					<a href="?dtdfn=<?php echo $data['_dataDefine'] ?>&page=editForm&id=<?php echo $row['id'] ?>">编辑</a>
					<a href="?dtdfn=<?php echo $data['_dataDefine'] ?>&api=reRedis&id=<?php echo $row['id'] ?>">重建索引</a>
					<a href="?dtdfn=<?php echo $data['_dataDefine'] ?>&page=findKeyWord&id=<?php echo $row['id'] ?>">关键词库</a>
					<a href="?dtdfn=<?php echo $data['_dataDefine'] ?>&page=findKeyWordGroup&id=<?php echo $row['id'] ?>">词组</a>
					<a href="?dtdfn=<?php echo $data['_dataDefine'] ?>&api=setStatusById&id=<?php echo $row['id'] ?>&status=<?php echo $row['status']==ml_model_wrcSource::STATUS_STOP?ml_model_wrcSource::STATUS_NORMAL:ml_model_wrcSource::STATUS_STOP;?>"><?php echo $row['status']==ml_model_wrcSource::STATUS_STOP?'<font color="gray">禁用中</font>':'<font color="green">正常</font>';?></a>
					<a href="javascript:;" onclick="if(window.confirm('xxx')){window.location='?dtdfn=<?php echo $data['_dataDefine'] ?>&api=delById&id=<?php echo $row['id'] ?>'}"><font color="red">删除</font></a>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="<?php echo count($dataDefine['field'])+2; ?>"><?php echo ml_tool_admin_view::get_page($data['total'] , $data['pagesize'] , $data['page']); ?></td>
			</tr>
		</table>
		<script type="text/javascript">
			$('.selCategory').change(function(){
				sid = $(this).parent().parent().attr('sid');
				window.location.href='?api=changeCategoryById&id='+sid+'&category='+$(this).val();
			});
		</script>
<?php
	}

	function page_findKeyWordGroup($data){
		?>
		<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<tr>
				<th>词组</th>
				<th>出现文章数</th>
				<th>idf</th>
			</tr>
			<?php
				foreach ($data['sort'] as $key => $value) {
					?>
				<tr>
					<td><?php echo $key; ?></td>
					<td><?php echo $value; ?></td>
					<td><?php echo implode(' ', $data['words'][$key]['idf']); ?></td>
				</tr>
					<?php
				}
			?>
			
		</table>
		<?php
	}
	function page_findKeyWord($data){
		?>
		<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<tr>
				<th>词组</th>
				<th>出现文章数</th>
				<th>idf</th>
			</tr>
			<?php
				foreach ($data['sort'] as $key => $value) {
					?>
				<tr>
					<td><?php echo $key; ?></td>
					<td><?php echo $value; ?></td>
					<td><?php echo $data['words'][$key]['idf'].' '.$data['words'][$key]['attr'] ; ?></td>
				</tr>
					<?php
				}
			?>
			
		</table>
		<?php
	}
?>
