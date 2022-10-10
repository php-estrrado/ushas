<style type="text/css">
    

.h-100vh.bg-primary {
    background-color: {{ config('settings.bg_primary_color') }}  !important;
    background-image: url("{{ URL::asset(config('settings.bg_primary_image')) }}");
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
}
#bgred{
    background-color:  {{ config('settings.dashboard_bg') }}  !important;
}
.side-menu__icon {
        color: {{ config('settings.menu_icon_color') }} !important;
    fill: {{ config('settings.menu_icon_color') }} !important;
}
.btn-secondary {
    background-color: {{ config('settings.btn_secondary') }} ;
    border-color: {{ config('settings.btn_secondary') }} ;
}
.page-item.active .page-link {
    background-color: {{ config('settings.active_link') }} !important;
    border-color: {{ config('settings.active_link') }} !important;
}
#back-to-top {
    background: {{ config('settings.back_to_top') }} !important;
}
.btn-primary {
    color: #fff !important;
    background-color: {{ config('settings.btn_primary') }} !important;
    border-color: {{ config('settings.btn_primary') }} !important;
    box-shadow: 0 0px 10px -5px rgb(112 94 200 / 50%);
}
.btn-info {
    color: #fff !important;
    background-color:  {{ config('settings.btn_info') }} !important;
    border-color:  {{ config('settings.btn_info') }} !important;
    box-shadow: 0 0px 10px -5px rgb(91 127 255 / 50%);
}
.app-header.header {
        background: {{ config('settings.dashboard_bg') }} !important;
}
.header-icon {
    color:  {{ config('settings.menu_icon_color') }} !important;
    fill:  {{ config('settings.menu_icon_color') }} !important;
}
.card-header::before {
    background-color:  {{ config('settings.menu_icon_color') }} !important;
}
.header-brand-img {
    height: 4.5rem !important;
}
.slide .slide-menu a.active, .slide.is-expanded .slide-menu li a:hover {
    color: {{ config('settings.menu_icon_color') }} !important;
}
.tabs-menu ul.nav li .active {
    background-color:{{ config('settings.menu_icon_color') }} !important;
}
input:checked~.switch-paddle {
    background:  {{ config('settings.menu_icon_color') }} !important;
}
.app-sidebar__logo {
    padding: 10px 15px !important;
    height: 90px !important;
}
 </style>