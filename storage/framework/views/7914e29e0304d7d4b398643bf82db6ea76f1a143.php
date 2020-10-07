<?php $__env->startSection('titulo', "Reiniciar Contraseña"); ?>

<?php $__env->startSection('content'); ?>
    <form method="POST" action="<?php echo e(route('password.update')); ?>" class="md-float-material">
        <div class="text-center">
            <img src="<?php echo e(asset('img/logo-blue.png')); ?>" alt="logo">
        </div>
        <h3 class="text-center txt-primary">Reiniciar contraseña</h3>
        <?php echo csrf_field(); ?>
        <input type="hidden" name="token" value="<?php echo e($token); ?>">
        <div class="md-input-wrapper">
            <input id="email" type="email" class="md-form-control <?php if ($errors->has('email')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('email'); ?> is-invalid <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>" name="email" value="<?php echo e($email ?? old('email')); ?>" required autocomplete="email" autofocus>
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
endif; ?>" name="password" required autocomplete="new-password">
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
        <div class="md-input-wrapper">
            <input id="password-confirm" type="password" class="md-form-control <?php if ($errors->has('password_confirmation')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('password_confirmation'); ?> is-invalid <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>" name="password_confirmation" required autocomplete="new-password">
                <?php if ($errors->has('password_confirmation')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('password_confirmation'); ?>
                    <span class="text-danger" role="alert">
                        <strong><?php echo e($message); ?></strong>
                    </span>
                <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>
            <label>Confirmar Contraseña</label>
        </div>
        <div class="row">
            <div class="col-xs-10 offset-xs-1">
                <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">REINICIAR</button>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.applogin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/kinchika/public_html/laravel/resources/views/auth/passwords/reset.blade.php ENDPATH**/ ?>