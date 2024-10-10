<!DOCTYPE html>
<html lang="zxx">
   <head>
      <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->
      <meta charset="utf-8">
	  <meta http-equiv="x-ua-compatible" content="IE=edge">
      {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	  <meta name="description" content="A system designed to simplify the school management process ">
	  <meta name="keyword" content="School Management System">
	  <meta name="author"  content="Tarick Abdul"/>
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <!-- Page Title -->
      <title>ACS</title>
      <!-- Main CSS -->
      <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"/>
      <link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css') }} " rel="stylesheet"/>
      <link href="{{ asset('assets/plugins/simple-line-icons/css/simple-line-icons.css') }}" rel="stylesheet">
      <link href="{{ asset('assets/plugins/ionicons/css/ionicons.css') }}" rel="stylesheet">
      <link href="{{ asset('assets/plugins/spinkit/spinkit.min.css') }}" rel="stylesheet">
      <link href="{{ asset('assets/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
      {{-- <link href="{{ asset('assets/plugins/chartist/chartist.css') }}" rel="stylesheet"> --}}
      <link href="{{ asset('assets/plugins/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
      <link href="{{ asset('assets/css/skin-turquoise.css') }}" rel="stylesheet" id="style-colors">
      <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet"/>
      <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet"/>
      {{-- <link rel="stylesheet" href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css') }}"> --}}
      <link rel="stylesheet" href="{{  asset('assets/plugins/f6/css/all.min.css') }}">
      <link href="{{ asset('assets/plugins/datatables/extensions/dataTables.jqueryui.min.css') }}" rel="stylesheet">
      <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/responsive/dataTables.responsive.js') }}">
      <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
      <link href="{{ asset('assets/plugins/steps/jquery.steps.css') }}" rel="stylesheet">

      <link href="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">

      <!-- Favicon -->
      {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css"> --}}
      <link href="{{ asset('assets/plugins/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
      <link rel="icon" sty href="{{ asset('assets/icons/sbrt-logo.ico') }}" type="image/x-icon">

      <link rel="stylesheet" href="{{ asset('select2s/dist/css/select2.min.css') }}">
      <script defer src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"> </script>




        <style>
    .user-avatar {
    border-radius: 50%;
    height: 40px;
    width: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
    background: #798bff;
    font-size: 14px;
    font-weight: 500;
    letter-spacing: 0.06em;
    flex-shrink: 0;
    position: relative;
}

.color{
   background:  #069613;
   color: white;
   }

   .mh-bg{

    background:#17a2b8; color:#ffffff

   }

   .logo-box{
    /* background: #ffffff !important; */
   }

.table-container-scroll {
overflow-x: scroll; /* Enable horizontal scroll */
position: relative;
}

.badge-dim.badge-success {
    color: #1ee0ac;
    background-color: #e6fcf6;
    border-color: #e6fcf6;
}

.new-header{
   color: #069613;
}

.badge {
    display: inline-block;
    padding: 0 0.375rem;
    font-size: 0.675rem;
    font-weight: 500;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 3px;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
/* .badge-success {
    border-color: #1ee0ac !important;
} */

.badge{
    padding: 0.4rem 0.5rem !important;
}

.badge-dim.badge-danger {
    color: #e85347;
    background-color: #fceceb;
    border-color: #fceceb;
}

.avatar-image {
    background-size: cover;
    background-position: center;
    border-radius: 50%;
    height: 100%;
    width: 100%;
}

.pull-right {
    float: right !important;
}

.content-inner-all{

margin-left: 225px;

}

.cxs {
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    z-index: 9999;
}

.cxs .spinner {
    font-size: 5rem;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black overlay */
  display: none; /* Initially hidden */
  z-index: 9999; /* Higher z-index to cover other content */
}

#refreshList {
    float: right;
}

        </style>

   </head>
   <body class="page-header-fixed">
      <!--================================-->
      <!-- Page Container Start -->
      <!--================================-->
      <div class="page-container" style="height: 100vh">
         <!--================================-->
         <!-- Page Sidebar Start -->
         <!--================================-->
         @include('layout.sidebar')
         <!--/ Page Sidebar End -->
         <!--================================-->
         <!-- Page Content Start -->
         <!--================================-->
         <div class="page-content">
            <!--================================-->
            <!-- Page Header Start -->
            <!--================================-->
            @include('layout.header')
            <!--/ Page Header End -->
            <!--================================-->
            <!-- Page Inner Start -->
            <!--================================-->
            <div class="page-inner">
               <!-- Main Wrapper -->
               <div id="main-wrapper">
                  <!--================================-->

                  <!-- Breadcrumb Start -->
                  <!--================================-->
                  @yield('breadcrumb')
                  @yield('top-bread')

                  <!--/ Breadcrumb End -->
                  <!--================================-->
                  {{-- @include('layout.loaders') --}}

                  <div id="content-inner-all"> </div>



                  @yield('body')
               </div>
               <!--/ Main Wrapper End -->
            </div>
            <!--/ Page Inner End -->
            <!--================================-->
            <!-- Page Footer Start -->
            <!--================================-->



            {{-- <footer class="page-footer bg-gray-100">
               <div class="pd-y-10 pd-x-25">
                  <span class="tx-italic text-muted"> SRSS Copyright&copy; 2023</span>
               </div>
            </footer> --}}

            <footer class="bg-gray-100">
               <div class="pd-y-10 pd-x-25">
                   <a href="https://shaabanrobert.sc.tz/" target="_blank" class="tx-italic text-muted" id="copyright"> SRSS Copyright&copy;</a>
               </div>
           </footer>
           





            <!--/ Page Footer End -->
         </div>
         <!--/ Page Content End -->
      </div>
      <!--/ Page Container End -->
      <!--================================-->
      <!-- Color switcher Start -->
      <!--================================-->
      {{-- <div class="color-switcher hide-color-switcher">
         <!--Color switcher Show/Hide button -->
         <a class="switcher-button d-none"><i class="fa fa-cog fa-spin"></i></a>
         <!-- Color switcher title -->
         <div class="color-switcher-title">
            <span class="tx-16 text-center">Color Switcher</span>
         </div>
         <!-- Colors style -->
         <div class="color-list">
            <a class="color turquoise-theme" title="turquoise"></a>
            <a class="color emerald-theme" title="emerald"></a>
            <a class="color peter-river-theme" title="peter-river"></a>
            <a class="color amethyst-theme" title="amethyst"></a>
            <a class="color wet-asphalt-theme" title="wet-asphalt"></a>
            <a class="color green-sea-theme" title="green-sea"></a>
            <a class="color nephritis-theme" title="nephritis"></a>
            <a class="color belize-hole-theme" title="belize-hole"></a>
            <a class="color wisteria-theme" title="wisteria"></a>
            <a class="color midnight-blue-theme" title="midnight-blue"></a>
            <a class="color sun-flower-theme" title="sun-flower"></a>
            <a class="color carrot-theme" title="carrot"></a>
            <a class="color alizarin-theme" title="alizarin"></a>
            <a class="color concrete-theme" title="concrete"></a>
            <a class="color orange-theme" title="orange"></a>
            <a class="color pumpkin-theme" title="pumpkin"></a>
            <a class="color bordeaux-theme" title="bordeaux"></a>
            <a class="color dark-theme" title="dark"></a>
         </div>
      </div> --}}
      <!--/ Color switcher  End  -->
      <!--================================-->
      <!-- Scroll To Top Start-->
      <!--================================-->
      <a href="#" data-click="scroll-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>
      <!--/ Scroll To Top End -->
      <!--================================-->
      <!-- Footer Script -->
      <!--================================-->
      <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
      <script src="{{ asset('select2s/dist/js/select2.min.js') }}" ></script>
      <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.js') }}"></script>
      <script src="{{ asset('assets/plugins/popper/popper.js') }}"></script>
      <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
      <script src="{{ asset('assets/plugins/pace/pace.min.js') }}"></script>
      <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
      <script src="{{ asset('assets/plugins/chartjs/chart.js') }}"></script>
      {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
      <script src="{{ asset('assets/plugins/sparkline/sparkline.min.js') }}"></script>
      <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>
      <!-- Include jquery.scrollbar.min.js -->
      <script src="{{ asset('js/jquery.scrollbar.min.js') }}"></script>
      <script src="{{ asset('assets/js/custom.js') }}"></script>
      
      <script src="{{ asset('assets/js/highlight.min.js') }}"></script>
      <script src="{{ asset('assets/js/adminify.js') }}"></script>
      <script src="{{ asset('assets/plugins/steps/jquery.steps.js') }}"></script>
      <script src="{{ asset('assets/plugins/parsleyjs/parsley.js') }}"></script>
      <script src="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.min.js') }}"> </script>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>

      @yield('scripts')
      <script>
        $('.select2s').select2({width:'100%'});
      </script>
 
      <script>
         // Toster Notification
        //  $(document).ready(function() {
        //  	setTimeout(function() {
        //  		toastr.options = {
        //  			positionClass: 'toast-top-right',
        //  			closeButton: true,
        //  			progressBar: true,
        //  			showMethod: 'slideDown',
        //  			timeOut: 5000
        //  		};
        //  		toastr.info('Multipurpose Admin Template', 'Hi, welcome to Adminify');

        //  	}, 300);

        //  });
         // AnnualReport Chart

         


        // Get the current year
        var currentYear = new Date().getFullYear();

        // Update the copyright text with the current year
        document.getElementById('copyright').innerHTML += currentYear;
    

      </script>
   </body>
</html>

