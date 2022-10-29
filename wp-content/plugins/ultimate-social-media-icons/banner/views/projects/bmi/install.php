<?php

  // Namespace
  namespace Inisev\Subs;

  // Disallow direct access
  if (!defined('ABSPATH')) exit;

?>

<div class="ci-left-part ci-install-state">
  <div class="ci-project-logo">
    <a href="https://www.ultimatelysocial.com/usm-premium/?utm_source=usmi_settings_page&utm_campaign=usm_bottom_banner_to_checkout&utm_medium=banner" target="_blank">
      <div class="ci-project-logo-element">
        <img src="<?php $this->_asset('/projects/bmi/imgs/big-colored-logo.png'); ?>">
        <span><b>Backup</b> &<br>Clone & Migration</span>
      </div>
    </a>
    <img src="<?php $this->_asset('/static/imgs/rating.svg'); ?>" class="ci-rating" >
  </div>
  <div class="ci-install-column">
    <ul class="ci-checkmark-list ci-checkmark-list-type-1">
      <li>Create backups & migrate your site</li>
      <li>Don't lose your work / switch to another host with ease</li>
      <li><b>Free</b> <span class="ci-light-font">(optional upgrade to <a href="https://www.ultimatelysocial.com/usm-premium/?utm_source=usmi_settings_page&utm_campaign=usm_bottom_banner_to_checkout&utm_medium=banner" target="_blank">premium</a>)</span></li>
    </ul>
    <div class="ci-install-button">
      <button class="ci-inisev-install-plugin" data-slug="bmi">Install plugin now</button>
      <span>(from <a href="https://wordpress.org/plugins/backup-backup/" target="_blank">WP directory</a>)</span>
    </div>
  </div>
</div>
