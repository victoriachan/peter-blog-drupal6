<?php
/**
 * Top Content
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>">
<head>
  <title><?php print $head_title; ?></title>
  <?php print $head; ?>
  <?php print $styles; ?>
  <?php print $scripts; ?>  
  <?php 
    // Output CSS for Animal picture
    if ($node->field_animal_right[0]['value'] || $node->field_animal_top[0]['value']):
   ?>
    <style type="text/css" media="screen">
      .field-animal-picture {
        <?php print $node->field_animal_right[0]['value']? 'right:'.$node->field_animal_right[0]['value'].'px;' : '';?>
        <?php print $node->field_animal_top[0]['value']? 'top:'.$node->field_animal_top[0]['value'].'px;' : '';?>      
      }
    </style>
   <?php endif; ?>
  <!--[if IE]>
    <?php print '<link rel="stylesheet" href="'.path_to_theme().'/css/ie.css" />' ?>
    <![endif]-->
</head>

<body class="<?php print trim($body_classes); ?>"><div id="second-body"><div id="third-body">
  <div id="top-bar"><div class="inner">
    <?php // print site name (h1 or div) ?>
    <<?php print $site_name_element; ?> id="site-name">
      <?php print l($site_name, '', array(html=>true, attributes=>array('title' => t('Return to homepage'), 'rel'=>'home'))); ?>
    </<?php print $site_name_element; ?>>
    
    <?php if ($top_bar): ?>
      <!-- REGION top_bar -->
      <?php print $top_bar; ?>
    <?php endif; ?>
  </div></div><!-- /#top-bar /.inner -->