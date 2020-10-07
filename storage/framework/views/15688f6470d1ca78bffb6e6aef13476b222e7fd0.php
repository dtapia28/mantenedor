<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('titulo'); ?> - Kinchika</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">

    <!-- Styles -->
    <link href="<?php echo e(asset('vendor/font-awesome/css/font-awesome.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('css/login/icofont.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('css/login/bootstrap.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('css/login/main.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('css/login/responsive.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('css/login/color-1.css')); ?>" rel="stylesheet" />
</head>
<body>
    <section class="login p-fixed d-flex text-center bg-primary common-img-bg">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="login-card card-block">
                        <?php echo $__env->yieldContent('content'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="<?php echo e(asset('vendor/jquery/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/login/jquery-ui.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/login/tether.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/login/popper.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/bootstrap/dist/js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/login/waves.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/login/elements.js')); ?>"></script>
    <?php echo $__env->yieldContent('js'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/layouts/applogin.blade.php ENDPATH**/ ?>