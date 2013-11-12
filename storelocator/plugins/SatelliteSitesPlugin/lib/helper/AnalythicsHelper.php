<?php

/**
 * AnalythicsHelper
 *
 * @author  ftassi
 * @package SatelliteSitesPlugin
 */

function get_analythics_code() {
	if (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && $_SERVER['HTTP_X_FORWARDED_HOST'] == 'www.diesel.com')
	{
		$html =<<<EOF
		<!-- google analytics tracking -->
		<script type="text/javascript">
		  var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		  document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
		  var pageTracker = _gat._createTracker("UA-2545529-1");
		  pageTracker._initData();
		  pageTracker._trackPageview();
		</script>
EOF;
    return $html;
	}
	return '';
}

function get_analythics_var() {
  if (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && $_SERVER['HTTP_X_FORWARDED_HOST'] == 'www.diesel.com')
  {
    $html =<<<EOF
    <!-- google analytics tracking -->
    <script type="text/javascript">
      var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
      document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
    </script>
    <script type="text/javascript">
      var pageTracker = _gat._createTracker("UA-2545529-1");
      pageTracker._initData();
    </script>
EOF;
    return $html;
  }
  return '';
}