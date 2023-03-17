
    
   <?php $__env->startSection('page-header'); ?>
    <?php
        $user = auth()->user();
        $c_code =  getDropdownData(DB::table('countries')->where('is_deleted',0)->get(),'id','phonecode');
        if($user->avatar == NULL){ $avatar = url('storage/app/public/no-avatar.png'); }
        else{ $avatar = config('app.url').'/storage'.auth()->user()->avatar; }
    ?>
<!--Page header-->
<div class="page-header">
    <div class="page-leftheader"><h4 class="page-title mb-0">Profile</h4></div>
</div>
<!--End Page header-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<!-- Row -->
<div class="row">
    <div class="col-xl-3 col-lg-3">
        <div class="card box-widget widget-user ">
            <div class="widget-user-image mx-auto mt-5"><img alt="User Avatar" class="rounded-circle" src="<?php echo e($avatar); ?>"></div>
            <div class="card-body text-center pt-2">
                <div class="pro-user">
                    <h3 class="pro-user-username text-dark mb-1 fs-22"><?php echo e($user->fname.' '.$user->lname); ?></h3>
                    <h6 class="pro-user-desc text-muted"><?php echo e(roleData()->usr_role_name); ?></h6>
                    <h6 class="pro-user-desc text-muted"><i class="fa fa-envelope mr-2" aria-hidden="true"></i><?php echo e($user->email); ?></h6>
                    <h6 class="pro-user-desc text-muted"><i class="fa fa-phone mr-2" aria-hidden="true"></i> <?php if(auth()->user()->isd_code): ?> <?php echo e(auth()->user()->isd_code); ?> <?php endif; ?> <?php echo e($user->phone); ?></h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-6">
        <div class="card">
            <div class="card-header"><div class="card-title">Edit Profile</div></div>
            <?php echo e(Form::open(['url' => "admin/profile/update", 'id' => 'userForm', 'name' => 'userForm', 'class' => '','files'=>'true', 'novalidate'])); ?>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <?php echo e(Form::label('fname','First Name',['class'=>'form-label'])); ?>

                            <?php echo e(Form::text('profile[fname]',$user->fname,['id'=>'fname','class'=>'form-control','placeholder'=>'First Name'])); ?>

                            <span class="error"></span>
                        </div>
                        <div class="col-12 mb-2">
                            <?php echo e(Form::label('lname','Last Name',['class'=>'form-label'])); ?>

                            <?php echo e(Form::text('profile[lname]',$user->lname,['id'=>'lname','class'=>'form-control','placeholder'=>'Last Name'])); ?>

                            <span class="error"></span>
                        </div>
                        <div class="col-12 mb-2">
                            <?php echo e(Form::label('email','Email',['class'=>'form-label'])); ?>

                            <?php echo e(Form::text('profile[email]',$user->email,['id'=>'email','class'=>'form-control','placeholder'=>'Email'])); ?>

                            <span class="error"></span>
                        </div>
                        <div class="col-12 mb-2">
                            <?php echo e(Form::label('phone','Phone',['class'=>'form-label'])); ?>

                            <div class="row">
                            <div class="col-3 pr-0">
                            <select id="isd_code" name="profile[isd_code]" class="form-control p-1" >
                            <?php if($c_code): ?> <?php $__currentLoopData = $c_code; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row=>$isd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                            <?php if($isd == auth()->user()->isd_code){ $selected = 'selected'; }else{ $selected = ''; } ?>

                            <option value="<?php echo e($isd); ?>" <?php echo e($selected); ?>>+<?php echo e($isd); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?>
                            </select>
                            </div>
                            <div class="col-9 pl-0">
                            <input type="text" class="form-control" name="profile[phone]" id="phone" placeholder="Phone" value="<?php echo e($user->phone); ?>" required>
                            <span class="error"></span>
                            </div>
                        </div>

                        </div>
                        <div class="col-md-12 mb-2">
                        <label for="avatar">Avatar</label>
                        <?php echo e(Form::file('avatar',['id'=>'avatar','class'=>'form-control', 'accept'=>"image/*"])); ?>

                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <?php echo e(Form::hidden('can_submit',0,['id'=>'can_submit'])); ?>

                    
                    <?php echo e(Form::submit('Update',['id'=>'save_btn', 'class'=>'btn  btn-primary'])); ?>

                    
                    <a href="<?php echo e(url('/')); ?>"  class="btn  btn-danger" >Cancel</a> 
                </div>
            <?php echo e(Form::close()); ?>

        </div>
    </div>
    <div class="col-xl-3 col-lg-3">
        <div class="card">
            <div class="card-header"><div class="card-title">Change Password</div></div>
            <?php echo e(Form::open(['url' => "admin/change/password", 'id' => 'passForm', 'name' => 'passForm', 'class' => '','files'=>'true', 'novalidate'])); ?>

                <div class="card-body row">
                    <div class="col-12 mb-2">
                        <?php echo e(Form::label('curr_password','Current Password',['class'=>'form-label'])); ?>

                        <?php echo e(Form::password('curr_password',['id'=>'curr_password','class'=>'form-control pwd'])); ?>

                        <span class="error"></span>
                    </div>
                    <div class="col-12 mb-2">
                        <?php echo e(Form::label('password','New Password',['class'=>'form-label'])); ?>

                        <?php echo e(Form::password('password',['id'=>'password','class'=>'form-control pwd','data-strength'])); ?>

                        <span class="error"></span>
                    </div>
                    <div class="col-12 mb-2">
                        <?php echo e(Form::label('password_confirmation','Confirm Password',['class'=>'form-label'])); ?>

                        <?php echo e(Form::password('password_confirmation',['id'=>'password_confirmation','class'=>'form-control pwd'])); ?>

                        <span class="error"></span>
                    </div>
                    <div class="col-12 mb-2 text-right">
                        <?php echo e(Form::hidden('can_submit_pass',0,['id'=>'can_submit_pass'])); ?>

                        <?php echo e(Form::submit('Update',['id'=>'save_pass', 'class'=>'btn  btn-primary'])); ?>

                    </div>
                <?php echo e(Form::close()); ?>

            </div>
        </div>
    </div>
</div>
<!-- End Row-->
<?php $__env->startSection('js'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#userForm').on('submit',function(){ 
            if($('#can_submit').val() > 0){ return true; }
            else{ 
                var form = $(this); 
                $('#save_btn').attr('disabled',true); $('#save_btn').text('Validating...');
                $.ajax({
                    type: "POST",
                    url: '<?php echo e(url("admin/validate/profile")); ?>',
                    data: form.serialize(),
                    success: function (data) {
                        if(data == 'success'){
                            $('#save_btn').text('Updating...'); $('#can_submit').val(1); $('#userForm').submit();
                        }else{
                            var errKey = ''; var n = 0;
                            $.each(data, function(key,value) { if(n == 0){ errKey = key; n++; }
                                $('#'+key).closest('div').find('.error').html(value);
                            }); 
                            $('#'+errKey).focus();
                            $('#save_btn').attr('disabled',false); $('#save_btn').text('Save'); return false;
                        }
                        return false;
                    }
                });
            }
          return false; 
        });
        
        $('body').on('submit','#passForm',function(e){ 
            $('#passForm .error').html('');
            e.preventDefault();    
            var formData = new FormData(this);
            $('#passForm #save_pass').attr('disabled',true); $('#passForm #save_pass').text('Validating...'); 
            $.ajax({
                type: "POST",
                url: '<?php echo e(url("admin/password/validate")); ?>',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if(data == 'success'){ 
                        $('#passForm #save_pass').text('Updating...'); $('#passForm #cancel_btn').trigger('click'); submitForm(formData); return false;
                     //    $('#sizeForm #can_submit').val(1); submitForm());
                    }else{
                        var errKey = ''; var n = 0;
                        $.each(data, function(key,value) { if(n == 0){ errKey = key; n++; }
                            $('#passForm #'+key).closest('div').find('.error').html(value);
                        }); 
                        $('#passForm #'+errKey).focus();
                        $('#passForm #save_pass').attr('disabled',false); $('#passForm #save_pass').text('Update'); return false;
                    }
                    return false;
                }
            });
        });
        
        <?php if(Session::has('success')): ?> toastr.success("<?php echo e(Session::get('success')); ?>"); <?php endif; ?>
    });
    
    function submitForm(postValues){
        $.ajax({
            type: "POST", 
            url: '<?php echo e(url("admin/change/password")); ?>',
            data: postValues, cache: false, contentType: false, processData: false,
            success: function (data) { 
              $('#passForm #save_pass').attr('disabled',false); $('#passForm #save_pass').text('Update'); $('#passForm')[0].reset();
              if(data == 'success'){ toastr.success("Password changed successfully!"); }else if(data == 'error'){ toastr.error("Somthing went wrong, please try again later"); }
              
            } 
        });

    }
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/profile.blade.php ENDPATH**/ ?>