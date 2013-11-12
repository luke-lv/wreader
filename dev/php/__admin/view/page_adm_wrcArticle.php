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
		$dataDefine = $dataDefine['field'];

?>
		<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<form action="?dtdfn=<?php echo $data['_dataDefine'] ?>&api=edit&id=<?php echo $data['row']['id']; ?>" method="post">
			<tr>
				<th colspan="2">编辑</th>
			</tr>
				
				<tr>
					<td>标题</td>
					<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['title']['type'] , 'title' , $dataDefine['title'] , $data['row']['title']); ?></td>
				</tr>
				<tr>
					<td>发布时间</td>
					<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['pub_time']['type'] , 'pub_time' , $dataDefine['pub_time'] , $data['row']['pub_time']); ?></td>
				</tr>
				<tr>
					<td>摘要</td>
					<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['summary']['type'] , 'summary' , $dataDefine['summary'] , $data['row']['summary']); ?></td>
				</tr>
				<tr>
					<td>链接</td>
					<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['link']['type'] , 'link' , $dataDefine['link'] , $data['row']['link']); ?></td>
				</tr>
				<tr>
					<td>标签</td>
					<td><?php echo ml_tool_admin_view::dtdfn_input($dataDefine['tags']['type'] , 'tags' , $dataDefine['tags'] , implode(' ',$data['row']['tags'])); ?></td>
				</tr>
				<tr>
					<td>职业能力</td>
					<td><?php echo ml_tool_admin_view::html_select('jobContentId' , $data['aJobContent'] , $data['row']['jobContentId']); ?></td>
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
		$year = date('y');

		for($i=0;$i>-6;$i--){ 
			
			$yearMonth = Tool_date::offsetMonth($i);
		?>
		<a href="?ym=<?php echo $yearMonth ?>&srcId=<?php echo $data['srcId']; ?>"><?php echo $yearMonth; ?></a> 
		<?php } ?>
		<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
			<tr>
				<th>#</th>
				<th>标题</th>
				<th>标签</th>
				<th>发布时间</th>
				<th>所属职业能力</th>
				<th>链接</th>
				<th>操作</th>
			</tr>
			<?php foreach ($data['rows'] as $key => $row) { ?>
			<tr aid="<?php echo $row['id']; ?>">
				<td><?php echo $row['id']; ?></td>
				<td><span title="<?php echo Tool_string::un_html($row['title']); ?>"><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'title' , $row['title'] , array(ml_tool_admin_view::ECHO_EXTRA_LEN => 30)); ?></span></td>
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'tags' , implode(' ' , $row['tags'])); ?></td>
				<td><?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'pub_time' , $row['pub_time']); ?></td>
				<td><?php echo ml_tool_admin_view::html_select('jobContentId' , $data['aJobContent'] , $row['jobContentId'][0] , '' , 'selJobContent' , true); ?></td>
				<td><a href="<?php ml_tool_admin_view::echoline($data['_dataDefine'] , 'link' , $row['link']); ?>" target="_blank">链接</a></td>
				<td>
					<a href="?dtdfn=<?php echo $data['_dataDefine'] ?>&page=articleShow&id=<?php echo $row['id'] ?>">查看</a>
					<a href="?dtdfn=<?php echo $data['_dataDefine'] ?>&page=editForm&id=<?php echo $row['id'] ?>">编辑</a>
					<a href="?dtdfn=<?php echo $data['_dataDefine'] ?>&api=reSegment&id=<?php echo $row['id'] ?>">分词</a>
					<a href="?dtdfn=<?php echo $data['_dataDefine'] ?>&api=seg2wordgroup&id=<?php echo $row['id'] ?>">词组</a>
					<a href="javascript:;" onclick="if(window.confirm('xxx')){window.location='?dtdfn=<?php echo $data['_dataDefine'] ?>&api=delById&id=<?php echo $row['id'] ?>'}"><font color="red">删除</font></a>
					<a href="javascript:;" onclick="if(window.confirm('xxx')){window.location='?dtdfn=<?php echo $data['_dataDefine'] ?>&api=realDelById&id=<?php echo $row['id'] ?>'}"><font color="red">[真删除]</font></a>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="<?php echo count($dataDefine['field'])+2; ?>"><?php echo ml_tool_admin_view::get_page($data['total'] , $data['pagesize'] , $data['page']); ?></td>
			</tr>
		</table>
		<script type="text/javascript">
			$('.selJobContent').change(function(){
				aid = $(this).parent().parent().attr('aid');
				window.location.href="?api=changeJobContentIdById&id="+aid+"&jobContentId="+$(this).val();
			});
		</script>
<?php
	}
	function page_articleShow($data)
	{
?>
	<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
		<tr>
			<th><?php echo $data['articleRow']['title'].' <a href="adm_wrcArticle.php?ym='.$data['ym'].'&srcId='.$data['source']['id'].'">'.$data['source']['title'].' '.$data['ym'].'</a>'; ?></th>
		</tr>
		<tr>
			<td><?php echo strip_tags($data['articleRow']['content']); ?></td>
		</tr>
	</table>
<?php
	}
?>
