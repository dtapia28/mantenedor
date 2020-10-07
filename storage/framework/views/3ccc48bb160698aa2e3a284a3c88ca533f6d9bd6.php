<?php $__env->startSection('titulo', "Crear Cuenta"); ?>
<?php
$nombre = Illuminate\Support\Facades\Cache::get('nombre');
$apellido = Illuminate\Support\Facades\Cache::get('apellido');
$mail = Illuminate\Support\Facades\Cache::get('mail');

?>

<?php $__env->startSection('content'); ?>
<form method="POST" action="<?php echo e(route('register')); ?>" class="md-float-material">
    <div class="text-center">
        <img src="<?php echo e(asset('img/logo-blue.png')); ?>" alt="logo">
    </div>
    <h4 class="text-center txt-primary"> Crear cuenta</h4>
    <?php echo csrf_field(); ?>
    <div class="md-input-wrapper">
        <input id="name" type="text" class="md-form-control <?php if ($errors->has('name')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('name'); ?> is-invalid <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>" name="name" value="<?php echo e($nombre); ?>" required autocomplete="name" autofocus>
        <?php if ($errors->has('name')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('name'); ?>
            <span class="text-danger" role="alert">
                <strong><?php echo e($message); ?></strong>
            </span>
        <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>
        <label>Nombre</label>
    </div>
    <div class="md-input-wrapper">
        <input id="lastname" type="text" class="md-form-control <?php if ($errors->has('name')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('name'); ?> is-invalid <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>" name="lastname" value="<?php echo e($apellido); ?>" required autocomplete="name" autofocus>
        <?php if ($errors->has('name')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('name'); ?>
            <span class="text-danger" role="alert">
                <strong><?php echo e($message); ?></strong>
            </span>
        <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>
        <label>Apellido</label>
    </div>    
    <div class="md-input-wrapper">
        <input id="email" type="email" class="md-form-control <?php if ($errors->has('email')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('email'); ?> is-invalid <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>" name="email" value="<?php echo e($mail); ?>" required autocomplete="email">
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
        <input id="rut" type="text" class="md-form-control <?php if ($errors->has('rut')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('rut'); ?> no es valido <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>" name="rut" value="<?php echo e(old('rut')); ?>" required maxlength="12" max="12">
        <label>Rut Empresa</label>
    </div>
    <div class="md-input-wrapper">
        <input id="nombre" type="text" class="md-form-control <?php if ($errors->has('nombre')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('nombre'); ?> no es valido <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>" name="nombre" value="<?php echo e(old('nombre')); ?>">
        <label>Nombre Empresa</label>
    </div>
    <div class="md-input-wrapper">
        <input id="n_telefono" type="text" title="Si es que desea que el sistema le pueda enviar mensajes via WhatsApp." class="md-form-control <?php if ($errors->has('n_telefono')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('n_telefono'); ?> no es valido <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>" name="n_telefono" value="<?php echo e(old('n_telefono')); ?>">
        <label>N&#250mero Tel&#233fono</label>
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
        <label>Confirmar Contraseña</label>
    </div>
    <div class="row">
        <div class="col-xs-10 offset-xs-1">
            <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">REGISTRAR</button>
        </div>
    </div>
    <div class="row text-center">
        <a href="<?php echo e(route('login')); ?>" class="text-right f-w-600"><i class="fa fa-arrow-left"></i> Volver al login</a>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="<?php echo e(asset('js/jquery.mask.js')); ?>" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            $('#rut').mask('00000000-A', {reverse: true, 'translation': {A: {pattern: /[0-9Kk]/}}});
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.applogin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/kinchika/public_html/laravel/resources/views/auth/register.blade.php ENDPATH**/ ?>