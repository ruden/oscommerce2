<div class="t-information mr-auto col-auto">

  <?php
  foreach ($information_array as $information) {
    ?>

    <a class="mr-1 col-form-label-sm" href="<?php echo tep_href_link('information.php', 'pages_id=' . $information['pages_id']); ?>"><?php echo $information['pages_name']; ?></a>

    <?php
  }
  ?>

  <a class="col-form-label-sm" href="<?php echo tep_href_link('contact_us.php', null, 'SSL'); ?>"><?php echo MODULE_HEADER_INFORMATION_TEXT_CONTACT_US; ?></a>

</div>
