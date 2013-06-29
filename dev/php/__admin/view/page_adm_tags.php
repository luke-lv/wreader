<?php
function page_index($adm)
{
    global $ML_TAG_TYPE;
    $aId2tag = array_flip($ML_TAG_TYPE);
?>
<table class="adminlist" width="100%">
<tr>
<td width="50%">
<?php foreach ($aId2tag as $type => $name) {
    echo '<a href="?type='.$type.'">'.$name.'</a> | ';
} ?>

<?php if($adm['sub_type']){ ?>
<br/>
<?php foreach ($adm['sub_type'] as $key => $value) {
    echo '<a href="?type='.ML_TAGTYPE_COLOR.'&subtype='.$key.'">'.$value.'</a> | ';

}} ?>
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
    <th>#</th>
    <th>标签</th>
    <th>分类</th>
    <th>核心标签</th>
    <th>子分类</th>
    <th>操作</th>
</tr>
<?php foreach ($adm['tags'] as $key => $value) {?>
<tr>
    <td><a id="id<?php echo $value['id']; ?>" name="id<?php echo $value['id']; ?>"></a><?php echo $value['id']; ?></td>
    <td><?php echo $value['tag']; ?></td>
    <td>
        <select name="type" onchange="window.location='?api=changeTypeById&id=<?php echo $value['id']; ?>&type='+this.value">
        <?php foreach ($ML_TAG_TYPE as $typename => $id) { ?>
        <option value="<?php echo $id; ?>"<?php if($id==$value['type']){echo ' selected';} ?>><?php echo $typename; ?></option>
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
    <td><?php echo $adm['sub_type'][$value['sub_type']]; ?></td>
    <td>
        <a href="?api=delTag&id=<?php echo $value['id']; ?>"><font color="red">删除</font></a>
        推荐分数：<select name="pt" onchange="window.location='?api=changePtById&id=<?php echo $value['id']; ?>&pt='+this.value">
        <?php for ($i=0; $i < 5; $i++){ ?>
        <option value="<?php echo $i; ?>"<?php if($i==$value['suggest_pt']){echo ' selected';} ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>

        <?php if($adm['sub_type']){ ?>
        修改子分类：<select name="sub_type" onchange="window.location='?api=changeSubTypeById&id=<?php echo $value['id']; ?>&sub_type='+this.value">
        <?php foreach ($adm['sub_type'] as $id => $typename) { ?>
        <option value="<?php echo $id; ?>"<?php if($id==$value['sub_type']){echo ' selected';} ?>><?php echo $typename; ?></option>
        <?php } ?>
    </select>
        <?php } ?>
    </td>
</tr>
<?php } ?>
<tr>
    <td colspan="4"><?php  echo ml_tool_admin_view::get_page($adm['total'] , 20 , $adm['page']);  ?></td>
</tr>
</table>
<table class="adminlist" width="100%">
<form action="?api=batch_add" method="post">
<tr>
    <td>
标签(每行一个)：<br/><textarea name="tags"></textarea><br/>
分类：<br/>
    <select name="type">
        <?php foreach ($ML_TAG_TYPE as $typename => $id) { ?>
        <option value="<?php echo $id; ?>"><?php echo $typename; ?></option>
        <?php } ?>
    </select><br/>
核心标签：<br/>
    <select name="core_tagid">
        <?php foreach ($adm['coreTag'] as $id => $core_tag) { ?>
        <option value="<?php echo $id; ?>"><?php echo $core_tag; ?></option>
        <?php } ?>
    </select><br/>
    <input type="submit" value="保存"/> <a href="?api=rebuildRdsTaghash">重建标签类型索引</a>
    </td>
</tr>
</table>
<?php
}

function page_redisTagStat($data)
{
    foreach ($data['tags'] as $key => $value) {
        echo $value['tag'].''.$value['article_cnt']."<br/>";
    }
}
?>
