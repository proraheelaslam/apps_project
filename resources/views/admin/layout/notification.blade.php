<script>
    // Success Message -------------------------------------
    @if(Session::has('success'))
    toastr.success("{{Session::get('success')}}");
    @endif


    // Warning Message -------------------------------------
    @if(Session::has('warning'))
    toastr.warning("{{Session::get('warning')}}");
    @endif

    // Error Message -------------------------------------
    @if(Session::has('error'))
    toastr.error("{{Session::get('error')}}");
    @endif


</script>