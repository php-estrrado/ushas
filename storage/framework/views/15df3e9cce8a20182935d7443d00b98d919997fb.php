
<?php $__env->startSection('css'); ?>
<style> #adminLogin .error{ color: #fff; }</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="page">
			<div class="page-content">
				<div class="container">
					<div class="row align-items-center justify-content-center">
						<div class="col-md-6">
							<div class="">
								<div class="text-white">
									<div class="card-body">
										<h2 class="display-4 mb-2 font-weight-bold error-text text-center"><strong><?php echo e(config('settings.app_name')); ?> Login</strong></h2>
										<h4 class="text-white-80 mb-7 text-center">Sign In to your account</h4>
                                                                                <form method="POST" id="adminLogin" action="<?php echo e(url('admin/login')); ?>">
                        <?php echo csrf_field(); ?>
										<div class="row">
											<div class="col-9 d-block mx-auto">
												<div class="input-group mb-4">
													<div class="input-group-prepend">
														<div class="input-group-text">
															<i class="fe fe-user"></i>
														</div>
													</div>
                                                                                                    <input type="email" name="email" class="form-control" placeholder="Email" required="">
                                                                                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                                                        <span class="invalid-feedback" role="alert">
                                                                                                            <strong><?php echo e($message); ?></strong>
                                                                                                        </span>
                                                                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                                                </div>
												<div class="input-group mb-4">
													<div class="input-group-prepend">
														<div class="input-group-text">
															<i class="fe fe-lock"></i>
														</div>
													</div>
                                                                                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required="">
                                                                                                    <span toggle="#password" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                                                                                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                                                        <span class="invalid-feedback" role="alert">
                                                                                                            <strong><?php echo e($message); ?></strong>
                                                                                                        </span>
                                                                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                                                    <div class="error tac fw" role="alert">
                                                                                                        <strong><?php echo e(Session::get('message')); ?></strong>
                                                                                                    </div>
                                                                                                </div>
                                                <div class="row">
												<div class="col-12">
												<label class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                                    <label class="custom-control-label" for="remember" >
                                                      <?php echo e(__('Remember Me')); ?>

                                                     </label>
												</label>
											  </div>
										     </div>
												<div class="row">
													<div class="col-12">
														<button type="submit" class="btn btn-secondary btn-block px-4" style="background:#fff; color:#ff0000 !important; padding:10px;">Login</button>
													</div>
													<div class="col-12 text-center">
														<a href="<?php echo e(url('/password/reset')); ?>" class="btn btn-link box-shadow-0 px-0 text-white-80">Forgot password?</a>
													</div>
												</div>
											</div>
										</div>
                                        </form>
									</div>
									<!--<div class="custom-btns text-center">-->
									<!--	<button class="btn btn-icon" type="button"><span class="btn-inner-icon"><i class="fa fa-facebook-f"></i></span></button>-->
									<!--	<button class="btn btn-icon" type="button"><span class="btn-inner-icon"><i class="fa fa-google"></i></span></button>-->
									<!--</div>-->
								</div>
							</div>
						</div>
						<!--<div class="col-md-6 d-none d-md-flex align-items-center">-->
						<!--	<img src="<?php echo e(URL::asset('admin/assets/images/png/login.png')); ?>" alt="img">-->
						<!--</div>-->
					</div>
				</div>
			</div>
        </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\ushas-dev\resources\views/admin/auth/login.blade.php ENDPATH**/ ?>