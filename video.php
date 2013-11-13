<!doctype html>
<html style="overflow:hidden;">
<head>
  <title>Video</title>
  <style type="text/css">
   body {background:#fff;margin:0;padding:0;}
  </style>
</head>
<body>
<script type="text/javascript" src="./js/modernizr.js"></script>
<script type="text/javascript" src="./js/swfobject.js"></script>
<script src="./js/jquery-1.7.2.min.js"></script>

<div id="video_popup_box" style="height:<?php print $_GET['height'];?>px;overflow:hidden;">
    <video poster="./videos/video<?php print $_GET['id'];?>.jpg" style="display:none;" controls width="640" height="360">
        <source src="./videos/video<?php print $_GET['id'];?>.mp4" type="video/mp4" />
    </video>
    <object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='640' height='360' id='player1' name='player1'>
        <param name='movie' value='videos/cwplayer.swf'>
        <param name='allowfullscreen' value='true'>
        <param name='allowscriptaccess' value='always'>
        <param name='wmode' value='transparent'>
        <param name='flashvars' value='xml_path=videos/video<?php print $_GET['id'];?>.xml'>
        <embed id='player1'
               name='player1'
               src='videos/cwplayer.swf'
               width='640'
               height='360'
               allowscriptaccess='always'
               allowfullscreen='true'
               wmode="transparent"
               flashvars="xml_path=videos/video_xml.php?id=<?php print $_GET['id'];?>"
            />
    </object>

    <script>
        if ( Modernizr.touch ) {
            $('.videoobject').remove();
            $('video').css('display','block');
        }
        else
        {
            $('.videoobject').css('display','block');
            $('video').remove();
        }
    </script>
</div>
</body>
</html>