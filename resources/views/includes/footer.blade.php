<footer class="pt-4 pb-4 bg-primary">
    <div class="container">
        <div class="row flex-column justify-content-center align-items-center">
            <div class="d-flex">
                <a href="{{ url('privacy-policy') }}" class="text-light nav-link">Privacy Policy</a>
                <a href="{{ url('terms-and-conditions') }}" class="text-light nav-link">Terms & Conditions</a>
            </div>
            <div class="mt-2 text-light">
                Copyright &copy; 2021 SWA <small class="logo-dot-text"><i class="fa fa-circle"></i></small> TANTRAA Wellness Private Limited. | Designed by <a class="text-light" href="https://bestlagroup.com/">Bestla Group</a>
            </div>
        </div>
    </div>
</footer>

@if (Request::route()->getName() != 'frontend.home')
    <a href="#"  onclick="goBack()" class="btn btn-dark float-back-btn"><i class="fa fa-angle-left fa-2x"></i></a>
@endif
<div class="modal fade" id="login_modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Login</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4 p-3">
                        <a href="{{ url('login') }}?type={{ Hash::make(0) }}" class="login-button-card card shadow align-items-center">
                            <div class="card-body text-center pl-1 pr-1">
                                <img src="{{ asset('assets/img/User.png') }}" class="card-img-top mb-4">
                                <h5 class="card-title text-primary"><b>Individual Login</b></h5>
                                <p class="card-text"><small>To Access Your Programs</small></p>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4 p-3">
                        <a href="{{ url('login') }}?type={{ Hash::make(2) }}" class="login-button-card card shadow align-items-center">
                            <div class="card-body text-center pl-1 pr-1">
                                <img src="{{ asset('assets/img/f.png') }}" class="card-img-top mb-4">
                                <h5 class="card-title text-primary"><b>Franchisee Login</b></h5>
                                <p class="card-text"><small>To Track All Your Users</small></p>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4 p-3">
                        <a href="{{ url('login') }}?type={{ Hash::make(1) }}" class="login-button-card card shadow align-items-center">
                            <div class="card-body text-center pl-1 pr-1">
                                <img src="{{ asset('assets/img/Institute.png') }}" class="card-img-top mb-4">
                                <h5 class="card-title text-primary"><b>Institutional Login</b></h5>
                                <p class="card-text"><small>Everything Related To Franchisee</small></p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/plugins/jquery/dist/jquery.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/dist/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/plugins/slick/slick.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="{{ asset('assets/plugins/jquery/jquery.validate.min.js') }}"></script>

<script>
function goBack() {
  window.history.back();
}

function myMap() {
    var myLatlng = new google.maps.LatLng(17.6847238, 73.9931717);
    var mapOptions = {
        zoom: 15,
        center: myLatlng
    }
    var map = new google.maps.Map(document.getElementById("googleMap"), mapOptions);
    var marker = new google.maps.Marker({
        position: myLatlng,
        title: "Hello World!"
    });
    // To add the marker to the map, call setMap();
    marker.setMap(map);
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDWwtYhkTroERwH4pW53cqK3FsGqcaQg44&callback=myMap"></script>
<script>
$(function(){
    $(window).scroll(function () {
        if ($(this).scrollTop() >= ($('body').height() - ($(this).height() * 1.5))) {
            $('.float-back-btn').css('display', 'flex');
        } else {
            $('.float-back-btn').hide();
        }
    });
})
</script>

@yield('js')
