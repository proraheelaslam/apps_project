<script>
    // Success Message -------------------------------------
    <?php if(Session::has('success')): ?>
    toastr.success("<?php echo e(Session::get('success')); ?>");
    <?php endif; ?>


    // Warning Message -------------------------------------
    <?php if(Session::has('warning')): ?>
    toastr.warning("<?php echo e(Session::get('warning')); ?>");
    <?php endif; ?>

    // Error Message -------------------------------------
    <?php if(Session::has('error')): ?>
    toastr.error("<?php echo e(Session::get('error')); ?>");
    <?php endif; ?>


</script>