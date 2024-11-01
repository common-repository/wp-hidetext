<?php
/*
Plugin Name: WPVN - HideText
Plugin URI: http://link2caro.net/read/wpvn-hidetext/
Description: This plugin is used to hide a desired text [hide[=Text to be displayed instead of default text]]TEXT[/hide]
Version: 0.5
Author: Minh Quan TRAN (aka link2caro - A member of WordPressVN)
Author URI: http://link2caro.net/donate/
*/

function hide_content_script() {
echo
<<<EOF
<script type="text/javascript">
<!--
function toggle_visibility(id) {
var e = document.getElementById(id);
if(e.style.display == 'none')
e.style.display = 'block';
else
e.style.display = 'none';
}
//-->
</script>
EOF;
}

$id_hide=1;

function hide_content($text) {
        global $id_hide;

	$html='';
	foreach (preg_split('|(\[HIDE.+)|i', $text, -1, PREG_SPLIT_DELIM_CAPTURE) as $token) {
                 if (preg_match('|(\[HIDE\])|i', $token)) {
                        $str = "<a onclick=\"toggle_visibility('hide-content-{$id_hide}');\" title=\"View entire text\">View entire text</a><div id=\"hide-content-{$id_hide}\" style=\"display:none\">";
                        $html .= preg_replace('|(\[HIDE\])|i',$str,$token);
                        $str = "</div>";
                        $html .= preg_replace('|(\[/HIDE\])|i',$str,$token);
                        $id_hide++;
                 } elseif (preg_match('|(\[HIDE=.+\])|i', $token)) {
                        $txt = split('\]',$token);

                        $text = split('=',$txt[0]);
                        $text = $text[1];

                        $content = split('\[',$txt[1]);
                        $content = $content[0];

                        $str = "<a onclick=\"toggle_visibility('hide-content-{$id_hide}');\" title=\"{$text}\">{$text}</a><div id=\"hide-content-{$id_hide}\" style=\"display:none\">";
                        $html .= $str.$content."</div>";

/*$cont = preg_replace('|(\[HIDE=.+\])|i',$str,$token);
$str="</div>";
$cont = preg_replace('|(\[/HIDE\])|i',$str,$cont);
                        $html .= $cont;*/

                        $id_hide++;
                 } else {
		        $html .= $token;
		 }
	}

        return $html;
}

add_action('wp_head', 'hide_content_script');
add_filter('the_content', 'hide_content');
?>