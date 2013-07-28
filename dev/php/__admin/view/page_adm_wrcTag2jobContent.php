<?php 
	function page_addForm($data)
	{
		$dataDefine=ml_factory::load_dataDefine($data['_dataDefine']);
		global $ML_TAG_CATEGORY;
?>
		<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<form action="?dtdfn=<?php echo $data['_dataDefine'] ?>&api=add" method="post">
			<tr>
				<th colspan="2">新增</th>
			</tr>
			<tr>
				<td>标签1</td>
				<td><?php echo ml_tool_admin_view::html_select('category' , array_flip($ML_TAG_CATEGORY) , $data['category'] , 'selCategory'); ?></td>
			</tr>
			<tr>
				<td>职业能力</td>
				<td><?php echo ml_tool_admin_view::html_select('jobContentId' , $data['aJobContent']); ?></td>
			</tr>
			<tr>
				<td>标签1</td>
				<td><input type="text" name="tag_1"/></td>
			</tr>
			<tr>
				<td>标签2</td>
				<td><input type="text" name="tag_2"/></td>
			</tr>


			<tr>
				<td colspan="2"><input type="submit" value="保存"/></td>
			</tr>
			</form>
		</table>
		<script type="text/javascript">
			$('#selCategory').change(function(){
				window.location.href='?page=addForm&category='+$(this).val();
			});
		</script>
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
		global $ML_TAG_CATEGORY;
		$categorys = array_flip($ML_TAG_CATEGORY);
?>
		<a href="?dtdfn=<?php echo $data['_dataDefine']; ?>&page=addForm">新增</a>
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
				<td><?php echo $categorys[$row['category']]; ?></td>
				<td><?php echo $row['jobContent']; ?></td>
				<td><?php echo $row['tag_1']; ?></td>
				<td><?php echo $row['tag_2']; ?></td>
				
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
