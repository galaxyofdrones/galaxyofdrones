@if (session()->has('_flash_manager'))
    <script type="text/javascript">
        Swal.fire({
            title: '{{ session('_flash_manager.title') }}',
            text: '{{ session('_flash_manager.message') }}',
            type: '{{ session('_flash_manager.type') }}',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
@elseif (session()->has('resent'))
    <script type="text/javascript">
        Swal.fire({
            title: '{{ trans('messages.success.singular') }}',
            text: '{{ trans('verification.sent') }}',
            type: 'success',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
@elseif (session()->has('status'))
    <script type="text/javascript">
        Swal.fire({
            title: '{{ trans('messages.success.singular') }}',
            text: '{{ session('status') }}',
            type: 'success',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
@elseif ($errors->any())
    <script type="text/javascript">
        Swal.fire({
            title: '{{ trans('messages.error.whoops') }}',
            text: '{{ trans('messages.error.wrong') }}',
            type: 'error',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
@endif
