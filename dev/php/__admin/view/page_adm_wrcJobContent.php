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
				<td>内容领域</td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['category']['type'] , 'category' , $dataDefine['category'] ,$data['category'] , 'selCategory'); ?></td>
			</tr>
			
			<tr>
				<td>级别</td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['level']['type'] , 'level' , $dataDefine['level'] ,$data['level'], 'selLevel'); ?></td>
			</tr>
			<tr>
				<td>能力名称</td>
				<td><input type="text" name="name"/></td>
			</tr>
			<tr>
				<td>内容名称</td>
				<td><?php echo ml_tool_admin_view::html_select('contentName_tagid' , $data['contentName'] , '' , '' , '' , true); ?></td>
			</tr>
			<tr>
				<td>内容方向</td>
				<td><?php echo ml_tool_admin_view::html_select('contentType_tagid' , $data['contentType'] , '' , '' , '' , true); ?></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" value="保存"/></td>
			</tr>
			</form>
		</table>
		<script type="text/javascript">
			$('#selCategory').change(function(){
				
				location.href='?page=addForm&category='+$(this).val();
			});
		</script>
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
				<td>领域</td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['category']['type'] , 'category' , $dataDefine['category'] ,$data['row']['category'], 'selLevel'); ?></td>
			</tr>
			<tr>
				<td>级别</td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['level']['type'] , 'level' , $dataDefine['level'] ,$data['row']['level'], 'selLevel'); ?></td>
			</tr>
			<tr>
				<td>能力名称</td>
				<td><input type="text" name="name" value="<?php echo $data['row']['name']; ?>"/></td>
			</tr>
			<tr>
				<td>内容名称</td>
				<td><?php echo ml_tool_admin_view::html_select('contentName_tagid' , $data['contentName'] , $data['row']['contentName_tagid'] , '' , '' , true); ?></td>
			</tr>
			<tr>
				<td>内容方向</td>
				<td><?php echo ml_tool_admin_view::html_select('contentType_tagid' , $data['contentType'] , $data['row']['contentType_tagid']); ?></td>
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
		global $ML_TAG_CATEGORY;
?>
		<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<tr><th>
		<?php foreach ($ML_TAG_CATEGORY as $category => $id) { ?>
			<a href="?category=<?php echo $id?>"><?php echo $category; ?></a>
		<?php } ?>
			</th></tr>
			<tr><td>
		<?php foreach ($data['aCnTag'] as $key => $value) { ?>
			<a href="?category=<?php echo $data['category'] ?>&contentName_tagid=<?php echo $key; ?>"><?php echo $value; ?></a>
		<?php } ?>
			</td></tr>
		</table>
		<a href="?dtdfn=<?php echo $data['_dataDefine']; ?>&page=addForm">新增</a>
		<a href="?dtdfn=<?php echo $data['_dataDefine']; ?>&page=redisStat">fff</a>
		<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<tr>
				<th>#</th>
				<?php foreach ($dataDefine['field'] as $key => $value) {?>
				<th><?php echo $value['cn']; ?></th>
				<?php } ?>
				<th>操作</th>
			</tr>
			<?php foreach ($data['rows'] as $key => $row) { ?>
			<tr>
				<td><?php echo $row['id']; ?></td>
				
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'category' , $row['category']); ?></td>
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'name' , $row['name']); ?></td>
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'level' , $row['level']); ?></td>
				<td><?php echo $row['contentName']; ?></td>
				<td><?php echo $row['contentType']; ?></td>
				<td>
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
?>
