<!doctype html>
<html lang="en">
@include('layouts.header')

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        @include('layouts.sidebar')
        <!--  Main wrapper -->
        <div class="body-wrapper">
            @include('layouts.navbar')
            <div class="body-wrapper-inner">
                <div class="container-fluid">
                    @yield('content')
                    @include('layouts.footer')
                </div>
            </div>
        </div>
    </div>
</body>

</html>
