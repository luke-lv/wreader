<?php 
	function page_addForm($data)
	{
		$dataDefine=ml_factory::load_dataDefine($data['_dataDefine']);
?>
		<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<form action="?dtdfn=<?php echo $data['_dataDefine'] ?>&api=add" method="post">
			<tr>
				<th colspan="2">新增</th>
			</tr>
			<?php foreach ($dataDefine['field'] as $name => $value) { ?>
			<tr>
				<td><?php echo $value['cn'] ?></td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($value['type'] , $name , $value); ?></td>
			</tr>
			<?php } ?>
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

?>
		<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<form action="?dtdfn=<?php echo $data['_dataDefine'] ?>&api=edit&id=<?php echo $data['row']['id']; ?>" method="post">
			<tr>
				<th colspan="2">编辑</th>
			</tr>
				<?php foreach ($dataDefine['field'] as $key => $value) { ?>
				<tr>
					<td><?php echo $value['cn'] ?></td>
					<td><?php echo ml_tool_admin_view::dtdfn_input($value['type'] , $key , $value , $data['row'][$key]); ?></td>
				</tr>
				<?php } ?>
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
?>
		<a href="?dtdfn=<?php echo $data['_dataDefine']; ?>&page=addForm">新增</a>
		<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<tr>
				<th>#</th>
				
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
			<tr>
				<td><?php echo $row['id']; ?></td>
				
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
					<a href="javascript:;" onclick="if(window.confirm('xxx')){window.location='?dtdfn=<?php echo $data['_dataDefine'] ?>&api=delById&id=<?php echo $row['id'] ?>'}"><font color="red">删除</font></a>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="<?php echo count($dataDefine['field'])+2; ?>"><?php echo ml_tool_admin_view::get_page($data['total'] , $data['pagesize'] , $data['page']); ?></td>
			</tr>
		</table>
<?php
	}
?>
