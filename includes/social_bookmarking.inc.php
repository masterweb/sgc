<?php
// +-------------------------------------------------------------+
// | SocialBookmarking                                           |
// | Displays links for various social bookmarking services      |
// +-------------------------------------------------------------+
// | Copyright (c) 2005 Jaimie Sirovich                          |
// +-------------------------------------------------------------+
// | Author: Jaimie Sirovich <jsirovic@gmail.com>                |
// | Icons taken from WordPress Plugin Sociable by Peter Harkins |
// | (http://push.cx)                                            |
// +-------------------------------------------------------------+

class SocialBookmarking
{ 
  var $_link;
  var $_title;
  var $_site_name;

  //Esta variable contiene los links e iconos para las redes sociales
  /*var $_templates = array(
    'blinkbits' => array( 
      'icon' => 'blinkbits.png', 
      'url' => 'http://www.blinkbits.com/bookmarklets/save.php?v=1&source_url=@@ta {LINK}&title={TITLE}&body={TITLE}'), 

    'BlinkList' => array( 
      'icon' => 'blinklist.png', 
      'url' => 'http://www.blinklist.com/index.php?Action=Blink/addblink.php&@@ta
Description=&Url={LINK}&Title={TITLE}'), 
   
    'blogmarks' => array( 
      'icon' => 'blogmarks.png', 
      'url' => 'http://blogmarks.net/my/new.php?mini=1&simple=1&url={LINK}&@@ta 
title={TITLE}'), 
   
    'co.mments' => array( 
      'icon' => 'co.mments.gif', 
      'url' => 'http://co.mments.com/track?url={LINK}&title={TITLE}'), 
   
    'connotea' => array( 
      'icon' => 'connotea.png', 
      'url' => 'http://www.connotea.org/addpopup?continue=confirm&uri={LINK}&@@ta 
title={TITLE}'), 
   
    'del.icio.us' => array( 
      'icon' => 'delicious.png', 
      'url' => 'http://del.icio.us/post?url={LINK}&title={TITLE}'), 
   
    'De.lirio.us' => array( 
      'icon' => 'delirious.png', 
      'url' => 'http://de.lirio.us/rubric/post?uri={LINK}&title={TITLE};@@ta 
when_done=go_back'), 
   
    'digg' => array( 
      'icon' => 'digg.png', 
      'url' => 'http://digg.com/submit?phase=2&url={LINK}&title={TITLE}'), 
   
    'Fark' => array( 
      'icon' => 'fark.png', 
      'url' => 'http://cgi.fark.com/cgi/fark/edit.pl?new_url={LINK}&new_comment={TITLE}&new_comment={SITENAME}&linktype=Misc'), 
   
    'feedmelinks' => array( 
      'icon' => 'feedmelinks.png', 
      'url' => 'http://feedmelinks.com/categorize?from=toolbar&op=submit&@@ta 
url={LINK}&name={TITLE}'), 
   
    'Furl' => array( 
      'icon' => 'furl.png', 
      'url' => 'http://www.furl.net/storeIt.jsp?u={LINK}&t={TITLE}'), 
   
    'LinkaGoGo' => array( 
      'icon' => 'linkagogo.png', 
      'url' => 'http://www.linkagogo.com/go/AddNoPopup?url={LINK}&title={TITLE}'), 
   
    'Ma.gnolia' => array( 
      'icon' => 'magnolia.png', 
      'url' => 'http://ma.gnolia.com/beta/bookmarklet/add?url={LINK}&@@ta 
title={TITLE}&description={TITLE}'), 
   
    'NewsVine' => array( 
      'icon' => 'newsvine.png', 
      'url' => 'http://www.newsvine.com/_tools/seed&save?u={LINK}&h={TITLE}'), 
   
    'Netvouz' => array( 
      'icon' => 'netvouz.png', 
      'url' => 'http://www.netvouz.com/action/submitBookmark?url={LINK}&@@ta 
title={TITLE}&description={TITLE}'), 
     
    'Reddit' => array( 
      'icon' => 'reddit.png', 
      'url' => 'http://reddit.com/submit?url={LINK}&title={TITLE}'), 
   
    'scuttle' => array( 
      'icon' => 'scuttle.png', 
      'url' => 'http://www.scuttle.org/bookmarks.php/maxpower?action=add&@@ta 
address={LINK}&title={TITLE}&description={TITLE}'), 
   
    'Shadows' => array( 
      'icon' => 'shadows.png', 
      'url' => 'http://www.shadows.com/features/tcr.htm?url={LINK}&title={TITLE}'), 
   
    'Simpy' => array( 
      'icon' => 'simpy.png', 
      'url' => 'http://www.simpy.com/simpy/LinkAdd.do?href={LINK}&title={TITLE}'), 
   
    'Smarking' => array( 
      'icon' => 'smarking.png', 
      'url' => 'http://smarking.com/editbookmark/?url={LINK}&description={TITLE}'), 
   
    'Spurl' => array( 
      'icon' => 'spurl.png', 
      'url' => 'http://www.spurl.net/spurl.php?url={LINK}&title={TITLE}'), 
   
    'TailRank' => array( 
      'icon' => 'tailrank.png', 
      'url' => 'http://tailrank.com/share/?text=&link_href={LINK}&title={TITLE}'), 
   
    'Wists' => array( 
      'icon' => 'wists.png', 
      'url' => 'http://wists.com/r.php?c=&r={LINK}&title={TITLE}'), 
   
    'YahooMyWeb' => array( 
      'icon' => 'yahoomyweb.png', 
      'url' => 'http://myweb2.search.yahoo.com/myresults/bookmarklet?u={LINK}@@ta 
&={TITLE}')
  );*/
   
  // the constructor
  function SocialBookmarking($link, $title, $site_name) 
  { 
    $this->_link = $link; 
    $this->_title = $title; 
    $this->_site_name = $site_name; 
  }

  // returns the HTML with social bookmarking symbols 
  function getHTML($sites = 
                   array('del.icio.us', 'digg', 'Furl', 'Reddit', 'YahooMyWeb')) 
  { 
    // build the output
    $html_feed = '<ul class="social_bookmarking">'; 
    // create HTML for each of the sites received as parameter
    foreach($sites as $s) 
    { 
      if ($_site_info = $this->_templates[$s]) 
      {
        $html_feed .= '<li class="social_bookmarking">';
        $url = str_replace(array('{LINK}', '{TITLE}', '{SITENAME}'), 
                           array(urlencode($this->_link), 
                           urlencode($this->_title), 
                           urlencode($this->_site_name)), 
                           $_site_info['url']);  
        $html_feed .= '<a rel="nofollow" href="' . $url . '" title="' . $s . '">'; 
        $html_feed .= '<img src="' . SITE_DOMAIN . 
                '/seophp/social_icons/' . $_site_info['icon'] . '" alt="' . $s . 
                '" class="social_bookmarking" />';
        $html_feed .= '</a></li>'; 
      }              
    }     
    $html_feed .= '</ul>';
    return $html_feed;
  }

  // returns HTML with social bookmarking links for inclusion in feeds
  function getFeedHTML($sites = 
                     array('del.icio.us', 'digg', 'Furl', 'Reddit', 'YahooMyWeb'))
  {
    // initialize $html_feed
    $html_feed = '';

    // build the HTML feed
    foreach($sites as $s) 
    { 
      if ($_site_info = $this->_templates[$s]) 
      {
        $url = str_replace(array('{LINK}', '{TITLE}', '{SITENAME}'),
                           array(urlencode($this->_link), 
                                 urlencode($this->_title), 
                                 urlencode($this->_site_name)), 
                           $_site_info['url']); 
        $html_feed .= '<a rel="nofollow" href="' . $url . 
                      '" title="' . $s . '">';
        $html_feed .= '<img src="/seophp/social_icons/' . $_site_info['icon'] .
                      '" alt="' . $s . '" class="social_bookmarking" />'; 
        $html_feed .= '</a> ';
      }
    }

    // return the HTML feed
    return '<p>' . $html_feed . '</p>'; 
  }
} 
?>  
