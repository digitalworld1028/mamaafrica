<footer class="main-footer">
 <?php $settings = get_options(array("copyright","website")); ?> 
    <strong><?php echo _l("Copyright"); ?> &copy; 2019-2020 <a href="<?php echo $settings["website"]; ?>"><?php echo $settings["copyright"]; ?></a>.</strong> 
    <?php echo _l("All rights reserved."); ?> 
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>
