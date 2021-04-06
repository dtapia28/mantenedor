<?php $__env->startSection('titulo', "Reiniciar Contraseña"); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('status')): ?>
    <div class="alert alert-success" role="alert">
        <?php echo e(session('status')); ?>

    </div>
<?php endif; ?>
<form method="POST" action="<?php echo e(route('password.email')); ?>" class="md-float-material">
    <div class="text-center">
        <img src="<?php echo e(asset('img/logo-blue.png')); ?>" alt="logo">
    </div>
    <h3 class="text-center txt-primary">Olvidé mi contraseña</h3>
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
    <div class="row">
        <div class="col-xs-10 offset-xs-1">
            <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">REINICIAR</button>
        </div>
    </div>
    <div class="row text-center">
        <a href="<?php echo e(route('login')); ?>" class="text-right f-w-600"><i class="fa fa-arrow-left"></i> Volver al login</a>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.applogin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/auth/passwords/email.blade.php ENDPATH**/ ?>