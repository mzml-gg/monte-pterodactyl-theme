<div class="sidebarContentContainer">
  <div class="sidebarContent">

    @if($n_sidebar_full == "1" && $n_sidebar_customlogo != "")
      <img class="customlogo" src="{{ $n_sidebar_customlogo }}" onclick="sidebarRefresh();sidebarButtonEvent('home')"></img>
    
      <!-- Item: Spacer -->
      <div class="sidebarSpacer"></div>
    @endif

    <!-- Item: Home -->
    <div class="tooltip-toggle">
      <span class="tooltip">Home</span>
      <button oncontextmenu="showContextMenu(event, `/`)" onclick="sidebarRefresh();sidebarButtonEvent('home')" id="sidebarMainHome" class="sidebarButton">
        @if($n_sidebar_home == "")
        <i class="sidebarIcon {{ $__home }}"></i>
        @else
        <img class="customicon" src="{{ $n_sidebar_home }}"></img>
        @endif
        @if($n_sidebar_full == "1")<span class="wideSidebarSpan">Home</span>@endif
      </button>
    </div>

    <!-- Item: Spacer -->
    <div class="sidebarSpacer"></div>
    
    <!-- Category: Server Management -->
    <div id="sidebarCategoryServer" class="sidebarCategory">

      <!-- Item: Terminal -->
      <div class="tooltip-toggle">
        <span class="tooltip">Terminal</span>
        <button oncontextmenu="showContextMenu(event, `/server/${fetchServerId()}`)" onclick="sidebarRefresh();sidebarButtonEvent('serverTerminal')" id="sidebarServerTerminal" class="sidebarButton">
          @if($n_sidebar_server_terminal == "")
          <i class="sidebarIcon {{ $__server_terminal }}"></i>
          @else
          <img class="customicon" src="{{ $n_sidebar_server_terminal }}"></img>
          @endif
          @if($n_sidebar_full == "1")<span class="wideSidebarSpan">Terminal</span>@endif
        </button>
      </div>
      
      <!-- Item: File Manager -->
      <div class="tooltip-toggle">
        <span class="tooltip">Files</span>
        <button oncontextmenu="showContextMenu(event, `/server/${fetchServerId()}/files`)" onclick="sidebarRefresh();sidebarButtonEvent('serverFiles')" id="sidebarServerFilemanager" class="sidebarButton">
          @if($n_sidebar_server_files == "")
          <i class="sidebarIcon {{ $__server_files }}"></i>
          @else
          <img class="customicon" src="{{ $n_sidebar_server_files }}"></img>
          @endif
          @if($n_sidebar_full == "1")<span class="wideSidebarSpan">Files</span>@endif
        </button>
      </div>
      
      <!-- Item: Databases -->
      <div class="tooltip-toggle">
        <span class="tooltip">Databases</span>
        <button oncontextmenu="showContextMenu(event, `/server/${fetchServerId()}/databases`)" onclick="sidebarRefresh();sidebarButtonEvent('serverDatabases')" id="sidebarServerDatabases" class="sidebarButton">
          @if($n_sidebar_server_databases == "")
          <i class="sidebarIcon {{ $__server_databases }}"></i>
          @else
          <img class="customicon" src="{{ $n_sidebar_server_databases }}"></img>
          @endif
          @if($n_sidebar_full == "1")<span class="wideSidebarSpan">Databases</span>@endif
        </button>
      </div>
      
      <!-- Item: Schedules -->
      <div class="tooltip-toggle">
        <span class="tooltip">Schedules</span>
        <button oncontextmenu="showContextMenu(event, `/server/${fetchServerId()}/schedules`)" onclick="sidebarRefresh();sidebarButtonEvent('serverSchedules')" id="sidebarServerSchedules" class="sidebarButton">
          @if($n_sidebar_server_schedules== "")
          <i class="sidebarIcon {{ $__server_schedules }}"></i>
          @else
          <img class="customicon" src="{{ $n_sidebar_server_schedules }}"></img>
          @endif
          @if($n_sidebar_full == "1")<span class="wideSidebarSpan">Schedules</span>@endif
        </button>
      </div>
      
      <!-- Item: Users -->
      <div class="tooltip-toggle">
        <span class="tooltip">Users</span>
        <button oncontextmenu="showContextMenu(event, `/server/${fetchServerId()}/users`)" onclick="sidebarRefresh();sidebarButtonEvent('serverUsers')" id="sidebarServerUsers" class="sidebarButton">
          @if($n_sidebar_server_users== "")
          <i class="sidebarIcon {{ $__server_users }}"></i>
          @else
          <img class="customicon" src="{{ $n_sidebar_server_users }}"></img>
          @endif
          @if($n_sidebar_full == "1")<span class="wideSidebarSpan">Users</span>@endif
        </button>
      </div>
      
      <!-- Item: Backups -->
      <div class="tooltip-toggle">
        <span class="tooltip">Backups</span>
        <button oncontextmenu="showContextMenu(event, `/server/${fetchServerId()}/backups`)" onclick="sidebarRefresh();sidebarButtonEvent('serverBackups')" id="sidebarServerBackups" class="sidebarButton">
          @if($n_sidebar_server_backups== "")
          <i class="sidebarIcon {{ $__server_backups }}"></i>
          @else
          <img class="customicon" src="{{ $n_sidebar_server_backups }}"></img>
          @endif
          @if($n_sidebar_full == "1")<span class="wideSidebarSpan">Backups</span>@endif
        </button>
      </div>
      
      <!-- Item: Network -->
      <div class="tooltip-toggle">
        <span class="tooltip">Network</span>
        <button oncontextmenu="showContextMenu(event, `/server/${fetchServerId()}/network`)" onclick="sidebarRefresh();sidebarButtonEvent('serverNetwork')" id="sidebarServerNetwork" class="sidebarButton">
          @if($n_sidebar_server_network== "")
          <i class="sidebarIcon {{ $__server_network }}"></i>
          @else
          <img class="customicon" src="{{ $n_sidebar_server_network }}"></img>
          @endif
          @if($n_sidebar_full == "1")<span class="wideSidebarSpan">Network</span>@endif
        </button>
      </div>
      
      <!-- Item: Startup -->
      <div class="tooltip-toggle">
        <span class="tooltip">Startup</span>
        <button oncontextmenu="showContextMenu(event, `/server/${fetchServerId()}/startup`)" onclick="sidebarRefresh();sidebarButtonEvent('serverStartup')" id="sidebarServerStartup" class="sidebarButton">
          @if($n_sidebar_server_startup== "")
          <i class="sidebarIcon {{ $__server_startup }}"></i>
          @else
          <img class="customicon" src="{{ $n_sidebar_server_startup }}"></img>
          @endif
          @if($n_sidebar_full == "1")<span class="wideSidebarSpan">Startup</span>@endif
        </button>
      </div>
      
      <!-- Item: Settings -->
      <div class="tooltip-toggle">
        <span class="tooltip">Settings</span>
        <button oncontextmenu="showContextMenu(event, `/server/${fetchServerId()}/settings`)" onclick="sidebarRefresh();sidebarButtonEvent('serverSettings')" id="sidebarServerSettings" class="sidebarButton">
          @if($n_sidebar_server_settings== "")
          <i class="sidebarIcon {{ $__server_settings }}"></i>
          @else
          <img class="customicon" src="{{ $n_sidebar_server_settings }}"></img>
          @endif
          @if($n_sidebar_full == "1")<span class="wideSidebarSpan">Settings</span>@endif
        </button>
      </div>
      
      <!-- Item: Activity -->
      <div class="tooltip-toggle">
        <span class="tooltip">Activity</span>
        <button oncontextmenu="showContextMenu(event, `/server/${fetchServerId()}/activity`)" onclick="sidebarRefresh();sidebarButtonEvent('serverActivity')" id="sidebarServerActivity" class="sidebarButton">
          @if($n_sidebar_server_activity== "")
          <i class="sidebarIcon {{ $__server_activity }}"></i>
          @else
          <img class="customicon" src="{{ $n_sidebar_server_activity }}"></img>
          @endif
          @if($n_sidebar_full == "1")<span class="wideSidebarSpan">Activity</span>@endif
        </button>
      </div>

      @if($n_page_indexing == "1")
      <!-- Item: More -->
      <div class="tooltip-toggle">
        <span class="tooltip">More</span>
        <button onclick="showContextMenu(event, undefined, 'more')" style="display:none" id="sidebarServerMore" class="sidebarButton">
          @if($n_sidebar_server_more== "")
          <i class="sidebarIcon {{ $__server_more }}"></i>
          @else
          <img class="customicon" src="{{ $n_sidebar_server_more }}"></img>
          @endif
          @if($n_sidebar_full == "1")<span class="wideSidebarSpan">More</span>@endif
        </button>
      </div>
      @endif
      
      @if($n_sidebar_always_visible_buttons == "1")
      <!-- Item: Spacer -->
      <div class="sidebarSpacer"></div>
      @endif
      
    </div>
    
    <!-- Category: Account -->
    <div id="sidebarCategoryAccount" class="sidebarCategory">
      
      <!-- Item: Account -->
      <div class="tooltip-toggle">
        <span class="tooltip">Account</span>
        <button oncontextmenu="showContextMenu(event, `/account`)" onclick="sidebarRefresh();sidebarButtonEvent('accountAccount')" id="sidebarAccountAccount" class="sidebarButton">
          @if($n_sidebar_account_account== "")
          <i class="sidebarIcon {{ $__account_account }}"></i>
          @else
          <img class="customicon" src="{{ $n_sidebar_account_account }}"></img>
          @endif
          @if($n_sidebar_full == "1")<span class="wideSidebarSpan">Account</span>@endif
        </button>
      </div>
      
      <!-- Item: API Credentials -->
      <div class="tooltip-toggle">
        <span class="tooltip">API</span>
        <button oncontextmenu="showContextMenu(event, `/account/api`)" onclick="sidebarRefresh();sidebarButtonEvent('accountApi')" id="sidebarAccountApi" class="sidebarButton">
          @if($n_sidebar_account_api== "")
          <i class="sidebarIcon {{ $__account_api }}"></i>
          @else
          <img class="customicon" src="{{ $n_sidebar_account_api }}"></img>
          @endif
          @if($n_sidebar_full == "1")<span class="wideSidebarSpan">API</span>@endif
        </button>
      </div>
      
      <!-- Item: SSH Keys -->
      <div class="tooltip-toggle">
        <span class="tooltip">SSH</span>
        <button oncontextmenu="showContextMenu(event, `/account/ssh`)" onclick="sidebarRefresh();sidebarButtonEvent('accountSsh')" id="sidebarAccountSsh" class="sidebarButton">
          @if($n_sidebar_account_ssh== "")
          <i class="sidebarIcon {{ $__account_ssh }}"></i>
          @else
          <img class="customicon" src="{{ $n_sidebar_account_ssh }}"></img>
          @endif
          @if($n_sidebar_full == "1")<span class="wideSidebarSpan">SSH Keys</span>@endif
        </button>
      </div>
      
      <!-- Item: Activity -->
      <div class="tooltip-toggle">
        <span class="tooltip">Activity</span>
        <button oncontextmenu="showContextMenu(event, `/account/activity`)" onclick="sidebarRefresh();sidebarButtonEvent('accountActivity')" id="sidebarAccountActivity" class="sidebarButton">
          @if($n_sidebar_account_activity== "")
          <i class="sidebarIcon {{ $__account_activity }}"></i>
          @else
          <img class="customicon" src="{{ $n_sidebar_account_activity }}"></img>
          @endif
          @if($n_sidebar_full == "1")<span class="wideSidebarSpan">Activity</span>@endif
        </button>
      </div>

      @if($n_page_indexing == "1")
      <!-- Item: More -->
      <div class="tooltip-toggle">
        <span class="tooltip">More</span>
        <button onclick="showContextMenu(event, undefined, 'more')" style="display:none" id="sidebarAccountMore" class="sidebarButton">
          @if($n_sidebar_account_more== "")
          <i class="sidebarIcon {{ $__account_more }}"></i>
          @else
          <img class="customicon" src="{{ $n_sidebar_account_more }}"></img>
          @endif
          @if($n_sidebar_full == "1")<span class="wideSidebarSpan">More</span>@endif
        </button>
      </div>
      @endif
      
      @if($n_sidebar_always_visible_buttons == "1")
      <!-- Item: Spacer -->
      <div class="sidebarSpacer"></div>
      @endif
      
    </div>

    <!-- Category: General -->
    <div id="sidebarCategoryGeneral">

      @if(Auth::user()->root_admin == 1)
      <!-- Item: Configuration -->
      <a href="/admin">
        <div class="tooltip-toggle">
          <span class="tooltip">Admin</span>
          <button oncontextmenu="showContextMenu(event, `/admin`)" onclick="sidebarRefresh();sidebarButtonEvent('admin')" id="sidebarMainConfiguration" class="sidebarButton">
            @if($n_sidebar_admin== "")
            <i class="sidebarIcon {{ $__admin }}"></i>
            @else
            <img class="customicon" src="{{ $n_sidebar_admin }}"></img>
            @endif
            @if($n_sidebar_full == "1")<span class="wideSidebarSpan">Admin</span>@endif
          </button>
        </div>
      </a>
      @endif
      
      <!-- Item: Account -->
      <div class="tooltip-toggle">
        <span class="tooltip">Account</span>
        <button oncontextmenu="showContextMenu(event, `/account`)" onclick="sidebarRefresh();sidebarButtonEvent('accountAccount')" id="sidebarMainAccount" class="sidebarButton">
          @if($n_sidebar_account== "")
          <i class="sidebarIcon {{ $__account }}"></i>
          @else
          <img class="customicon" src="{{ $n_sidebar_account }}"></img>
          @endif
          @if($n_sidebar_full == "1")<span class="wideSidebarSpan">Account</span>@endif
        </button>
      </div>
      
      <!-- Item: Log out -->
      <div class="tooltip-toggle">
        <span class="tooltip">Logout</span>
        <button oncontextmenu="return false;" onclick="sidebarRefresh();sidebarButtonEvent('logout')" id="sidebarMainLogout" class="sidebarButton">
          @if($n_sidebar_logout== "")
          <i class="sidebarIcon {{ $__logout }}"></i>
          @else
          <img class="customicon" src="{{ $n_sidebar_logout }}"></img>
          @endif
          @if($n_sidebar_full == "1")<span class="wideSidebarSpan">Logout</span>@endif
        </button>
      </div>
    
    </div> 
    
  </div>
</div>