<?php 
	function page_addForm($data)
	{
		$dataDefine=ml_factory::load_dataDefine($data['_dataDefine']);
		$dataDefine['field']['job_id']['enum'][0] = '无';
		
?>
		<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<form action="?dtdfn=<?php echo $data['_dataDefine'] ?>&api=add" method="post">
			<tr>
				<th colspan="2">新增</th>
			</tr>
			<tr>
				<td>所属职业：</td>
			<td>
				<?php 
					echo ml_tool_admin_view::dtdfn_input($dataDefine['field']['job_id']['type'] , 'job_id' , $dataDefine['field']['job_id'] ,$data['job_id'], 'selJobid'); 
				?>
			</td>
			</tr>
			<tr>
				<td>内容类型</td>
				<td><?php 
					echo ml_tool_admin_view::dtdfn_input($dataDefine['field']['level']['type'] , 'level' , $dataDefine['field']['level'] ,$data['level'], 'selLevel'); 
				?></td>
			</tr>
			<tr>
				<td>内容名称</td>
				<td><?php foreach ($data['contentName'] as $tag => $tag_hash) { ?>

					<input type="checkbox" name="contentName[<?php echo $tag_hash ?>]" value="<?php echo $tag_hash; ?>" id="cn_<?php echo $tag_hash; ?>"<?php if($data['jobContent'][$tag_hash]){echo ' checked disabled';} ?>><span for="cn_<?php echo $tag_hash; ?>"><?php echo $tag; ?></span>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					难度：
					<?php
						echo ml_tool_admin_view::dtdfn_input($dataDefine['field']['level']['type'] , 'level['.$tag_hash.']' , $dataDefine['field']['level'] ,$data['level'], 'selJobid'); 
					?>
					推荐级别：
					<?php
						echo ml_tool_admin_view::dtdfn_input($dataDefine['field']['recommend_level']['type'] , 'recommend_level['.$tag_hash.']' , $dataDefine['field']['recommend_level'] ,$data['recommend_level'], 'selJobid'); 
					?>
					<br/>
					<?php } ?>
					</td>
			</tr>
						
			<tr>
				<td colspan="2"><input type="submit" value="保存"/></td>
			</tr>
			</form>
		</table>
		<script type="text/javascript">
		
			
			$('#selJobid').change(function(){
				location.href='?page=addForm&job_id='+$(this).val();
			});
			$('#selLevel').change(function(){
				location.href='?page=addForm&job_id=<?php echo $data['job_id'] ?>&level='+$(this).val();
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
				
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'job_id' , $row['job_id']); ?></td>
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'level' , $row['level']); ?></td>
				<td><?php echo $row['contentName']; ?></td>
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'recommend_level' , $row['recommend_level']); ?></td>
				
				
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
