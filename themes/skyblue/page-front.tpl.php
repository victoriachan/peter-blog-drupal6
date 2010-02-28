<?php
// $Id$
/* *
 * @file
 * page.tpl.php
 */

/**
 * documentation:
 * http://api.drupal.org/api/file/modules/system/page.tpl.php
 * -------------------------------------
 * page vars dsm(get_defined_vars())
 * -------------------------------------
 * <?php print $base_path; ?>
 * <?php print $is_front ?>
 */
 
 //dsm(get_defined_vars());
?>
<?php 
  include("includes/top.inc"); 
?>

  <div id="site-wrapper">
    <div id="main-wrapper">
      <?php if (!empty($admin)) print $admin; ?>
      
      <div id="content-top" class="content-top">
        
        <?php print $site_slogan ?>
        <?php print $mission ?>
        
        <?php if (!$node): ?>
          <h1 class="title"><?php print $title; ?></h1>
        <?php endif; ?>
        
        <?php if ($help OR $messages OR $tabs): ?>
        <div id="admin-tabs-top">  
          <?php print $help ?>
          <?php print $messages ?>
          <?php print $tabs; ?>
        </div>
        <?php endif; ?>
      </div><!-- /.content_top -->
      
      <?php if ($right): ?>
        <div class="secondary-content">
          <?php print $right; ?>
        </div><!-- /#secondary_content -->
      <?php endif; ?>      
      
      <div id="page-content" class="page-content">
        <?php //print $content; ?>
      </div><!-- /.content -->
        
    </div><!-- /.main_wrapper -->    
  </div><!-- /#site_wrapper -->
  
<?php
  include("includes/bottom.inc"); 
?>