<?php 
    if(auth()->user()->avatar == NULL){ $avatar = url('storage/app/public/no-avatar.png'); }
    else{ $avatar = config('app.url').'/storage'.auth()->user()->avatar; }
    $sidebar = sidebarMenu();
    $version = appVersion('admin');
	
?>
<aside class="app-sidebar">
        <div class="app-sidebar__logo">
                <a class="header-brand" href="<?php echo e(url('/')); ?>" data-u="<?php echo e(config('app.url')); ?>">
                        <img src="<?php echo e(URL::asset(config('settings.logo'))); ?>" class="header-brand-img desktop-lgo" alt="Admintro logo">
                </a>
        </div>
        <div class="app-sidebar__user">
                <div class="dropdown user-pro-body text-center">
                        <div class="user-pic">
                                <img src="<?php echo e($avatar); ?>" alt="user-img" class="avatar-xl rounded-circle mb-1">
                        </div>
                        <div class="user-info">
                                <h5 class=" mb-1"><?php echo e(auth()->user()->fname.' '.auth()->user()->lname); ?> <i class="ion-checkmark-circled  text-success fs-12"></i></h5>
                                <span class="text-muted app-sidebar__user-name text-sm"><?php echo e(roleData()->usr_role_name); ?></span><br>
                                <span class="text-muted app-sidebar__user-name text-sm">v<?php echo e($version); ?></span>
                        </div> 
                </div>
                
        </div>
       <ul class="side-menu app-sidebar3">


                <?php $__currentLoopData = $sidebar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php  $pt = $row['parent'];  $child = $row['child']; ?> 
<?php if($pt['is_active'] !=1): ?> <?php $pr_class="pr_hide"; ?>  <?php else: ?> <?php $pr_class=""; ?>  <?php endif; ?>

                <li class="slide <?php echo e($pr_class); ?>">
                        <a class="side-menu__item <?php echo e($pt['class']); ?> " <?php if($child && count($child) > 0): ?>  data-toggle="slide" <?php endif; ?> href="<?php echo e(url($pt['link'])); ?>">
                                <?php if($pt['menu_icon'] !=""): ?>
                               <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"> <?php echo $pt['menu_icon']; ?> </svg>
                                <?php else: ?>
                                <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M16.66 4.52l2.83 2.83-2.83 2.83-2.83-2.83 2.83-2.83M9 5v4H5V5h4m10 10v4h-4v-4h4M9 15v4H5v-4h4m7.66-13.31L11 7.34 16.66 13l5.66-5.66-5.66-5.65zM11 3H3v8h8V3zm10 10h-8v8h8v-8zm-10 0H3v8h8v-8z"/></svg>
                                <?php endif; ?>
                        
                        <span class="side-menu__label"><?php echo e($pt['module_name']); ?></span>
                        <?php if($child && count($child) > 0): ?> 
                        <i class="angle fa fa-angle-right"></i></a>
                        <ul class="slide-menu">
                                <?php $__currentLoopData = $child; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
<?php if($ch['is_active'] !=1): ?>  <?php $ch_class="ch_hide"; ?> <?php else: ?>  <?php $ch_class=""; ?> <?php endif; ?>
                               <?php $menu_link = $ch['link']; 
                                ?>
                                <li class='<?php echo activeMenu(url("$menu_link")); ?> <?php echo e($ch_class); ?>'><a href='<?php echo url("$menu_link") ?>' class="slide-item"><?php echo e($ch['module_name']); ?></a></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <?php else: ?>
                        </a>
                        <?php endif; ?>
                </li>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                
        </ul>
</aside>

<?php

function activeMenu($uri = '') {
$active = '';

$cur_url = url()->current();

if($cur_url ==$uri ){
    $active = 'active';
}

return $active;
return $active;
}
?>
<!--aside closed--><?php /**PATH /home/qaushas/public_html/resources/views/includes/sidebar.blade.php ENDPATH**/ ?>