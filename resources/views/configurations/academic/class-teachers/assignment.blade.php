@extends('layout.index')
@section('body')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css ">
<style>

.image-container {
    /* position: relative; */
    width: 200px; /* Adjust the size as needed */
}

.edit-icon {
    position: absolute;
    font-size: 22px;
    color: #007bff;
    cursor: pointer;
}

.user-profile-img img {
border-radius: 0 !important;

}

</style>

        </div>


        </div>



        {{-- STEP 02 --}}


        <div class="user-prfile-activity-area mg-b-40 mg-t-30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="user-profile-about shadow-reset">
                            <h2 style="color:#49668a"><i style="color: #77bd5d" class="fa-brands fa-atlassian"></i> &nbsp; Assign Class Teacher </h2>

                        </div>
                    </div>


                    @foreach ($streams as $stream )
                    <div class="col-lg-4">
                        <div class="sparkline7-list profile-online-mg-t-30 shadow-reset">
                            <div class="sparkline7-hd">
                                <div class="main-spark7-hd">
                                    <h1 style="color:#49668a">  <i style="color: #77bd5d" class="fa-brands fa-square-steam"></i> &nbsp; STREAM {{ $stream->name }}</h1>
                                    <div class="sparkline7-outline-icon">
                                        <span class="sparkline7-collapse-link"><i class="fa fa-chevron-up"></i></span>
                                        <span><i class="fa fa-wrench"></i></span>
                                        <span class="sparkline7-collapse-close"><i class="fa fa-times"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="sparkline7-graph" style="max-height: 30rem !important; min-height:20rem;">
                                <div class="user-profile-contact user-profile-scrollbar mCustomScrollbar _mCS_6 mCS-autoHide" style="height: 1820px; position: relative; overflow: visible;"><div id="mCSB_6" class="mCustomScrollBox mCS-light-1 mCSB_vertical mCSB_outside" tabindex="0" style="max-height: none;"><div id="mCSB_6_container" class="mCSB_container" style="position:relative; top:0; left:0;" dir="ltr">
                                    <ul class="profile-contact-menu">
                                        <li>
                                            <a href="#"><img src="img/notification/5.jpg" alt="" class="mCS_img_loaded"> <span>Sakila Joy</span> <span class="contact-profile-online-f"><i class="fa fa-circle contact-profile-online"></i></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#"><img src="img/notification/1.jpg" alt="" class="mCS_img_loaded"> <span>Fire Foxy</span> <span class="contact-profile-online-f">31m</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#"><img src="img/notification/2.jpg" alt="" class="mCS_img_loaded"> <span>Jhon Royita</span> <span class="contact-profile-online-f"><i class="fa fa-circle contact-profile-online"></i></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#"><img src="img/notification/3.jpg" alt="" class="mCS_img_loaded"> <span>Selim Reza</span> <span class="contact-profile-online-f"><i class="fa fa-circle contact-profile-online"></i></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div id="mCSB_6_scrollbar_vertical" class="mCSB_scrollTools mCSB_6_scrollbar mCS-light-1 mCSB_scrollTools_vertical" style="display: block;"><div class="mCSB_draggerContainer"><div id="mCSB_6_dragger_vertical" class="mCSB_dragger" style="position: absolute; min-height: 30px; display: block; height: 1113px; max-height: 1770px; top: 0px;"><div class="mCSB_dragger_bar" style="line-height: 30px;"></div></div><div class="mCSB_draggerRail"></div></div></div></div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>




@section('scripts')
<script>


</script>

@endsection



@endsection
