<?php
/*
// Pixel Point Creative "Simple Spotlight" Module for Joomla!
// License: http://www.gnu.org/copyleft/gpl.html
// Copyright (c) 2010 Pixel Point Creative LLC.
// More info at http://www.pixelpointcreative.com
// Developer: Daniel Riefstahl
// ***Last update: September 8th, 2011***
*/
defined('_JEXEC') or die('Restricted access');

//Parameters for Simple Spotlight
$spotlightImage = $params->get('spotlightImage');
$spotlightImage2 = $params->get('spotlightImage2');
$spotlightImage3 = $params->get('spotlightImage3');
$spotlightImage4 = $params->get('spotlightImage4');
$spotlightImage5 = $params->get('spotlightImage5');
$spotlightImage6 = $params->get('spotlightImage6');
$spotlightImage7 = $params->get('spotlightImage7');
$spotlightImage8 = $params->get('spotlightImage8');
$spotlightImage9 = $params->get('spotlightImage9');
$spotlightImage10 = $params->get('spotlightImage10');
$spotlightImage11 = $params->get('spotlightImage11');
$spotlightImage12 = $params->get('spotlightImage12');
$spotlightImage13 = $params->get('spotlightImage13');
$spotlightImage14 = $params->get('spotlightImage14');
$spotlightImage15 = $params->get('spotlightImage15');
$spotlightImage16 = $params->get('spotlightImage16');
$spotlightImage17 = $params->get('spotlightImage17');
$spotlightImage18 = $params->get('spotlightImage18');
$spotlightImage19 = $params->get('spotlightImage19');
$spotlightImage20 = $params->get('spotlightImage20');
$alt = $params->get('alt');
$alt2 = $params->get('alt2');
$alt3 = $params->get('alt3');
$alt4 = $params->get('alt4');
$alt5 = $params->get('alt5');
$alt6 = $params->get('alt6');
$alt7 = $params->get('alt7');
$alt8 = $params->get('alt8');
$alt9 = $params->get('alt9');
$alt10 = $params->get('alt10');
$alt11 = $params->get('alt11');
$alt12 = $params->get('alt12');
$alt13 = $params->get('alt13');
$alt14 = $params->get('alt14');
$alt15 = $params->get('alt15');
$alt16 = $params->get('alt16');
$alt17 = $params->get('alt17');
$alt18 = $params->get('alt18');
$alt19 = $params->get('alt19');
$alt20 = $params->get('alt20');
$window = $params->get('window');
$window2 = $params->get('window2');
$window3 = $params->get('window3');
$window4 = $params->get('window4');
$window5 = $params->get('window5');
$window6 = $params->get('window6');
$window7 = $params->get('window7');
$window8 = $params->get('window8');
$window9 = $params->get('window9');
$window10 = $params->get('window10');
$window11 = $params->get('window11');
$window12 = $params->get('window12');
$window13 = $params->get('window13');
$window14 = $params->get('window14');
$window15 = $params->get('window15');
$window16 = $params->get('window16');
$window17 = $params->get('window17');
$window18 = $params->get('window18');
$window19 = $params->get('window19');
$window20 = $params->get('window20');
$link = $params->get('link');
$link2 = $params->get('link2');
$link3 = $params->get('link3');
$link4 = $params->get('link4');
$link5 = $params->get('link5');
$link6 = $params->get('link6');
$link7 = $params->get('link7');
$link8 = $params->get('link8');
$link9 = $params->get('link9');
$link10 = $params->get('link10');
$link11 = $params->get('link11');
$link12 = $params->get('link12');
$link13 = $params->get('link13');
$link14 = $params->get('link14');
$link15 = $params->get('link15');
$link16 = $params->get('link16');
$link17 = $params->get('link17');
$link18 = $params->get('link18');
$link19 = $params->get('link19');
$link20 = $params->get('link20');
$width = $params->get('width');
$height = $params->get('height');
$auto = $params->get('auto');
$easing = $params->get('easing');
$sides = $params->get("sides", 8);
$speed = $params->get("speed", '5');
$effect = $params->get('effect');
$random = $params->get('random','0');
$buttons = $params->get('buttons','right');
$jquery	= $params->get('jquery', '1');
$play 							= $params->get("play", 'true');
$effect 						= $params->get("effect", 'fade');
$slideshow_speed 				= $params->get("slideshow_speed", 800);
$timer_speed 					= $params->get("timer_speed", 4000);
$auto_play						= $params->get('play', 1);

if ($jquery) 
if( !defined('PPC_JQUERY_INC') ){
	JHTML::script('ppc.safejquery.start.js','modules/'.$module->module.'/js/');
	JHTML::script('jquery-1.5.min.js','modules/'.$module->module.'/js/');
	JHTML::script('ppc.safejquery.end.js','modules/'.$module->module.'/js/');
	define('PPC_JQUERY_INC', 1);
}
JHTML::script('ppc.safejqueryplugin.start.js','modules/'.$module->module.'/js/');
JHTML::script('jquery.cycle.all.2.74.js','modules/'.$module->module.'/js/');
JHTML::script('ppc.safejqueryplugin.end.js','modules/'.$module->module.'/js/');
/* Add css*/	
JHTML::stylesheet('style.css',JURI::base() . 'modules/'.$module->module.'/css/');


?>
<script type="text/javascript">
$jppc(document).ready(function($) {
    $('#sp<?php echo $module->id?> ul').cycle({
		fx:     '<?php echo $effect;?>',
		timeout: <?php echo $auto_play ? $timer_speed : 0;?>,
    	speed:  <?php echo $slideshow_speed;?>,
		next:   '#next<?php echo $module->id?>', 
    	prev:   '#prev<?php echo $module->id?>',
	    pause: <?php echo ($play) ? 1 : 0;?>		
	});		
});

</script>
<div style="clear:both;background:none;width:100%;float:left; " >  
<div class="slideshow" id="sp<?php echo $module->id?>" style="width:<?php echo ($width) ?>px; height:<?php echo $height; ?>px;">
<ul style="background:none;margin:0px;padding:0px">
	<?php 

        $arr_img = array();

if ($params->get('show1')){
	if ($spotlightImage) $getlink = '';
                   if ($params->get('nolink')) {$getlink = '<a href="'.$link.'" target="'.$window.'"><img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt.'" title="'.$alt.'" /></a>';
                    } else {
                   $getlink = '<img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt.'" title="'.$alt.'" />';
                    }
                    $arr_img[]='<li style="background:none;margin:0px;padding:0px">'. $getlink .'</li>';
}
if ($params->get('show2')){
        if ($spotlightImage2) $getlink2 = '';
                   if ($params->get('nolink2')) {$getlink2 = '<a href="'.$link2.'" target="'.$window2.'"><img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage2.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt2.'" title="'.$alt2.'" /></a>';
                    } else {
                   $getlink2 = '<img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage2.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt2.'" title="'.$alt2.'" />';
                    }
                    $arr_img[]='<li style="background:none;margin:0px;padding:0px">'. $getlink2 .'</li>';
}
if ($params->get('show3')){
	        if ($spotlightImage3) $getlink3 = '';
                   if ($params->get('nolink3')) {$getlink3 = '<a href="'.$link3.'" target="'.$window3.'"><img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage3.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt3.'" title="'.$alt3.'" /></a>';
                    } else {
                   $getlink3 = '<img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage3.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt3.'" title="'.$alt3.'" />';
                    }
                    $arr_img[]='<li style="background:none;margin:0px;padding:0px">'. $getlink3 .'</li>';
}	
if ($params->get('show4')){				
        if ($spotlightImage4) $getlink4 = '';
                   if ($params->get('nolink4')) {$getlink4 = '<a href="'.$link4.'" target="'.$window4.'"><img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage4.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt4.'" title="'.$alt4.'" /></a>';
                    } else {
                   $getlink4 = '<img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage4.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt4.'" title="'.$alt4.'" />';
                    }
                    $arr_img[]='<li style="background:none;margin:0px;padding:0px">'. $getlink4 .'</li>';
}	
if ($params->get('show5')){	
        if ($spotlightImage5) $getlink5 = '';
                   if ($params->get('nolink5')) {$getlink5 = '<a href="'.$link5.'" target="'.$window5.'"><img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage5.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt5.'" title="'.$alt5.'" /></a>';
                    } else {
                   $getlink5 = '<img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage5.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt5.'" title="'.$alt5.'" />';
                    }
                    $arr_img[]='<li style="background:none;margin:0px;padding:0px">'. $getlink5 .'</li>';
}	
if ($params->get('show6')){		
        if ($spotlightImage6) $getlink6 = '';
                   if ($params->get('nolink6')) {$getlink6 = '<a href="'.$link6.'" target="'.$window6.'"><img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage6.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt6.'" title="'.$alt6.'" /></a>';
                    } else {
                   $getlink6 = '<img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage6.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt6.'" title="'.$alt6.'" />';
                    }
                    $arr_img[]='<li style="background:none;margin:0px;padding:0px">'. $getlink6 .'</li>';
}	
if ($params->get('show7')){		
        if ($spotlightImage7) $getlink7 = '';
                   if ($params->get('nolink7')) {$getlink7 = '<a href="'.$link7.'" target="'.$window7.'"><img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage7.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt7.'" title="'.$alt7.'" /></a>';
                    } else {
                   $getlink7 = '<img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage7.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt7.'" title="'.$alt7.'" />';
                    }
                    $arr_img[]='<li style="background:none;margin:0px;padding:0px">'. $getlink7 .'</li>';
}	
if ($params->get('show8')){		
        if ($spotlightImage8) $getlink8 = '';
                   if ($params->get('nolink8')) {$getlink8 = '<a href="'.$link8.'" target="'.$window8.'"><img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage8.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt8.'" title="'.$alt8.'" /></a>';
                    } else {
                   $getlink8 = '<img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage8.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt8.'" title="'.$alt8.'" />';
                    }
                    $arr_img[]='<li style="background:none;margin:0px;padding:0px">'. $getlink8 .'</li>';
}	
if ($params->get('show9')){			
        if ($spotlightImage9) $getlink9 = '';
                   if ($params->get('nolink9')) {$getlink9 = '<a href="'.$link9.'" target="'.$window9.'"><img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage9.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt9.'" title="'.$alt9.'" /></a>';
                    } else {
                   $getlink9 = '<img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage9.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt9.'" title="'.$alt9.'" />';
                    }
                    $arr_img[]='<li style="background:none;margin:0px;padding:0px">'. $getlink9 .'</li>';
}	
if ($params->get('show10')){		
        if ($spotlightImage10) $getlink10 = '';
                   if ($params->get('nolink10')) {$getlink10 = '<a href="'.$link10.'" target="'.$window10.'"><img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage10.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt10.'" title="'.$alt10.'" /></a>';
                    } else {
                   $getlink10 = '<img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage10.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt10.'" title="'.$alt10.'" />';
                    }
                    $arr_img[]='<li style="background:none;margin:0px;padding:0px">'. $getlink10 .'</li>';
}
if ($params->get('show11')){		
        if ($spotlightImage11) $getlink11 = '';
                   if ($params->get('nolink11')) {$getlink11 = '<a href="'.$link11.'" target="'.$window11.'"><img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage11.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt11.'" title="'.$alt11.'" /></a>';
                    } else {
                   $getlink11 = '<img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage11.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt11.'" title="'.$alt11.'" />';
                    }
                    $arr_img[]='<li style="background:none;margin:0px;padding:0px">'. $getlink11 .'</li>';
}
if ($params->get('show12')){		
        if ($spotlightImage12) $getlink12 = '';
                   if ($params->get('nolink12')) {$getlink12 = '<a href="'.$link12.'" target="'.$window12.'"><img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage12.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt12.'" title="'.$alt12.'" /></a>';
                    } else {
                   $getlink12 = '<img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage12.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt12.'" title="'.$alt12.'" />';
                    }
                    $arr_img[]='<li style="background:none;margin:0px;padding:0px">'. $getlink12 .'</li>';
}	
if ($params->get('show13')){		
        if ($spotlightImage13) $getlink13 = '';
                   if ($params->get('nolink13')) {$getlink13 = '<a href="'.$link13.'" target="'.$window13.'"><img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage13.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt13.'" title="'.$alt13.'" /></a>';
                    } else {
                   $getlink13 = '<img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage13.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt13.'" title="'.$alt13.'" />';
                    }
                    $arr_img[]='<li style="background:none;margin:0px;padding:0px">'. $getlink13 .'</li>';
}	
if ($params->get('show14')){		
        if ($spotlightImage14) $getlink14 = '';
                   if ($params->get('nolink14')) {$getlink14 = '<a href="'.$link14.'" target="'.$window14.'"><img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage14.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt14.'" title="'.$alt14.'" /></a>';
                    } else {
                   $getlink14 = '<img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage14.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt14.'" title="'.$alt14.'" />';
                    }
                    $arr_img[]='<li style="background:none;margin:0px;padding:0px">'. $getlink14 .'</li>';
}
if ($params->get('show15')){		
        if ($spotlightImage15) $getlink15 = '';
                   if ($params->get('nolink15')) {$getlink15 = '<a href="'.$link15.'" target="'.$window15.'"><img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage15.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt15.'" title="'.$alt15.'" /></a>';
                    } else {
                   $getlink15 = '<img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage15.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt15.'" title="'.$alt15.'" />';
                    }
                    $arr_img[]='<li style="background:none;margin:0px;padding:0px">'. $getlink15 .'</li>';
}	
if ($params->get('show16')){		
        if ($spotlightImage16) $getlink16 = '';
                   if ($params->get('nolink16')) {$getlink16 = '<a href="'.$link16.'" target="'.$window16.'"><img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage16.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt16.'" title="'.$alt16.'" /></a>';
                    } else {
                   $getlink16 = '<img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage16.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt16.'" title="'.$alt16.'" />';
                    }
                    $arr_img[]='<li style="background:none;margin:0px;padding:0px">'. $getlink16 .'</li>';
}
if ($params->get('show17')){		
        if ($spotlightImage17) $getlink17 = '';
                   if ($params->get('nolink17')) {$getlink17 = '<a href="'.$link17.'" target="'.$window17.'"><img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage17.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt17.'" title="'.$alt17.'" /></a>';
                    } else {
                   $getlink17 = '<img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage17.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt17.'" title="'.$alt17.'" />';
                    }
                    $arr_img[]='<li style="background:none;margin:0px;padding:0px">'. $getlink17 .'</li>';
}	
if ($params->get('show18')){		
        if ($spotlightImage18) $getlink18 = '';
                   if ($params->get('nolink18')) {$getlink18 = '<a href="'.$link18.'" target="'.$window18.'"><img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage18.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt18.'" title="'.$alt18.'" /></a>';
                    } else {
                   $getlink18 = '<img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage18.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt18.'" title="'.$alt18.'" />';
                    }
                    $arr_img[]='<li style="background:none;margin:0px;padding:0px">'. $getlink18 .'</li>';
}
if ($params->get('show19')){		
        if ($spotlightImage19) $getlink19 = '';
                   if ($params->get('nolink19')) {$getlink19 = '<a href="'.$link19.'" target="'.$window19.'"><img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage19.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt19.'" title="'.$alt19.'" /></a>';
                    } else {
                   $getlink19 = '<img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage19.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt19.'" title="'.$alt19.'" />';
                    }
                    $arr_img[]='<li style="background:none;margin:0px;padding:0px">'. $getlink19 .'</li>';
}
if ($params->get('show20')){		
        if ($spotlightImage20) $getlink20 = '';
                   if ($params->get('nolink20')) {$getlink20 = '<a href="'.$link20.'" target="'.$window20.'"><img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage20.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt20.'" title="'.$alt20.'" /></a>';
                    } else {
                   $getlink20 = '<img src="'.JURI::base().'modules/mod_ppc_simple_spotlight/img/'.$spotlightImage20.'" width="'.$width.'px" height="'.$height.'px" border="0" alt="'.$alt20.'" title="'.$alt20.'" />';
                    }
                    $arr_img[]='<li style="background:none;margin:0px;padding:0px">'. $getlink20 .'</li>';
}	
		
       if($random == 1){

            shuffle($arr_img);

        }

        foreach($arr_img as $item) {
		

            echo $item;

		}


    ?>
	</ul>
	<?php if ($params->get('nav')) : ?>
    
	
	<div class="ssbutton" style="width:<?php echo ($width)-15 ?>px;text-align:<?php echo ($buttons) ?>; margin: -40px auto; padding: 0 25px 0 10px;  position: relative;  z-index: 200;">
	<a href="#" class="ssprev"><span id="prev<?php echo $module->id?>"><img src="modules/mod_ppc_simple_spotlight/img/button/button-prev.png" title="Previous" alt="Previous" /></span></a> 
	<a href="#" class="ssnext"><span id="next<?php echo $module->id?>"><img src="modules/mod_ppc_simple_spotlight/img/button/button-next.png" title="Next" alt="Next" /></span></a>
    </div>
    <?php endif; ?>
</div>
</div>
