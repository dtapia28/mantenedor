<?php $__env->startSection('titulo', "Login"); ?>

<?php $__env->startSection('content'); ?>
    <form method="POST" action="<?php echo e(route('login')); ?>" class="md-float-material">
        <div class="text-center">
            <img src="<?php echo e(asset('img/logo-blue.png')); ?>" alt="logo">
        </div>
        <h3 class="text-center txt-primary">Ingresa a tu cuenta</h3>
        <?php echo csrf_field(); ?>
        <div class="md-input-wrapper">
            <input id="email" type="email" class="md-form-control <?php if ($errors->has('email')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('email'); ?> is-invalid <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email">
                <?php if ($errors->has('email')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('email'); ?>
                    <span class="text-danger" role="alert">
                        <strong><?php echo e($message); ?></strong>
                    </span>
                <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>
            <label>Correo electrónico</label>
        </div>
        <div class="md-input-wrapper">
            <input id="password" type="password" class="md-form-control <?php if ($errors->has('password')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('password'); ?> is-invalid <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>" name="password" required autocomplete="current-password">
                <?php if ($errors->has('password')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('password'); ?>
                    <span class="text-danger" role="alert">
                        <strong><?php echo e($message); ?></strong>
                    </span>
                <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>
            <label>Contraseña</label>
        </div>
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <div class="rkmd-checkbox checkbox-rotate checkbox-ripple m-b-25">
                    <label class="input-checkbox checkbox-primary">
                        <input type="checkbox" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                        <span class="checkbox"></span>
                    </label>
                    <div class="captions">Recordarme</div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12 forgot-phone text-right">
                <?php if(Route::has('password.request')): ?>
                    <a href="<?php echo e(route('password.request')); ?>" class="text-right f-w-600">Olvidé mi contraseña</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-10 offset-xs-1">
                <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">INGRESAR <i class="fa-signin"></i></button>
            <a href="<?php echo e(url('/redirect/')); ?>" class="btn btn-default btn-block waves-effect text-center m-b-20"><img src="<?php echo e(asset('img/logo-google.png')); ?>" alt="logo-google"> Ingresa con tu cuenta de Google</a>
            </div>
        </div>
        <?php if(Route::has('register')): ?>
            <div class="col-sm-12 col-xs-12 text-center">
                <span class="text-muted">¿No estas registrado?</span>
                <a href="<?php echo e(route('register')); ?>" class="f-w-600 p-l-5">Crea un cuenta aquí</a>
            </div>
        <?php endif; ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.applogin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/auth/login.blade.php ENDPATH**/ ?>