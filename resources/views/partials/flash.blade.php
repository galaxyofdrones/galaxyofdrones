@if (session()->has('_flash_manager'))
    <script type="text/javascript">
        Swal.fire({
            icon: '{{ session('_flash_manager.type') }}',
            title: '{{ session('_flash_manager.title') }}',
            text: '{{ session('_flash_manager.message') }}',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
@elseif (session()->has('resent'))
    <script type="text/javascript">
        Swal.fire({
            icon: 'success',
            title: '{{ __('messages.success.singular') }}',
            text: '{{ __('verification.sent') }}',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
@elseif (session()->has('status'))
    <script type="text/javascript">
        Swal.fire({
            icon: 'success',
            title: '{{ __('messages.success.singular') }}',
            text: '{{ session('status') }}',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
@elseif ($errors->any())
    <script type="text/javascript">
        Swal.fire({
            icon: 'error',
            title: '{{ __('messages.error.whoops') }}',
            text: '{{ __('messages.error.wrong') }}',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
@endif
