<?php 
	function page_addForm($data)
	{
		$dataDefine=ml_factory::load_dataDefine($data['_dataDefine']);
		$dataDefine = $dataDefine['field'];
		global $ML_TAG_CATEGORY , $ML_RECOMMENDLEVEL;



?>
		<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<form action="?dtdfn=<?php echo $data['_dataDefine'] ?>&api=add" method="post">
			<tr>
				<th colspan="2">新增</th>
			</tr>
			
			<tr>
				<td>职业</td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['job_id']['type'] , 'job_id' , $dataDefine['job_id'] ,$data['job_id'], 'selJobId'); ?></td>
			</tr>
			<tr>
				<td>级别</td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['level']['type'] , 'level' , $dataDefine['level'] ,$data['level'], ''); ?></td>
			</tr>
			<tr>
				<td>领域</td>
				<td><?php foreach ($ML_TAG_CATEGORY as $cn => $value) {
					?>
					<input type="checkbox" class="cbCategory" name="category[]" value=<?php echo $value; ?><?php if(in_array($value, $data['category'])){echo ' checked';} ?>><?php echo $cn; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php
				} ?></td>
			</tr>
			<tr>
				<td>能力</td>
				<td><?php foreach ($data['aJobContent'] as $jobContentId => $name) {
					?>
					<input type="checkbox" name="jobContentId[]" value=<?php echo $jobContentId; ?>><?php echo $name; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					推荐级别：<?php echo ml_tool_admin_view::html_select('recommendlevel['.$jobContentId.']' , array_flip($ML_RECOMMENDLEVEL)); ?>
					<br/>
				<?php } ?></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" value="保存"/></td>
			</tr>
			</form>
		</table>
		<script type="text/javascript">
			$('.cbCategory').click(function(){
				var category = '';
				$('.cbCategory').each(function(){
					if($(this).attr('checked') == 'checked'){
						category += $(this).attr('value')+',';
					}
				});
				window.location.href='?page=addForm&job_id='+$('#selJobId').val()+'&category='+category;
			});
		</script>
<?php
	}

	function page_editForm($data)
	{
		$dataDefine=ml_factory::load_dataDefine($data['_dataDefine']);
		$dataDefine = $dataDefine['field'];
		global $ML_TAG_CATEGORY , $ML_RECOMMENDLEVEL;
?>
		<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<form action="?dtdfn=<?php echo $data['_dataDefine'] ?>&api=edit&id=<?php echo $data['row']['id']; ?>" method="post">
			<tr>
				<th colspan="2">编辑</th>
			</tr>
			<tr>
				<td>职业</td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['job_id']['type'] , 'job_id' , $dataDefine['job_id'] ,$data['row']['job_id'], 'selJobId'); ?></td>
			</tr>
			<tr>
				<td>级别</td>
				<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['level']['type'] , 'level' , $dataDefine['level'] ,$data['row']['level']); ?></td>
			</tr>
			<tr>
				<td>领域</td>
				<td><?php foreach ($ML_TAG_CATEGORY as $cn => $value) {
					?>
					<input type="checkbox" class="cbCategory" name="category[]" value=<?php echo $value; ?><?php if(in_array($value, $data['category'])){echo ' checked';} ?>><?php echo $cn; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php
				} ?></td>
			</tr>
			<tr>
				<td>能力</td>
				<td><?php foreach ($data['aJobContent'] as $jobContentId => $name) {
					?>
					<input type="checkbox" name="jobContentId[]" value=<?php echo $jobContentId; ?><?php if(in_array($jobContentId, array_keys($data['row']['jobContentIds']))){echo ' checked';} ?>><?php echo $name; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="jobContentId[]" value=<?php echo $jobContentId; ?>><?php echo $name; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					推荐级别：<?php echo ml_tool_admin_view::html_select('recommendlevel['.$jobContentId.']' , array_flip($ML_RECOMMENDLEVEL) , $data['row']['jobContentIds'][$jobContentId]['rcmdLv']); ?>
					<br/>
					<?php
				} ?></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" value="保存"/></td>
			</tr>
			</form>
		</table>
		<script type="text/javascript">
			$('.cbCategory').click(function(){
				var category = '';
				$('.cbCategory').each(function(){
					if($(this).attr('checked') == 'checked'){
						category += $(this).attr('value')+',';
					}
				});
				window.location.href='?page=editForm&job_id='+$('#selJobId').val()+'&category='+category;
			});
		</script>
<?php
	}

	function page_index($data)
	{
		$dataDefine=ml_factory::load_dataDefine($data['_dataDefine']);
		$dataDefine= $dataDefine['field'];
?>
		<a href="?dtdfn=<?php echo $data['_dataDefine']; ?>&page=addForm">新增</a>
		<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<tr>
				<th>#</th>
				
				<th>职业</th>
				<th>级别</th>
				<th>能力</th>
				<th>操作</th>
			</tr>
			<?php foreach ($data['rows'] as $key => $row) { ?>
			<tr>
				<td><?php echo $row['id']; ?></td>
				
				<td><?php ml_tool_admin_view::echoline('wrcJob2jobContent' , 'job_id' , $row['job_id']); ?></td>
				<td><?php ml_tool_admin_view::echoline('wrcJob2jobContent' , 'level' , $row['level']); ?></td>
				<td><?php foreach ($row['jobContentIds'] as $jcid => $jc_param) {
					echo $data['aJobContent'][$jcid].' , '; 
				} ?></td>
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
