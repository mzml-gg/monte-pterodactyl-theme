<style id="nebula-mobile-navigation">
  .nebula-mobile-nav { display: none }

  @if($n_sidebar_full == "1" && Auth::check())

    /* full sidebar */
    .sidebarIcon {
      float: left;
      margin-left: 15px;
      /*margin-top: 9px;*/
      padding-right: 13px;
      line-height: 55px;
      font-size: calc({{ $__SCALE }} * 0.8);
    }
    .nebula-mobile-nav { display: none; }
    .sidebar {
      width: 200px;
      display: block;
    }
    div.ProgressBar___StyledDiv-sc-14ayc3f-1.jleFWY {
      left: 195px !important;
      width: calc(100% - 195px) !important;
    }
    .sidebarContentContainer {
      width: 100%;
    }
    
    .customicon {
      height: 100%;
      aspect-ratio: 1/1;
      float: left;
      width: auto;
    }
    body, body.bg-neutral-800 {
      padding-left: 200px;
    }
    .sidebarSpacer {
      width: calc(100% - 31px);
      margin-left: 5px;
      margin-right: 5px;
    }
    .wideSidebarSpan {
      color: #ff365f !important;
      text-shadow: 0 1px 3px rgba(50,0,10,.75);
      text-align: left;
      display: block;
      width: 100%;
      font-size: 17px;
      font-weight: 500;
      height: 100%;
      /*padding-top: 15px;*/
      line-height: 55px;
      font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    }
    .sidebarButton {
      width: calc(100% - 10px - 10px);
      transition:
        border .2s,
        border-left .2s;
    }
    @if($n_sidebar_customlogo != "")
      /* custom logo */
      .customlogo {
        height: 62px;
        width: auto;
        margin-right: auto;
        border-radius: var(--borderRadiusSidebar);
        margin-bottom: 10px;
      }
    @endif
    @if($n_sidebar_buttonstyle == "1")
      .sidebarButton {
        border-left: 0px solid transparent;
      }
      .sidebarButtonSelected {
        background-color: var(--sidebarSecondary);
        border-left: 10px solid var(--sidebarButtonActive) !important;
        transition: border .2s, border-left .2s;
      }
    @elseif($n_sidebar_buttonstyle == "2")
      .sidebarIcon {
        line-height: 49px !important;
        margin-left: 12px !important;
      }
      .wideSidebarSpan {
        line-height: 49px !important;
      }
      .sidebarButton {
        --border: 3px solid transparent;
        border: 3px solid transparent !important;
        border-left: 3px solid transparent !important;
      }
      .sidebarButtonSelected {
        background-color: var(--sidebarSecondary);
        --border: 3px solid var(--sidebarButtonActive);
        border-left: 3px solid var(--sidebarButtonActive) !important;
        border: 3px solid var(--sidebarButtonActive) !important;
        transition: border .2s, border-left .2s;
      }
    @endif
  @endif
  @media screen and (max-width: 760px) {
    body, body.bg-neutral-800 {
      padding-left: unset !important;
    }

    .sidebar {
      display: none;
      z-index: 101;
    }
    div.ProgressBar___StyledDiv-sc-14ayc3f-1.jleFWY {
      left: 0 !important;
      width: 100% !important;
    }
    .nebula-mobile-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 80px;
      z-index: 99;
      /*background-color: var(--sidebarBackground);
      border-top: 1px solid var(--sidebarSecondaryHover);*/
      display: flex;
      justify-content: center;
      flex-direction: row;
      background: transparent;
      background: linear-gradient(180deg, transparent 0%, var(--pageBackground) 100%); 
    }
    .mobile-nav-container {
      margin-left: 15px;
      margin-right: 15px;
    }

    .mobile-nav-icon {
      font-size: 20px;
      color: var(--sidebarPrimary);
    }
    .mobile-nav-button {
      background-color: var(--sidebarSecondary);
      border-radius: 100px;
      padding-left: 25px;
      padding-right: 25px;
      padding-top: 10px;
      padding-bottom: 10px;
    }

    .nebula-mobile-overlay {
      width: 100%;
      height: 100%;
      opacity: 0;
      display: none;
      position: fixed;
      left: -200px;
      top: 0;
      transition: opacity .4s, left .4s;
      z-index: 100;
      background: var(--pageBackground);
      background: linear-gradient(90deg, var(--pageBackground) 75%, transparent 100%);
    }

    .pointer-events-none.fixed.bottom-0.mb-6.flex.justify-center.w-full.z-50:has(.ipOPTy) {
      margin-bottom: 0px !important;
      margin-top: 20px !important;
      bottom: unset !important;
      top: 0px !important;
    }
    #fileMode {
      bottom: unset !important;
      top: 20px !important;
    }

     /* loading bar styling */
    .CbttB {
      top: 0;
      bottom: unset !important;
    }
  }
</style>

@if(Auth::check())
  <div class="nebula-mobile-overlay" onclick="closeMobileNav()"></div>
  <div class="nebula-mobile-nav">
    <div class="mobile-nav-container">
      <!-- Home -->
      <button class="mobile-nav-button" onclick="sidebarRefresh();sidebarButtonEvent('home')"><i class="mobile-nav-icon bi bi-house-fill"></i></button>
    </div>
    <div class="mobile-nav-container">
      <!-- Account -->
      <button class="mobile-nav-button" onclick="sidebarRefresh();sidebarButtonEvent('accountAccount')"><i class="mobile-nav-icon bi bi-person-fill"></i></button>
    </div>
    <div class="mobile-nav-container">
      <!-- Navigation -->
      <button class="mobile-nav-button" onclick="sidebarRefresh();openMobileNav()"><i class="mobile-nav-icon bi bi-list"></i></button>
    </div>
  </div>
  <script>
    mobileNavigationVisible=false
    navPossible=true
    function toggleMobileNav() {
      if(mobileNavigationVisible) {
        closeMobileNav()
      } else {
        openMobileNav()
      }
    }
    function closeMobileNav() {
      if(navPossible == false) {return;}
      navPossible = false
      let sidebar = document.querySelector(".sidebar")
      let overlay = document.querySelector(".nebula-mobile-overlay")
      sidebar.style.left = "-200px"
      overlay.style.left = null
      overlay.style.opacity = null
      setTimeout(() => {
        sidebar.style.display = null
        overlay.style.display = null
        navPossible = true
      },300)
      mobileNavigationVisible=false
    }
    function openMobileNav() {
      if(navPossible == false) {return;}
      navPossible = false
      let sidebar = document.querySelector(".sidebar")
      let overlay = document.querySelector(".nebula-mobile-overlay")
      sidebar.style.left = "-200px"
      overlay.style.left = "-200px"
      overlay.style.opacity = "0"
      sidebar.style.display = "block"
      overlay.style.display = "block"
      setTimeout(() => {
        overlay.style.opacity = ".7"
        sidebar.style.left = "0px"
        overlay.style.left = "0px"
        navPossible = true
      },50)
      mobileNavigationVisible=true
    }
  </script>
@endif