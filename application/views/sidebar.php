<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Sidebar partial (CI3 view) -->
<aside class="ms-sidebar" aria-label="Main navigation">
  <div class="ms-logo">
    <img src="<?php echo base_url('assets/img/logosidebar.png'); ?>" alt="M-Sales logo">
  </div>

  <div class="ms-user">
    <div class="ms-avatar">
      <img src="<?php echo base_url('assets/img/Profile.svg'); ?>" alt="User avatar">
    </div>
    <div class="ms-user-info">
      <div class="ms-user-name">Budi Dharma</div>
      <div class="ms-user-role">BSH</div>
    </div>
    <button class="ms-chevron" aria-label="Open user menu">▾</button>
  </div>

  <hr class="ms-divider">

 <nav class="ms-menu" aria-label="Primary">
  <a class="ms-item ms-item--active" >
     <span class="ms-item-left">
    <img src="<?php echo base_url('assets/img/icon/dashboard.svg'); ?>" alt="Dashboard icon">
    <span class="ms-label">Dashboard</span>
    </span>
  </a>

  <a class="ms-item ms-item--has-submenu" href="#" aria-expanded="false">
    <span class="ms-item-left">
      <img  src="<?php echo base_url('assets/img/icon/Application Input.svg'); ?>" alt="Application Input icon">
      <span class="ms-label">Application Input</span>
    </span>
    <svg class="ms-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 6l6 6-6 6" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
  </a>

  <!-- Submenu for Application Input -->
  <div class="ms-submenu" aria-hidden="true">
    <a class="ms-subitem ms-subitem--active" href="<?php echo base_url('preview_pemol'); ?>">PEMOL</a>
    <a class="ms-subitem" href="#">Merchant</a>
    <a class="ms-subitem" href="#">CC Reguler</a>
    <a class="ms-subitem" href="#">Mobile Sales</a>
    <a class="ms-subitem" href="#">Corporate</a>
    <a class="ms-subitem" href="#">Smart Cash (SC)</a>
    <a class="ms-subitem" href="#">Personal Loan (PL)</a>
  </div>

  <a class="ms-item ms-item--has-submenu" href="#" aria-expanded="false">
    <span class="ms-item-left">
    <img src="<?php echo base_url('assets/img/icon/Data Decision.svg'); ?>" alt="Data Decision icon">
    <span class="ms-label">Data Decision</span>
    </span>
        <svg class="ms-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 6l6 6-6 6" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
  </a>

  <!-- Submenu for Data Decision -->
  <div class="ms-submenu" aria-hidden="true">
    <a class="ms-subitem ms-subitem--active" href="<?php echo base_url('preview_pemol'); ?>">PEMOL</a>
    <a class="ms-subitem" href="#">Merchant</a>
    <a class="ms-subitem" href="#">Credit Card (CC)</a>
    <a class="ms-subitem" href="#">Corporate</a>
    <a class="ms-subitem" href="#">Smart Cash (SC)</a>
    <a class="ms-subitem" href="#">Personal Loan (PL)</a>
  </div>

  <a class="ms-item ms-item--has-submenu" href="#" aria-expanded="false">
    <span class="ms-item-left">
      <img  src="<?php echo base_url('assets/img/icon/Icon.svg'); ?>" alt="Incoming icon">
      <span class="ms-label">Incoming</span>
    </span>
        <svg class="ms-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 6l6 6-6 6" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
  </a>

   <!-- Submenu for Incoming -->
  <div class="ms-submenu" aria-hidden="true">
    <a class="ms-subitem ms-subitem--active" href="#">Mobile Sales</a>
    <a class="ms-subitem" href="#">PEMOL</a>
    <a class="ms-subitem" href="#">TM CC</a>
    <a class="ms-subitem" href="#">TM SC</a>
  </div>

  <a class="ms-item ms-item--has-submenu" href="#" aria-expanded="false">
    <span class="ms-item-left">
      <img src="<?php echo base_url('assets/img/icon/Application Check.svg'); ?>" alt="Application Check icon">
      <span class="ms-label">Application Check</span>
    </span>
    <svg class="ms-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 6l6 6-6 6" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
  </a>

  <div class="ms-submenu" aria-hidden="true">
    <a class="ms-subitem ms-subitem--active" href="#">PEMOL</a>
    <a class="ms-subitem" href="#">Merchant</a>
    <a class="ms-subitem" href="#">Credit Card (CC)</a>
    <a class="ms-subitem" href="#">Corporate</a>
    <a class="ms-subitem" href="#">Smart Cash (SC)</a>
    <a class="ms-subitem" href="#">Personal Loan (PL)</a>
  </div>

  <a class="ms-item" href="#">
    <span class="ms-item-left">
    <img src="<?php echo base_url('assets/img/icon/My Performance.svg'); ?>" alt="My Performance icon">
    <span class="ms-label">My Performance</span>
    </span>
  </a>

  <a class="ms-item" href="#">
    <span class="ms-item-left">
      <img src="<?php echo base_url('assets/img/icon/Data Addendum.svg'); ?>" alt="Data Addendum icon">
      <span class="ms-label">Data Addendum</span>
    </span>
  </a>

  <a class="ms-item ms-item--has-submenu" href="#" aria-expanded="false">
    <span class="ms-item-left">
        <img src="<?php echo base_url('assets/img/icon/Candidate Info.svg'); ?>" alt="Candidate Info icon">
      <span class="ms-label">Candidate Info</span>
    </span>
    <svg class="ms-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 6l6 6-6 6" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
  </a>

  <div class="ms-submenu" aria-hidden="true">
    <a class="ms-subitem ms-subitem--active" href="#">Candidate Details</a>
    <a class="ms-subitem" href="#">Approval</a>
    <a class="ms-subitem" href="#">History</a>
  </div>

  <a class="ms-item ms-item--has-submenu" href="#" aria-expanded="false">
    <span class="ms-item-left">
        <img src="<?php echo base_url('assets/img/icon/Request to HRD.svg'); ?>" alt="Request to HRD icon">
      <span class="ms-label">Request to HRD</span>
    </span>
    <svg class="ms-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 6l6 6-6 6" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
  </a>

   <div class="ms-submenu" aria-hidden="true">
    <a class="ms-subitem ms-subitem--active" href="#">Exit</a>
    <a class="ms-subitem" href="#">Restruct</a>
    <a class="ms-subitem" href="#">Level</a>
    <a class="ms-subitem" href="#">Reaktif</a>
  </div>

  <a class="ms-item ms-item--has-submenu" href="#" aria-expanded="false">
    <span class="ms-item-left">
        <img src="<?php echo base_url('assets/img/icon/Approval.svg'); ?>" alt="Approval icon">
      <span class="ms-label">Approval</span>
    </span>
    <svg class="ms-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 6l6 6-6 6" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
  </a>

  <div class="ms-submenu" aria-hidden="true">
    <a class="ms-subitem ms-subitem--active" href="#">Restruct</a>
    <a class="ms-subitem" href="#">Reaktif</a>
    <a class="ms-subitem" href="#">Promotion</a>
  </div>

  <a class="ms-item" href="#">
    <span class="ms-item-left">
        <img src="<?php echo base_url('assets/img/icon/Check Postal Code.svg'); ?>" alt="Check Postal Code icon">
    <span class="ms-label">Check Postal Code</span>
    </span>
  </a>

  <a class="ms-item" href="#">
    <span class="ms-item-left">
        <img src="<?php echo base_url('assets/img/icon/Duplicate Check.svg'); ?>" alt="Duplicate Check icon">
    <span class="ms-label">Duplicate Check</span>
    </span>
  </a>

  <a class="ms-item" href="#">
    <span class="ms-item-left">
        <img src="<?php echo base_url('assets/img/icon/Monitoring.svg'); ?>" alt="Monitoring icon">
    <span class="ms-label">Monitoring</span>
    </span>
  </a>

</nav>
</aside>
