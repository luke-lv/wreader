<?php
function page_index($adm)
{
    global $ML_TAG_CATEGORY , $ML_TAG_TYPE;
    $aId2tag = array_flip($ML_TAG_CATEGORY);
    $idfList = array_combine($adm['idfList'], $adm['idfList']);
?>
<table class="adminlist" width="100%">
<tr>
<td width="50%">
    <a href="?page=addForm">新建标签</a>
<?php foreach ($aId2tag as $ctg => $name) {
    echo '<a href="?category='.$ctg.'">'.$name.'</a> | ';
} ?>
    <a href="?">全部</a>
</td>
<td width="30%">
    <a href="?page=nearHotTag">最近常见标签</a>
    <a href="?page=redisTagStat">标签redis统计</a>
</td>
<td width="20%">
<form method="get" action="?">
    <input type="text" name="tag"/>
    <input type="submit" value="查找"/>
</form>
</td>
</tr></table>
<table class="adminlist" width="100%">
<tr>
    <th>#<input type="checkbox" id="cbSelAll" value=""/></th>
    <th>标签</th>
    <th>类型 (<a href="?category=<?php echo $adm['category']; ?>&type=<?php echo ML_TAGTYPE_CONTENTNAME; ?>">内容名称</a>|<a href="?category=<?php echo $adm['category']; ?>&type=<?php echo ML_TAGTYPE_CONTENTTYPE; ?>">内容方向</a>)</th>
    <th>分类</th>
    <th>核心标签（<a href="?category=<?php echo $adm['category']; ?>&is_core=true">查看</a>）</th>
    <th>难度级别</th>
    <th>分词IDF</th>
    <th>职业能力</th>
    <th>操作</th>
</tr>
<form id="formList" method="post">
<?php foreach ($adm['tags'] as $key => $value) {?>
<tr class="trTag" tagid="<?php echo $value['id']; ?>">
    <td><a id="id<?php echo $value['id']; ?>" name="id<?php echo $value['id']; ?>"></a><?php echo $value['id']; ?>
        <input type="checkbox" class="cbList" name="ids[]" value="<?php echo $value['id']; ?>"/></td>
    <td><?php echo $value['tag']; ?></td>
    <td>
        <select name="type" onchange="window.location='?api=changeTypeById&id=<?php echo $value['id']; ?>&type='+this.value">
        <?php foreach ($ML_TAG_TYPE as $type => $id) { ?>
        <option value="<?php echo $id; ?>"<?php if($id==$value['type']){echo ' selected';} ?>><?php echo $type; ?></option>
        <?php } ?>
    </select>

        <select name="type" onchange="window.location='?api=changeContentNameById&id=<?php echo $value['id']; ?>&tag_id='+this.value">
            <option value="0">无</option>
        <?php foreach ($adm['contentNameTag'] as $id => $v) { ?>
        <option value="<?php echo $id; ?>"<?php if($id==$value['contentName_tagid']){echo ' selected';} ?>><?php echo $v; ?></option>
        <?php }?>
        </select>

        <select name="type" onchange="window.location='?api=changeContentTypeById&id=<?php echo $value['id']; ?>&tag_id='+this.value">
            <option value="0">无</option>
        <?php foreach ($adm['contentTypeTag'] as $id => $v) { ?>
        <option value="<?php echo $id; ?>"<?php if($id==$value['contentType_tagid']){echo ' selected';} ?>><?php echo $v; ?></option>
        <?php }?>
        </select>
    </td>
    <td>
        <select name="category" onchange="window.location='?api=changeCategoryById&id=<?php echo $value['id']; ?>&category='+this.value">
        <?php foreach ($ML_TAG_CATEGORY as $categoryname => $id) { ?>
        <option value="<?php echo $id; ?>"<?php if($id==$value['category']){echo ' selected';} ?>><?php echo $categoryname; ?></option>
        <?php } ?>
    </select>
    </td>
    <td>
        <a href = "?api=changeIsCoreById&id=<?php echo $value['id']; ?>&value=<?php echo $value['is_core']==0?'1':'0'; ?>"><font color="<?php echo $value['is_core']?'#ff0000':'bcbcbc'; ?>"><?php echo $value['is_core']?'核心标签':'普通标签'; ?></font></a>
        <select name="core_tagid" onchange="window.location='?api=changeCoreTagidById&id=<?php echo $value['id']; ?>&coreTagid='+this.value">
        <?php foreach ($adm['coreTag'] as $id => $coreTag) { ?>
        <option value="<?php echo $id; ?>"<?php if($id==$value['core_tagid']){echo ' selected';} ?>><?php echo $coreTag; ?></option>
        <?php } ?>
    </select>
    </td>
    <td><select name="level" onchange="window.location='?api=changeLevelById&id=<?php echo $value['id']; ?>&level='+this.value">
        <?php for($i=0;$i<5;$i++) { ?>
        <option value="<?php echo $i; ?>"<?php if($i==$value['level']){echo ' selected';} ?>><?php echo $i; ?></option>
        <?php } ?></td>
    <td>
        <?php echo ml_tool_admin_view::html_select('segment_idf',$idfList , $value['segment_idf'] ,'', 'selIdf'); ?>
    </td>
    <td>
        <?php echo ml_tool_admin_view::html_select('jobContentId',$adm['jobContent'] , $value['jobContentId'] , '' , 'selJc' , true); ?>
    </td>
    <td>
        <a href="?api=delTag&id=<?php echo $value['id']; ?>"><font color="red">删除</font></a>
        推荐分数：<select name="pt" onchange="window.location='?api=changePtById&id=<?php echo $value['id']; ?>&pt='+this.value">
        <?php for ($i=0; $i < 5; $i++){ ?>
        <option value="<?php echo $i; ?>"<?php if($i==$value['suggest_pt']){echo ' selected';} ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>

    </td>
</tr>
<?php } ?>
</form>
<tr>
    <td colspan="4"><?php  echo ml_tool_admin_view::get_page($adm['total'] , 50 , $adm['page']);  ?></td>
    <td colspan="4"><input type="button" id="btnDel" value="删除" style="background-color:red;"/></td>
</tr>
</table>

<script type="text/javascript">
    $('.selIdf').change(function(){
        tagid=$(this).parent().parent().attr('tagid');
        
        window.location.href = '?api=changeIdfById&id='+tagid+'&idf='+$(this).val();
    });
    $('.selJc').change(function(){
        tagid=$(this).parent().parent().attr('tagid');
        
        window.location.href = '?api=changeJobContentIdById&id='+tagid+'&jobContentId='+$(this).val();
    });
    $('#cbSelAll').click(function(){
        if(!$(this).attr('checked')){
            $('.cbList').each(function(){
                $(this).attr('checked' , false);
            });
        }else{
            $('.cbList').each(function(){
                $(this).attr('checked' , true);
            });
        }
    });
    $('#btnDel').click(function(){
        if(window.confirm('xxx')){
            $('#formList').attr('action' , '?api=delByIds');
            $('#formList').submit();
        }
    });
</script>
<?php
}

function page_addForm($data)
{
    global $ML_TAG_CATEGORY;
    ?>
<table class="adminlist" width="100%">
<form action="?api=batch_add" method="post">
<tr>
    <td>
        分类：<br/>
    <?php echo ml_tool_admin_view::html_select('category' , array_flip($ML_TAG_CATEGORY) , $data['category'] , 'selCategory');?><br/>

标签(每行一个)：<br/><textarea name="tags"></textarea><br/>

内容名称：
    <?php echo ml_tool_admin_view::html_select('contentName_tagid' , $data['aCnTag'] , '' , '' , '' , true); ?><br/>

内容类型：
    <?php echo ml_tool_admin_view::html_select('contentType_tagid' , $data['aCtTag'] , '' , '' , '' , true); ?><br/>

    <input type="submit" value="保存"/> <a href="?api=rebuildRdsTaghash">重建标签类型索引</a>
    </td>
</tr>
</table>
<script>
    $('#selCategory').change(function(){
        window.location.href='?page=addForm&category='+$(this).val();
    });
</script>
    <?php
}

function page_redisTagStat($data)
{
    foreach ($data['tags'] as $key => $value) {
        echo $value['tag'].''.$value['article_cnt']."<br/>";
    }
}
?>
