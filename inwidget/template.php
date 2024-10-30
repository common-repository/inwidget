<?php 
/**
 * Project:     inWidget: show pictures from instagram.com on your site!
 * File:        template.php
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of MIT license
 * http://inwidget.ru/MIT-license.txt
 * 
 * @link http://inwidget.ru
 * @copyright 2014-2017 Alexandr Kazarmshchikov
 * @author Alexandr Kazarmshchikov
 * @version 1.1.0
 * @package inWidget
 *
 */

if(!$inWidget) die('inWidget object was not initialised.');
if(!is_object($inWidget->data)) die('<b style="color:red;">Cache file contains plain text:</b><br />'.$inWidget->data);

?>
<!DOCTYPE html> 
<html lang="ru">
	<head>
		<title>inWidget - free Instagram widget for your site!</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="content-language" content="<?php echo $inWidget->langName; ?>" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<link rel="stylesheet" type="text/css" href="css/default.css?r1" media="all" />
		<style type='text/css'>
			.widget {
				width:<?php echo $inWidget->width; ?>px;
			}
			.widget .data a.image:link, .widget .data a.image:visited{
				width:<?php echo $inWidget->imgWidth; ?>px;
				height:<?php echo $inWidget->imgWidth; ?>px;
			}
			.widget .data .image span {
				width:<?php echo $inWidget->imgWidth; ?>px;
				height:<?php echo $inWidget->imgWidth; ?>px;
			}
			.copyright {
				width:<?php echo $inWidget->width; ?>px;
			}
		</style>
	</head>
<body>
<div id="widget" class="widget">
	<a rel="nofollow" href="http://instagram.com/<?php echo $inWidget->data->username; ?>" target="_blank" class="title">
		<img 
			src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAMAAADzN3VRAAAAUVBMVEVHcEwhISEiIiIiIiIhISEwMDAhISEjIyMhISEkJCQhISEhISEiIiIiIiIiIiIjIyMhISEiIiIiIiIiIiIiIiIiIiIiIiImJiYiIiIoKCghISGZlJIhAAAAGnRSTlMA5dNwQgqsP/gf/dCJkd9mSPNU7WOlYj2WFwAUIYYAAADRSURBVCjPfZJJksJADASzd7X3FZj6/0PnAEGEMUbXjG5JqQJIW/arNZKkxlaftwQAxemzXAEoJtvDMsVYSolxWsJusgLJyVeOVb1cYpN9gPD4q6aNrP0I7tKDXRmv8Pqib13bV6opEORZtQAwzpKkeaTeYdGKaQIYpaG7dYM0AkwyGkWgzsoJSFlzBaIapAj0GhIAaVAPRAmpAK06AKBTC5Q3cbq9yE3uQL6/uepzPdv1PmcH8HRw8gZAkD+7BmBX/nIfoJq2Hze9zsGP7Fzl7R+hrxbe6kzjrwAAAABJRU5ErkJggg==" 
			class="logo"
        />
		<?php echo $inWidget->lang['title']; ?>
	</a>
	<?php
		if($inWidget->toolbar == true) { 
			echo '
			<table class="profile">
				<tr>
					<td rowspan="2" class="avatar">
						<a href="http://instagram.com/'.$inWidget->data->username.'" target="_blank"><img src="'.$inWidget->data->avatar.'"></a>
					</td>
					<td class="value">
						'.$inWidget->data->posts.'
						<span>'.$inWidget->lang['statPosts'].'</span>
					</td>
					<td class="value">
						'.$inWidget->data->followers.'
						<span>'.$inWidget->lang['statFollowers'].'</span>
					</td>
					<td class="value" style="border-right:none !important;">
						'.$inWidget->data->following.'
						<span>'.$inWidget->lang['statFollowing'].'</span>
					</td>
				</tr>
				<tr>
					<td colspan="3" style="border-right:none !important;">
						<a href="http://instagram.com/'.$inWidget->data->username.'" class="follow" target="_blank">'.$inWidget->lang['buttonFollow'].' &#9658;</a>
					</td>
				</tr>
			</table>';
		}
		$i = 0;
		$count = $inWidget->countAvailableImages($inWidget->data->images);
		if($count>0) {
			if($inWidget->config['imgRandom'] === true) shuffle($inWidget->data->images);
			//$inWidget->data->images = array_slice($inWidget->data->images,0,$inWidget->view);
			echo '<div id="widgetData" class="data">';
				foreach ($inWidget->data->images as $key=>$item){
					if($inWidget->isBannedUserId($item->authorId) == true) continue;
					switch ($inWidget->preview){
						case 'large':
							$thumbnail = $item->large;
							break;
						case 'fullsize':
							$thumbnail = $item->fullsize;
							break;
						default:
							$thumbnail = $item->small;
					}
					echo '<a href="'.$item->link.'" class="image" target="_blank"><span style="background-image:url('.$thumbnail.');">&nbsp;</span></a>';
					$i++;
					if($i >= $inWidget->view) break;
				}
				echo '<div class="clear">&nbsp;</div>';
			echo '</div>';
		}
		else {
			if(!empty($inWidget->config['HASHTAG'])) {
				$inWidget->lang['imgEmptyByHash'] = str_replace(
					'{$hashtag}', 
					$inWidget->config['HASHTAG'], 
					$inWidget->lang['imgEmptyByHash']
				);
				echo '<div class="empty">'.$inWidget->lang['imgEmptyByHash'].'</div>';
			}
			else echo '<div class="empty">'.$inWidget->lang['imgEmpty'].'</div>';
		}
	?>
</div>
</body>
</html>
<!-- 
	inWidget - free Instagram widget for your site!
	http://inwidget.ru
	© Alexandr Kazarmshchikov
-->