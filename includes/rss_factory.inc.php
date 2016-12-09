<?php

// load social bookmarking helper class
require_once 'social_bookmarking.inc.php';

class RSSFactory 
{ 
  var $_title; 
  var $_link; 
  var $_description; 
  var $_language; 
  var $_items; 
   
  // escape string characters for inclusion in XML structure
  function _escapeXML($str)
  {
    $translation = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES); 
    foreach ($translation as $key => $value) 
    {
      $translation[$key] = '&#' . ord($key) . ';'; 
    }
    $translation[chr(37)] = '&';
    return preg_replace("/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,3};)/","&#38;", 
                        strtr($str, $translation)); 
  } 

  // the class constructor is executed when creating an instance of the class
  function RSSFactory($title, $link, $description, 
                      $language = 'es-ec', $items = array())  
  { 
    // save feed data to local class members
    $this->_title = $title; 
    $this->_link = $link; 
    $this->_description = $description; 
    $this->_language = $language; 
    $this->_items = $items; 
  } 
   
  // adds a new feed item
  function addItem($title, $link, $description, $additional_fields = array()) 
  { 
    // add feed item
    $this->_items[] = 
      array_merge(array('title' => $title, 
                        'link' => $link, 
                        'description' => $description), 
                  $additional_fields); 
  } 
   
  // generates feed
  function get() 
  {
    // initial preparations
    ob_start(); 
    header('Content-type: text/xml'); 
   
    // generate feed header
    echo '<rss version="2.0">' . 
         '<channel>' . 
           '<title>' . RSSFactory::_escapeXML($this->_title) . '</title>' . 
           '<link>' . RSSFactory::_escapeXML($this->_link) . '</link>' .
           '<description>' . 
             RSSFactory::_escapeXML($this->_description) . 
           '</description>';

    // add feed items
    foreach ($this->_items as $feed_item)
    {
      // add a feed item and its contents
      echo '<item>';
      foreach ($feed_item as $item_name => $item_value) 
      { 
        // add social bookmarking icons to feed description 
        if ($item_name == 'description')
        {
          // instantiate class by providing link, title, and site name
          $social = new SocialBookmarking($feed_item['link'], 
                                          $feed_item['title'],
                                          $this->_title); 

          // add social bookmarking icons to the feed
          $item_value = $item_value . $social->getFeedHTML();
        }

        // output feed item
        echo "<$item_name>" . 
               RSSFactory::_escapeXML($item_value) . 
             "</$item_name>"; 
      }        
      echo '</item>';
    }

    // close channel and rss elements
    echo '</channel></rss>';

    // return feed data
    return ob_get_clean();
  }
}

?>
