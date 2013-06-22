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
		<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<tr>
				<th>#</th>
				<th>标题</th>
				<th>标签</th>
				<th>发布时间</th>
				<th>链接</th>
				<th>操作</th>
			</tr>
			<?php foreach ($data['rows'] as $key => $row) { ?>
			<tr>
				<td><?php echo $row['id']; ?></td>
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'title' , $row['title']); ?></td>
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'tags' , $row['tags']); ?></td>
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'pub_time' , $row['pub_time']); ?></td>
				<td><a href="<?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'link' , $row['link']); ?>" target="_blank">链接</a></td>
				<td>
					<a href="?dtdfn=<?php echo $data['_dataDefine'] ?>&page=articleShow&id=<?php echo $row['id'] ?>">查看</a>
					<a href="?dtdfn=<?php echo $data['_dataDefine'] ?>&page=editForm&id=<?php echo $row['id'] ?>">编辑</a>
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
	function page_articleShow($data)
	{
?>
	<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
		<tr>
			<th><?php echo $data['articleRow']['title']; ?></th>
		</tr>
		<tr>
			<td><?php echo $data['articleRow']['content']; ?></td>
		</tr>
	</table>
<?php
	}
?>
