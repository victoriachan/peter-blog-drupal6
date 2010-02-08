<?php
// $Id$
/**
 * @file
 * template file for  subtheme
 */
 
function phptemplate_preprocess(&$vars, $hook) {
  if($hook == 'page') {
    // Add a 'page-node' class if this is a node that is rendered as page
    if (isset($vars['node']) && $vars['node']->type) {
      $vars['body_classes'] .= ' page-node';
    }
    
    //// Add Feed icon
    //if($array_q[0] == 'geek'){
    //  drupal_add_feed(url('geek/feed', array('absolute' => TRUE)), 'VictoriaC RSS | Geek');
    //} elseif($array_q[0] == 'life') {
    //  drupal_add_feed(url('life/feed', array('absolute' => TRUE)), 'VictoriaC RSS | Life');
    //} elseif($array_q[0] == 'today') {
    //  drupal_add_feed(url('today/feed', array('absolute' => TRUE)), 'VictoriaC RSS | Today');
    //} elseif($array_q[0] == '漢字感じ') {
    //  drupal_add_feed(url('漢字感じ/feed', array('absolute' => TRUE)), 'VictoriaC RSS | 漢字感じ');
    //} else {
    //  drupal_add_feed(url('feed', array('absolute' => TRUE)), 'VictoriaC RSS | All');
    //}
    //$vars['head'] = drupal_get_html_head();   // Refresh $head variable
    //$vars['feed_icons'] = drupal_get_feeds();  // Refresh $feed_icons variable

  }
  
  // Replace funny chinese characters in section name
  //$vars['body_classes'] = str_replace('-e6-bc-a2-e5-ad-97-e6-84-9f-e3-81-98', 'kanjikanji', $vars['body_classes']);
}

function phptemplate_preprocess_page(&$vars) {
  
  // Add css for node pages
  if ($vars['node']) {
    drupal_add_css(path_to_theme() . '/css/node.css', 'theme');
    drupal_add_css(path_to_theme() . '/css/comments.css', 'theme');
  }
  
  // Add css for specific node types
  if ($vars['node']->type == 'blog') {
    drupal_add_css(path_to_theme() . '/css/node_blog.css', 'theme');
  } elseif ($vars['node']->type == 'update') {
    drupal_add_css(path_to_theme() . '/css/node_update.css', 'theme');
  } elseif ($vars['node']->type == 'project') {
    drupal_add_css(path_to_theme() . '/css/node_project.css', 'theme');
  } elseif ($vars['node']->type == 'tiantian') {
    drupal_add_css(path_to_theme() . '/css/node_tiantian.css', 'theme');
  }
  
  // Add css for search
  $array_q = explode('/', $_GET['q']);
  if ($array_q[0] == 'search') {
    drupal_add_css(path_to_theme() . '/css/search.css', 'theme');
    $vars['tabs'] = null; // don't show search tabs
  }
  
  //Reload css/js. This is needed for preprocess_page since css/js is already loaded.
  $vars['styles'] = drupal_get_css();
}

function phptemplate_preprocess_node(&$vars) {
  
  // To access regions in nodes
  $vars['node_bottom'] = theme('blocks', 'node_bottom');
  
  // Load node type-specific preprocess functions (if they exist)
  $function = 'phptemplate_preprocess_node'.'_'. $vars['node']->type;
  if (function_exists($function)) {
    $function(&$vars);
  } else {
    // Load the usual node stuff
    phptemplate_preprocess_node_default($vars);
  }
}

function phptemplate_preprocess_node_default(&$vars) {
  /**
   * load usual node stuff
   */
  drupal_add_css(path_to_theme() . '/css/node.css', 'theme'); 
   
  // Format nice blog dates
  $vars['blog_date'] = format_date($vars['node']->created, 'large');

   // embedded video
   if ($vars['page'] && $vars['node']->field_video[0]['value']) {
     $vars['video'] = views_embed_view('node_content','block_1', $vars['node']->nid);
   } 
}

/**
* function to overwrite links. removes the reply link per node type
*
* @param $links
* @param $attributes
* @return unknown_type
*/
// Can't name as phptemplate_links() as mothership is using that
//function phptemplate_links($links, $attributes = array('class' => 'links')) {
//  
//  // Link 'Add a comment' link to node page instead of comments reply page
//  if($links['comment_add']['href']){
//    $arr_linkparts = explode('/', $links['comment_add']['href']);
//    $links['comment_add']['href'] = 'node/'.$arr_linkparts[2];
//  }
//  // Don't show 'reply' link for comments
//  unset($links['comment_reply']);
//  
//  return theme_links($links, $attributes);
//}