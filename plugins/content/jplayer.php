<?php
/*
 * JPlayer for Joomla! 1.5
 * Author: Max
 * Version: 1.5
 * Last Update: 31/07/2010
 *
 * JW FLV Player
 * Author: Jeroen Wijering
 * ULR: http://www.longtailvideo.com/players/jw-flv-player/
 * Version: 4.7.811
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgContentJplayer extends JPlugin {

   function plgContentJplayer( &$subject, $params ) {
      parent::__construct( $subject, $params );
   }

   function onPrepareContent( &$article, &$params ) {

      // API
      $mainframe= &JFactory::getApplication();

      // Assign paths
      $sitePath = JPATH_SITE;
      $siteUrl  = substr(JURI::root(), 0, -1);

      // Check if plugin is enabled
      if(JPluginHelper::isEnabled('content','jplayer')==false) return;

      // ------------------------------------ Prepare elements -------------------------------------
      // Includes
      require($sitePath.DS.'plugins'.DS.'content'.DS.'jplayer'.DS.'sources.php');
      // Simple performance check to determine whether plugin should process further
      $grabTags = str_replace("(","",str_replace(")","",implode(array_keys($tagReplace),"|")));
      if(preg_match("#{(".$grabTags.")}#s",$article->text)==false) return;


      // ---------------------------------- Get plugin parameters ----------------------------------
      $plugin = & JPluginHelper::getPlugin('content','jplayer');
      $pluginParams = new JParameter( $plugin->params );

      /* Video */
      $videofolder             = $pluginParams->get('videofolder','media/videos');
      $videowidth              = $pluginParams->get('videowidth',400);
      $videoheight             = $pluginParams->get('videoheight',300);
      $transparency            = $pluginParams->get('transparency','transparent');
      $background              = $pluginParams->get('background','#010101');
      /* Audio */
      $audiofolder             = $pluginParams->get('audiofolder','media/audio');
      $audiowidth              = $pluginParams->get('audiowidth',300);
      $audioheight             = $pluginParams->get('audioheight',20);
      /* Playlist */
      $playlistsize            = $pluginParams->get('playlistsize',350);
      $shuffle                 = $pluginParams->get('shuffle',0);
      /* Youtube */
      $youtuberelated          = $pluginParams->get('youtuberelated',1);
      $youtubeborder           = $pluginParams->get('youtubeborder',0);
      $youtubecolors           = $pluginParams->get('youtubecolors',0);
      $youtuberesolution       = $pluginParams->get('youtuberesolution',2);
      /* General */
      $fullscreen              = ($pluginParams->get('fullscreen',0)) ? 'true' : 'false';
      $autoplay                = ($pluginParams->get('autoplay',0)) ? 'true' : 'false';
      $downloadLink            = $pluginParams->get('downloadLink',1);
      $skin                    = $pluginParams->get('skin','');
      $logo                    = $pluginParams->get('logo','');
      /* Advanced */
      $debugMode               = $pluginParams->get('debugMode',0);
      if($debugMode==0) error_reporting(0); // Turn off all error reporting

      if($shuffle==1) {
         $shuffle="&shuffle=true";
      }
      else {
         $shuffle="";
      }
      if($skin!="") {
         $skin="&skin=".$siteUrl."/plugins/content/jplayer/skins/".$skin;
      }
      if($logo!="") {
         $logo="&logo=".$siteUrl."/".$logo;
      }

      $document = & JFactory::getDocument();
      $document->addStyleSheet('plugins/content/jplayer/style.css');

      // ------------------------------------ Render the output ------------------------------------
      foreach($tagReplace as $plg_tag => $value) {
         // expression to search for
         $regex = "#{".$plg_tag."}(.*?){/".$plg_tag."}#s";
         // process tags
         if(preg_match_all($regex, $article->text, $matches, PREG_PATTERN_ORDER) > 0) {
            // start the replace loop
            foreach ($matches[0] as $key => $match) {
               $tagcontent = preg_replace("/{.+?}/", "", $match);
               $tagparams  = explode('|',$tagcontent);
               $tagsource  = trim(strip_tags($tagparams[0]));

               // source elements
               $findAVparams = array(
                  "{SOURCE}",
                  "{FOLDER}",
                  "{WIDTH}",
                  "{HEIGHT}",
                  "{TRANSPARENCY}",
                  "{BACKGROUND}",
                  "{FULLSCREEN}",
                  "{AUTOPLAY}",
                  "{PLAYLISTSIZE}",
                  "{SHUFFLE}",
                  "{SKIN}",
                  "{LOGO}",
                  "{SITEURL}",
                  "{YOUTUBECODE}",
                  "{YOUTUBEWIDTH}",
                  "{YOUTUBEHEIGHT}",
                  "{YOUTUBEPARAMETERS}",
               );

               // Prepare the HTML
               $output = new JObject;

               // replacement elements
               if($plg_tag=="youtube") {

                  if($youtuberelated) {
                     $youtube_parameters = "";
                  } else {
                     $youtube_parameters = "rel=0&";
                  }

                  switch ($youtubecolors) {
                     case 0:
                        break;
                     case 1:
                        $youtube_parameters .= "color1=0x3a3a3a&color2=0x999999&";
                        break;
                     case 2:
                        $youtube_parameters .= "color1=0x2b405b&color2=0x6b8ab6&";
                        break;
                     case 3:
                        $youtube_parameters .= "color1=0x006699&color2=0x54abd6&";
                        break;
                     case 4:
                        $youtube_parameters .= "color1=0x234900&color2=0x4e9e00&";
                        break;
                     case 5:
                        $youtube_parameters .= "color1=0xe1600f&color2=0xfebd01&";
                        break;
                     case 6:
                        $youtube_parameters .= "color1=0xcc2550&color2=0xe87a9f&";
                        break;
                     case 7:
                        $youtube_parameters .= "color1=0x402061&color2=0x9461ca&";
                        break;
                     case 8:
                        $youtube_parameters .= "color1=0x5d1719&color2=0xcd311b&";
                        break;
                  }

                  switch ($youtuberesolution) {
                     case 1:
                        $youtube_width  = 425;
                        $youtube_height = 344;
                        break;
                     case 2:
                        $youtube_width  = 480;
                        $youtube_height = 385;
                        break;
                     case 3:
                        $youtube_width  = 640;
                        $youtube_height = 505;
                        break;
                     case 4:
                        $youtube_width  = 960;
                        $youtube_height = 745;
                        break;
                  }

                  $youtube_width  = (@$tagparams[1]) ? $tagparams[1] : $youtube_width;
                  $youtube_height = (@$tagparams[2]) ? $tagparams[2] : $youtube_height;

                  if($youtubeborder) {
                     $youtube_width  += 20;
                     $youtube_height += 20;
                     $youtube_parameters .= "border=1";
                  }

               } elseif(in_array($plg_tag, array("mp3","mp3remote","mp3playlist"))) {

                  $final_width    = (@$tagparams[1]) ? $tagparams[1] : $audiowidth;
                  $final_height   = (@$tagparams[2]) ? $tagparams[2] : $audioheight;
                  $final_autoplay = (@$tagparams[3]) ? $tagparams[3] : $autoplay;
                  $final_folder   = $audiofolder;

                  if($plg_tag=="mp3playlist") {
                     $final_height=$final_height+$playlistsize;
                  }
                  
                  $output->playerWidth  = $audiowidth;
                  $output->playerHeight = $audioheight;

               } else {

                  $final_width    = (@$tagparams[1]) ? $tagparams[1] : $videowidth;
                  $final_height   = (@$tagparams[2]) ? $tagparams[2] : $videoheight;
                  $final_autoplay = (@$tagparams[3]) ? $tagparams[3] : $autoplay;
                  $final_folder   = $videofolder;

                  if($plg_tag=="videoplaylist") {
                     $final_height=$final_height+$playlistsize;
                  }
                  
                  $output->playerWidth  = $final_width;
                  $output->playerHeight = $final_height;

               }

               $replaceAVparams = array(
                  $tagsource,
                  $final_folder,
                  $final_width,
                  $final_height,
                  $transparency,
                  $background,
                  $fullscreen,
                  $final_autoplay,
                  $playlistsize,
                  $shuffle,
                  $skin,
                  $logo,
                  $siteUrl,
                  $tagsource,
                  $youtube_width,
                  $youtube_height,
                  $youtube_parameters,
               );

               $output->player = JFilterOutput::ampReplace(str_replace($findAVparams, $replaceAVparams, $tagReplace[$plg_tag]));

               if($downloadLink && (in_array($plg_tag, array("flv","mp3","mp4"))) ) {

                  $filesize = filesize($sitePath.'/'.$final_folder.'/'.$tagsource.'.'.$plg_tag);
                  $units       = array('B', 'kB', 'MB', 'GB', 'TB', 'PB');
                  $i           = 0;
                  while($filesize>=1024) {
                     $filesize = $filesize/1024;
                     $i++;
                  }
                  $filesize = round($filesize, 2)."".$units[$i];
                  $output->downloadLink = '<a href="'.$siteUrl.'/'.$final_folder.'/'.$tagsource.'.'.$plg_tag.'" title="Download">Click</a> to download in '.strtoupper($plg_tag).' format ('.$filesize.')';

               } else {
                  $output->downloadLink = '';
               }

               $getTemplate = '
<!-- JPlayer Plugin (start) -->
<div class="jplayer">
<div class="jplayer-box">'.$output->player.'</div>
<div class="jplayer-text">
'.$output->downloadLink.'
</div>
</div>
<!-- JPlayer Plugin (end) -->
';

               // Do the replace
               $article->text = preg_replace("#{".$plg_tag."}".preg_quote($tagcontent)."{/".$plg_tag."}#s", $getTemplate , $article->text);

            } // end foreach
         } // end if
      }
   }

   function onPrepareContentGaleria( &$article, &$params ) {

      // API
      $mainframe= &JFactory::getApplication();

      // Assign paths
      $sitePath = JPATH_SITE;
      $siteUrl  = substr(JURI::root(), 0, -1);

      // Check if plugin is enabled
      if(JPluginHelper::isEnabled('content','jplayer')==false) return;

      // ------------------------------------ Prepare elements -------------------------------------
      // Includes
      require($sitePath.DS.'plugins'.DS.'content'.DS.'jplayer'.DS.'sources.php');
      // Simple performance check to determine whether plugin should process further
      $grabTags = str_replace("(","",str_replace(")","",implode(array_keys($tagReplace),"|")));
      if(preg_match("#{(".$grabTags.")}#s",$article->text)==false) return;


      // ---------------------------------- Get plugin parameters ----------------------------------
      $plugin = & JPluginHelper::getPlugin('content','jplayer');
      $pluginParams = new JParameter( $plugin->params );

      /* Video */
      $videofolder             = $pluginParams->get('videofolder','media/videos');
      $videowidth              = 128;
      $videoheight             = 76;
      $transparency            = $pluginParams->get('transparency','transparent');
      $background              = $pluginParams->get('background','#010101');
      /* Audio */
      $audiofolder             = $pluginParams->get('audiofolder','media/audio');
      $audiowidth              = $pluginParams->get('audiowidth',300);
      $audioheight             = $pluginParams->get('audioheight',20);
      /* Playlist */
      $playlistsize            = $pluginParams->get('playlistsize',350);
      $shuffle                 = $pluginParams->get('shuffle',0);
      /* Youtube */
      $youtuberelated          = $pluginParams->get('youtuberelated',1);
      $youtubeborder           = $pluginParams->get('youtubeborder',0);
      $youtubecolors           = $pluginParams->get('youtubecolors',0);
      $youtuberesolution       = $pluginParams->get('youtuberesolution',2);
      /* General */
      $fullscreen              = ($pluginParams->get('fullscreen',0)) ? 'true' : 'false';
      $autoplay                = ($pluginParams->get('autoplay',0)) ? 'true' : 'false';
      $downloadLink            = $pluginParams->get('downloadLink',1);
      $skin                    = $pluginParams->get('skin','');
      $logo                    = $pluginParams->get('logo','');
      /* Advanced */
      $debugMode               = $pluginParams->get('debugMode',0);
      if($debugMode==0) error_reporting(0); // Turn off all error reporting

      if($shuffle==1) {
         $shuffle="&shuffle=true";
      }
      else {
         $shuffle="";
      }
      if($skin!="") {
         $skin="&skin=".$siteUrl."/plugins/content/jplayer/skins/".$skin;
      }
      if($logo!="") {
         $logo="&logo=".$siteUrl."/".$logo;
      }

      $document = & JFactory::getDocument();
      $document->addStyleSheet('plugins/content/jplayer/style.css');

      // ------------------------------------ Render the output ------------------------------------
      foreach($tagReplace as $plg_tag => $value) {
         // expression to search for
         $regex = "#{".$plg_tag."}(.*?){/".$plg_tag."}#s";
         // process tags
         if(preg_match_all($regex, $article->text, $matches, PREG_PATTERN_ORDER) > 0) {
            // start the replace loop
            foreach ($matches[0] as $key => $match) {
               $tagcontent = preg_replace("/{.+?}/", "", $match);
               $tagparams  = explode('|',$tagcontent);
               $tagsource  = trim(strip_tags($tagparams[0]));

               // source elements
               $findAVparams = array(
                  "{SOURCE}",
                  "{FOLDER}",
                  "{WIDTH}",
                  "{HEIGHT}",
                  "{TRANSPARENCY}",
                  "{BACKGROUND}",
                  "{FULLSCREEN}",
                  "{AUTOPLAY}",
                  "{PLAYLISTSIZE}",
                  "{SHUFFLE}",
                  "{SKIN}",
                  "{LOGO}",
                  "{SITEURL}",
                  "{YOUTUBECODE}",
                  "{YOUTUBEWIDTH}",
                  "{YOUTUBEHEIGHT}",
                  "{YOUTUBEPARAMETERS}",
               );

               // Prepare the HTML
               $output = new JObject;

               // replacement elements
               if($plg_tag=="youtube") {

                  if($youtuberelated) {
                     $youtube_parameters = "";
                  } else {
                     $youtube_parameters = "rel=0&";
                  }

                  switch ($youtubecolors) {
                     case 0:
                        break;
                     case 1:
                        $youtube_parameters .= "color1=0x3a3a3a&color2=0x999999&";
                        break;
                     case 2:
                        $youtube_parameters .= "color1=0x2b405b&color2=0x6b8ab6&";
                        break;
                     case 3:
                        $youtube_parameters .= "color1=0x006699&color2=0x54abd6&";
                        break;
                     case 4:
                        $youtube_parameters .= "color1=0x234900&color2=0x4e9e00&";
                        break;
                     case 5:
                        $youtube_parameters .= "color1=0xe1600f&color2=0xfebd01&";
                        break;
                     case 6:
                        $youtube_parameters .= "color1=0xcc2550&color2=0xe87a9f&";
                        break;
                     case 7:
                        $youtube_parameters .= "color1=0x402061&color2=0x9461ca&";
                        break;
                     case 8:
                        $youtube_parameters .= "color1=0x5d1719&color2=0xcd311b&";
                        break;
                  }

                  switch ($youtuberesolution) {
                     case 1:
                        $youtube_width  = 425;
                        $youtube_height = 344;
                        break;
                     case 2:
                        $youtube_width  = 480;
                        $youtube_height = 385;
                        break;
                     case 3:
                        $youtube_width  = 640;
                        $youtube_height = 505;
                        break;
                     case 4:
                        $youtube_width  = 960;
                        $youtube_height = 745;
                        break;
                  }

                  $youtube_width  = (@$tagparams[1]) ? $tagparams[1] : $youtube_width;
                  $youtube_height = (@$tagparams[2]) ? $tagparams[2] : $youtube_height;

                  if($youtubeborder) {
                     $youtube_width  += 20;
                     $youtube_height += 20;
                     $youtube_parameters .= "border=1";
                  }

               } elseif(in_array($plg_tag, array("mp3","mp3remote","mp3playlist"))) {

                  $final_width    = (@$tagparams[1]) ? $tagparams[1] : $audiowidth;
                  $final_height   = (@$tagparams[2]) ? $tagparams[2] : $audioheight;
                  $final_autoplay = (@$tagparams[3]) ? $tagparams[3] : $autoplay;
                  $final_folder   = $audiofolder;

                  if($plg_tag=="mp3playlist") {
                     $final_height=$final_height+$playlistsize;
                  }
                  
                  $output->playerWidth  = $audiowidth;
                  $output->playerHeight = $audioheight;

               } else {

                  $final_width    = (@$tagparams[1]) ? $tagparams[1] : $videowidth;
                  $final_height   = (@$tagparams[2]) ? $tagparams[2] : $videoheight;
                  $final_autoplay = (@$tagparams[3]) ? $tagparams[3] : $autoplay;
                  $final_folder   = $videofolder;

                  if($plg_tag=="videoplaylist") {
                     $final_height=$final_height+$playlistsize;
                  }
                  
                  $output->playerWidth  = $final_width;
                  $output->playerHeight = $final_height;

               }

               $replaceAVparams = array(
                  $tagsource,
                  $final_folder,
                  $final_width,
                  $final_height,
                  $transparency,
                  $background,
                  $fullscreen,
                  $final_autoplay,
                  $playlistsize,
                  $shuffle,
                  $skin,
                  $logo,
                  $siteUrl,
                  $tagsource,
                  $youtube_width,
                  $youtube_height,
                  $youtube_parameters,
               );

               $output->player = JFilterOutput::ampReplace(str_replace($findAVparams, $replaceAVparams, $tagReplace[$plg_tag]));

               if($downloadLink && (in_array($plg_tag, array("flv","mp3","mp4"))) ) {

                  $filesize = filesize($sitePath.'/'.$final_folder.'/'.$tagsource.'.'.$plg_tag);
                  $units       = array('B', 'kB', 'MB', 'GB', 'TB', 'PB');
                  $i           = 0;
                  while($filesize>=1024) {
                     $filesize = $filesize/1024;
                     $i++;
                  }
                  $filesize = round($filesize, 2)."".$units[$i];
                  $output->downloadLink = '<a href="'.$siteUrl.'/'.$final_folder.'/'.$tagsource.'.'.$plg_tag.'" title="Download">Click</a> to download in '.strtoupper($plg_tag).' format ('.$filesize.')';

               } else {
                  $output->downloadLink = '';
               }

               $getTemplate = '
<!-- JPlayer Plugin (start) -->
<div class="jplayer">
<div class="jplayer-box">'.$output->player.'</div>
<div class="jplayer-text">
'.$output->downloadLink.'
</div>
</div>
<!-- JPlayer Plugin (end) -->
';

               // Do the replace
               $article->text = preg_replace("#{".$plg_tag."}".preg_quote($tagcontent)."{/".$plg_tag."}#s", $getTemplate , $article->text);

            } // end foreach
         } // end if
      }
   }

}
?>