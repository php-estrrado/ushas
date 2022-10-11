
<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="page">
			<div class="page-single">
				<div class="container">
					<div class="row">
						<div class="col mx-auto">
							<div class="row justify-content-center">
								<div class="col-md-5">
									<div class="card">
										<div class="card-body">
											<div class="text-center mb-4 ">
												
											</div>
											<span class="m-4 d-none d-lg-block text-center">
												<span class="fs-20"><strong>Site Config</strong></span>
											</span>
											<?php echo e(Form::open(['url' => "admin/unlock-password", 'id' => 'userForm', 'name' => 'userForm', 'class' => '','files'=>'true', 'novalidate'])); ?>

											<div class="form-group">
												<input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
												<?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
												<p style="color: red"><?php echo e($message); ?></p>
												<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
											</div>
												<?php $__errorArgs = ['error'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
												<p style="color: red"><?php echo e($message); ?></p>
												<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
											<button  class="btn btn-primary btn-block"><i class="fe fe-lock"></i> Unlock</button>
										</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master4', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\ushas-dev\resources\views/lockscreen.blade.php ENDPATH**/ ?>