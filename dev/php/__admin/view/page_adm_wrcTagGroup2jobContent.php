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
				<td>领域</td>
				<td><?php echo ml_tool_admin_view::html_select('category' , array_flip($ML_TAG_CATEGORY) , $data['category'] , 'selCategory'); ?></td>
			</tr>
			<tr>
				<td>职业能力</td>
				<td><?php echo ml_tool_admin_view::html_select('jobContentId' , $data['aJobContent']); ?></td>
			</tr>
			<tr>
				<td>内容名称</td>
				<td><?php echo ml_tool_admin_view::html_select('contentName_tagid' , $data['aContentName']); ?></td>
			</tr>
			<tr>
				<td>辅助标签</td>
				<td><textarea name="tags"></textarea></td>
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
		global $ML_TAG_CATEGORY;
		$category = array_flip($ML_TAG_CATEGORY);

?>
		<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<form action="?dtdfn=<?php echo $data['_dataDefine'] ?>&api=edit&id=<?php echo $data['row']['id']; ?>" method="post">
			<tr>
				<th colspan="2">编辑</th>
			</tr>
				<tr>
				<td>领域</td>
				<td><?php echo $category[$data['row']['category']]; ?></td>
			</tr>
			<tr>
				<td>职业能力</td>
				<td><?php echo $data['jobContent']['name']; ?></td>
			</tr>
			<tr>
				<td>内容名称</td>
				<td><?php echo $data['contentName']['tag']; ?></td>
			</tr>
			<tr>
				<td>辅助标签</td>
				<td><textarea name="tags"><?php echo implode(' ',$data['row']['tags']); ?></textarea></td>
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
		$jobConf = ml_factory::load_standard_conf('wreader_jobs');
		$jobConf = Tool_array::format_2d_array($jobConf , 'tag_category' , Tool_array::FORMAT_VALUE2VALUE2 , 'name');
?>
	<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<tr>
				<th><?php foreach ($jobConf as $key => $value) { ?>
					<a href="?category=<?php echo $key; ?>"><?php echo $value ?></a>
				<?php } ?></th>

			</tr>

		</table>
		<a href="?dtdfn=<?php echo $data['_dataDefine']; ?>&page=addForm">新增</a>
		<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<tr>
				<th>#</th>
				<th>领域</th>
				<th>职业能力</th>
				<th>内容名称</th>
				<th>辅助标签</th>
				<th>操作</th>
			</tr>
			<?php foreach ($data['rows'] as $key => $row) { ?>
			<tr>
				<td><?php echo $row['id']; ?></td>
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'category' , $row['category']); ?></td>
				<td><?php echo $data['aJobContent'][$row['jobContentId']] ?></td>
				<td><?php echo $data['aContentName'][$row['contentName_tagid']] ?></td>
				<td><?php echo implode(' ',$row['tags']); ?></td>
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
