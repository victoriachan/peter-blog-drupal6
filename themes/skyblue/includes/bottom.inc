<?php
/**
 * Bottom Content
 */
 ?>
    <?php if ($side_tab): ?>
      <div id="side-tab"><?php print $side_tab; ?></div>
    <?php endif; ?> 
 
    <?php if ($content_bottom): ?>
      <div id="content-bottom" class="content-bottom"><div class="inner">
        <!-- REGION content_bottom -->
        <?php print $content_bottom; ?>
      </div></div> <!-- /.content-bottom -->
    <?php endif; ?>
     
    <?php if ($footer || $footer_message): ?>
    <div id="footer" class="footer">
      <div class="inner">
      <?php print $footer; ?>
      <?php if ($footer_message): ?>
        <div id="footer-message"><?php print $footer_message; ?></div>
      <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>  
   
  <?php print $closure; ?>
</div></div><!-- /third-body /second-body --></body>
</html>