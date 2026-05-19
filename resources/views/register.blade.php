<!DOCTYPE html>
<html lang="en">

@include('components.register.head')

<body>

@include('components.register.navbar')

<section class="contact">
    @include('components.register.form-register')
</section>

@include('components.register.footer')

<!-- JS -->
<script src="{{ asset('js/register/password-toggle.js') }}"></script>
<script src="{{ asset('js/register/kota-dropdown.js') }}"></script>
<script src="{{ asset('js/register/role-handler.js') }}"></script>
<script src="{{ asset('js/register/sekolah-dropdown.js') }}"></script>
<script src="{{ asset('js/register/guru-studi.js') }}"></script>
<script src="{{ asset('js/register/sesi-kegiatan.js') }}"></script>

</body>
</html>