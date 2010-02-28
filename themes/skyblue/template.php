<?php
// $Id$
/**
 * @file
 * template file for  subtheme
 */

/**
 * Take in results of term node count, and return output for tag cloud
 */
function _make_tag_cloud($result, $max_count=15) {
  $terms_output = '';
  foreach($result as $key => $value) {
    if ($value->term_node_count_node_count != 0) {
      
      $count = $value->term_node_count_node_count;
      
      $options['attributes']['title'] = $value->term_node_count_node_count . ' post';
      if ($value->term_node_count_node_count > 1) {
        $options['attributes']['title'] .= 's';
      }
      $options['attributes']['title'] = t($options['attributes']['title'].' about '. $value->term_data_name);
      
      // cap the max count at 10
      if ($count > $max_count ) {
        $count = 'max';
      }
      $terms_output .= '<li class="count_'.$count.'">';
      $terms_output .= l(t($value->term_data_name), 'taxonomy/term/'.$value->tid, $options);
      $terms_output .= '</li>';
    }
  }
  if (strlen($terms_output)) {
    $terms_output = '<ul>'.$terms_output.'</ul>';
  }
  return $terms_output;
}

function format_date_parts ($date) {
  //assumes $date is d.M.Y
  $date_string = format_date($date, 'custom', 'd.m.Y');
  $arr_dateparts = explode('.',$date_string);
  $chinese_month = array(                 
    '01' => '一月',
    '02' => '二月',
    '03' => '三月',
    '04' => '四月',
    '05' => '五月',
    '06' => '六月',
    '07' => '七月',
    '08' => '八月',
    '09' => '九月',
    '10' => '十月',
    '11' => '十一月',
    '12' => '十二月'
  );
                     
  $ret = '';
  $ret .= '<span class="month" title="'.$arr_dateparts[1].'">'.$chinese_month[$arr_dateparts[1]].'</span><span class="delimiter"> / </span>';
  $ret .= '<span class="date" title="'.$arr_dateparts[0].'">'.$arr_dateparts[0].'</span><span class="delimiter"> / </span>';
  $ret .= '<span class="year" title="'.$arr_dateparts[2].'">'.$arr_dateparts[2].'</span>';
  return $ret;
}

function phptemplate_preprocess(&$vars, $hook) {
  if($hook == 'page') {
    // Add a 'page-node' class if this is a node that is rendered as page
    if (isset($vars['node']) && $vars['node']->type) {
      $vars['body_classes'] .= ' page-node';
    }
    
    // Add Feed icon
    $array_q = explode('/', $_GET['q']);
    if($array_q[0] == 'blog'){
      drupal_add_feed(url('blog/rss.xml', array('absolute' => TRUE)));
    } elseif($array_q[0] == 'projects') {
      drupal_add_feed(url('projects/rss.xml', array('absolute' => TRUE)));
    } elseif($array_q[0] == '字') {
      drupal_add_feed(url('字/rss.xml', array('absolute' => TRUE)));
    } else {
      drupal_add_feed(url('rss.xml', array('absolute' => TRUE)));
    }
    
    $vars['head'] = drupal_get_html_head();   // Refresh $head variable
    $vars['feed_icons'] = drupal_get_feeds();  // Refresh $feed_icons variable
  }
  
  // Replace funny chinese characters in section name
  //$vars['body_classes'] = str_replace('-e6-bc-a2-e5-ad-97-e6-84-9f-e3-81-98', 'kanjikanji', $vars['body_classes']);
}

function phptemplate_preprocess_page(&$vars) {

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

  // Format nice blog dates
  $vars['blog_date'] = format_date($vars['node']->created, 'large'); 
  
  if ($vars['page']) {

    // To access regions in nodes
    $vars['node_bottom'] = theme('blocks', 'node_bottom');
    
    // Add css for node pages
    drupal_add_css(path_to_theme() . '/css/node.css', 'theme');
    drupal_add_css(path_to_theme() . '/css/comments.css', 'theme');
    drupal_add_css(path_to_theme() . '/css/syntax_highlighting.css', 'theme');
  
    // Handle specific node types
    if ($vars['node']->type == 'blog') {
      
    } elseif ($vars['node']->type == 'update') {
      drupal_add_css(path_to_theme() . '/css/book.css', 'theme');
      drupal_add_css(path_to_theme() . '/css/node_project.css', 'theme');
      drupal_add_feed('/project_feed/rss.xml?project_id='.$vars['node']->book['bid']);
    
    } elseif ($vars['node']->type == 'project') {
      drupal_add_css(path_to_theme() . '/css/book.css', 'theme');
      drupal_add_css(path_to_theme() . '/css/node_project.css', 'theme');
      drupal_add_feed('/project_feed/rss.xml?project_id='.$vars['node']->nid);
      $vars['blog_date'] = null;
    
    } elseif ($vars['node']->type == 'zi') {
      drupal_add_css(path_to_theme() . '/css/node_zi.css', 'theme');
    }
    
  }
  
  // different date for zi
  if ($vars['node']->type == 'zi') {
    $vars['blog_date'] = format_date_parts($vars['node']->created);
  }
  
}

/**
 * Implementation of template_preprocess_views_view
 */
function phptemplate_preprocess_views_view__projects(&$vars) {
  drupal_add_css(path_to_theme() . '/css/home_projects.css', 'theme');
}

function phptemplate_preprocess_views_view__section_listing(&$vars) {
  if ($vars['view']->current_display == 'page_1') {
    drupal_add_css(path_to_theme() . '/css/node.css', 'theme');
    drupal_add_css(path_to_theme() . '/css/home_blog.css', 'theme');
  } elseif ($vars['view']->current_display == 'page_2') {
    drupal_add_css(path_to_theme() . '/css/node.css', 'theme');
    drupal_add_css(path_to_theme() . '/css/node_zi.css', 'theme');
    drupal_add_css(path_to_theme() . '/css/home_zi.css', 'theme');
  }
}

function phptemplate_preprocess_views_view__archive(&$vars) {
  if ($vars['view']->current_display == 'page_1') {
    drupal_add_css(path_to_theme() . '/css/node.css', 'theme');
    drupal_add_css(path_to_theme() . '/css/home_blog.css', 'theme');
  } elseif ($vars['view']->current_display == 'page_2') {
    drupal_add_css(path_to_theme() . '/css/node.css', 'theme');
    drupal_add_css(path_to_theme() . '/css/node_zi.css', 'theme');
    drupal_add_css(path_to_theme() . '/css/home_zi.css', 'theme');
  }
}

function phptemplate_preprocess_views_view__taxonomy_term(&$vars) {
  drupal_add_css(path_to_theme() . '/css/node.css', 'theme');
  drupal_add_css(path_to_theme() . '/css/topics.css', 'theme');
  $vars['title'] = 'asd';
}

function phptemplate_preprocess_views_view__topics(&$vars) {
  if ($vars['view']->current_display == 'page_1') {
    drupal_add_css(path_to_theme() . '/css/node.css', 'theme');
    drupal_add_css(path_to_theme() . '/css/topics.css', 'theme');
  }
  $vars['rows'] = _make_tag_cloud($vars['view']->result);
}

/**
 * implementation of template_preprocess_book_navigation
 */
function phptemplate_preprocess_book_navigation(&$variables) {
  $variables['tree'] = NULL;
}

function phptemplate_preprocess_comment_wrapper(&$vars) {
  if ($vars['node']->comment_count > 0) {
    $vars['has_comments'] = TRUE;
  }
}

/**
 * Implementation of hook_link_alter() for node_link() in node module to always show 'Read More' link.
 */
function node_link_alter(&$links, $node) {
  // Use 'Read more »' instead of 'Read more'
  if (isset($links['node_read_more'])) {
    $links['node_read_more']['title'] = t('Read more »');
  }
  // Don't show add new comment link
  if (isset($links['comment_add'])) {
    unset($links['comment_add']);
  }
}
