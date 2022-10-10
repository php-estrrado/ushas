@php 
    if(auth()->user()->avatar == NULL){ $avatar = url('storage/app/public/no-avatar.png'); }
    else{ $avatar = url('storage'.auth()->user()->avatar); }
    $sidebar = sidebarMenu();
    $version = appVersion('admin');
	
@endphp
<aside class="app-sidebar">
        <div class="app-sidebar__logo">
                <a class="header-brand" href="{{url('/')}}">
                        <img src="{{URL::asset(config('settings.logo'))}}" class="header-brand-img desktop-lgo" alt="Admintro logo">
                </a>
        </div>
        <div class="app-sidebar__user">
                <div class="dropdown user-pro-body text-center">
                        <div class="user-pic">
                                <img src="{{$avatar}}" alt="user-img" class="avatar-xl rounded-circle mb-1">
                        </div>
                        <div class="user-info">
                                <h5 class=" mb-1">{{auth()->user()->fname.' '.auth()->user()->lname}} <i class="ion-checkmark-circled  text-success fs-12"></i></h5>
                                <span class="text-muted app-sidebar__user-name text-sm">{{roleData()->usr_role_name}}</span><br>
                                <span class="text-muted app-sidebar__user-name text-sm">v{{ $version }}</span>
                        </div> 
                </div>
                
        </div>
       <ul class="side-menu app-sidebar3">


                @foreach($sidebar as $row)
@php  $pt = $row['parent'];  $child = $row['child']; @endphp 
@if($pt['is_active'] !=1) @php $pr_class="pr_hide"; @endphp  @else @php $pr_class=""; @endphp  @endif

                <li class="slide {{ $pr_class }}">
                        <a class="side-menu__item {{ $pt['class'] }} " @if($child && count($child) > 0)  data-toggle="slide" @endif href="{{ url($pt['link']) }}">
                                @if($pt['menu_icon'] !="")
                               <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"> <?php echo $pt['menu_icon']; ?> </svg>
                                @else
                                <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M16.66 4.52l2.83 2.83-2.83 2.83-2.83-2.83 2.83-2.83M9 5v4H5V5h4m10 10v4h-4v-4h4M9 15v4H5v-4h4m7.66-13.31L11 7.34 16.66 13l5.66-5.66-5.66-5.65zM11 3H3v8h8V3zm10 10h-8v8h8v-8zm-10 0H3v8h8v-8z"/></svg>
                                @endif
                        
                        <span class="side-menu__label">{{$pt['module_name']}}</span>
                        @if($child && count($child) > 0) 
                        <i class="angle fa fa-angle-right"></i></a>
                        <ul class="slide-menu">
                                @foreach($child as $ch) 
@if($ch['is_active'] !=1)  @php $ch_class="ch_hide"; @endphp @else  @php $ch_class=""; @endphp @endif
                               <?php $menu_link = $ch['link']; 
                                ?>
                                <li class='<?php echo activeMenu(url("$menu_link")); ?> {{ $ch_class }}'><a href='<?php echo url("$menu_link") ?>' class="slide-item">{{$ch['module_name']}}</a></li>
                                @endforeach
                        </ul>
                        @else
                        </a>
                        @endif
                </li>

                @endforeach

                
                
        </ul>
</aside>

@php

function activeMenu($uri = '') {
$active = '';

$cur_url = url()->current();

if($cur_url ==$uri ){
    $active = 'active';
}

return $active;
return $active;
}
@endphp
<!--aside closed-->