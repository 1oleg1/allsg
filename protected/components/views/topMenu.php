
<script type="text/javascript">
$(function() {
    var loc = window.location.href;
    $('.navbar li').each(function() {
        var link = $(this).find('a:first').attr('href');
        if(loc.indexOf(link) >= 0)
            $(this).addClass('active');
    });
});
</script>

<div class="navbar">
<ul class="nav nav-pills ddmenu">
<?php
foreach ($items as $item) {
	echo '<li>'.CHtml::link($item['title'], $item['link'], array('class'=>$item['class'])).'</li>';
}
?>
</ul>
<?
//a image link list with the array(language=>image,...) as first param 
///$links = Yii::app()->urlManager->getLanguageImageLinks(array('en'=>'images/flags/en.gif','ru'=>'images/flags/ru.gif'));
/// foreach($links as $link) {
///       echo $link .' ';
/// }
?>


</div>
