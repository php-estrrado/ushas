<div class="app-sidebar sidebar-shadow bg-slick-carbon sidebar-text-light">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>    
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                @if(getMenus() && count(getMenus()) > 0)
                    <li class="app-sidebar__heading"></li>
                    @foreach(getMenus() as $row)
                        @php $pt = (object)$row['parent']; $child = (object)$row['child']; @endphp
                        @if($pt->haveAccess) 
                            <li>
                                <a class="@if($pt->active == $menuGroup) mm-active @endif" @if($pt->haveChild)data-toggle="slide" @endif href="{{url($pt->link)}}" >
                                    <i class="{{$pt->class}}"></i>
                                    {{$pt->name}}
                                    @if($pt->haveChild)<i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>@endif
                                </a>
                                @if($pt->haveChild)
                                <ul class="@if($pt->active == $menuGroup) mm-show @endif">
                                    @foreach($child as $ch)
                                    @if($ch['haveAccess']) <li><a href="{{url($ch['link'])}}" class="@if($ch['active'] == $menu) mm-active @endif"><i class="metismenu-icon"></i>{{$ch['name']}}</a></li>@endif
                                    @endforeach
                                </ul>
                                @endif
                            </li>
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div> 