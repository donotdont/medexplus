<link rel="stylesheet" href="/assets/css/video-js.min.css" />
<link rel="stylesheet" href="/assets/css/swiper-bundle.min.css" />
<div class="container">
  <!-- Swiper -->
  <div class="swiper">
    <div class="swiper-wrapper">
      <div class="swiper-slide">
        <img class="img-responsive" src="/assets/images/tube2222.png?v=<?= $version ?>" />
      </div>
      <div class="swiper-slide">
        <img class="img-responsive" src="/assets/images/Coagution.png?v=<?= $version ?>" />
      </div>
      <!-- <div class="swiper-slide">
        <img class="img-responsive" src="/assets/images/cover.jpg?v=<?= $version ?>" />
      </div>  -->
    </div>
    <!-- Add Pagination -->
    <div class="swiper-pagination swiper-pagination-white"></div>
    <!-- Add Navigation -->
    <div class="swiper-button-prev swiper-button-white"></div>
    <div class="swiper-button-next swiper-button-white"></div>
  </div>



  <!-- Initialize Swiper -->
  <script type="module">
    /*import Swiper from 'swiper/swiper-bundle.mjs';
    import 'swiper/swiper-bundle.css';*/
    var swiper = new Swiper('.swiper', {
      speed: 600,
      parallax: true,
      loop: true,
      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
  </script>


  <!-- Top Deplay -->
  <div class="row my-2">
    <div class="col-md-8 mb-2">
      <div id="carouselExampleCaptions" class="carousel slide">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="/assets/images/cover.jpg?v=<?= $version ?>" class="d-block w-100" alt="...">
          </div>
          <div class="carousel-item">
            <img src="/assets/images/cover.jpg?v=<?= $version ?>" class="d-block w-100" alt="...">
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="row">
        <img src="/assets/images/rect3.png" class="img-fluid" style="min-height: 200px;">
        <div class="col-4"></div>
        <div class="col-4"> <a href="#" class="btn btn-success btn-sm" style="margin-top:-150px;">Buy Now</a></div>
        <div class="col-4"></div>
      </div>
      <div class="row">
        <img src="/assets/images/p2024.jpg" class="img-fluid" style="min-height: 200px;">
        <div class="col-4"></div>
        <div class="col-4"> <a href="#" class="btn btn-success btn-sm" style="margin-top:-150px;">Buy Now</a></div>
        <div class="col-4"></div>
      </div>
    </div>
  </div>
</div>



<div class="container">
  <h2 class="pb-2 border-bottom">
    <span class="title titleBefore pb-1">Browse Categories</span>
  </h2>
  <div class="mt-3 row">
    <div class="col-md-3">
      <div class="d-flex flex-column flex-md-row align-items-center justify-content-center">
        <div class="m-0 list-group list-group-checkable d-grid gap-2 border-0">
          <input class="list-group-item-check pe-none" type="radio" name="listGroupCheckableRadios" id="listGroupCheckableRadios1" value="1" checked="">
          <label class="list-group-item list-group-item-action d-flex gap-3 rounded-3 py-3" for="listGroupCheckableRadios1">
            <div class="masthead-followup-icon d-inline-block p-1 text-bg-secondary rounded flex-shrink-0 bg-primary">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" focusable="false" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="m 8,16 c 3.313708,0 6,-2.686292 6,-6 C 14,6.6572179 8,0 8,0 8,0 2,5.686 2,10 c 0,3.313708 2.6862915,6 6,6 z M 6.646,4.646 C 6.27,5.023 5.374,6.135 4.553,7.776 c 0,0 -0.3040045,0.6235878 0.101224,0.8448251 C 5.0594524,8.8420625 5.447,8.224 5.447,8.224 c 0.78,-1.559 1.616,-2.58 1.907,-2.87 0,0 0.4564135,-0.6389305 0.1253713,-0.9119568 C 7.1483291,4.169017 6.646,4.646 6.646,4.646 Z" clip-rule="evenodd"></path>
              </svg>
            </div>
            <div class="d-flex gap-2 w-100 justify-content-between">
              <div>
                <h6 class="mb-0">Hematology</h6>
                <span class="d-block small opacity-50">0 item(s)</span>
              </div>
            </div>
          </label>

          <input class="list-group-item-check pe-none" type="radio" name="listGroupCheckableRadios" id="listGroupCheckableRadios2" value="2">
          <label class="list-group-item list-group-item-action d-flex gap-3 rounded-3 py-3" for="listGroupCheckableRadios2">
            <div class="masthead-followup-icon d-inline-block p-1 text-bg-secondary rounded flex-shrink-0 bg-primary">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" focusable="false" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M 5.734513,0 C 5.247107,0 4.863172,0.383935 4.863172,0.871341 c 0,0.4179715 0.313138,0.7692307 0.697073,0.8386658 V 13.560245 C 5.560245,14.918993 6.641253,16 8,16 c 1.358747,0 2.439755,-1.081007 2.439755,-2.439755 V 1.7100068 C 10.82369,1.6405718 11.136828,1.2893125 11.136828,0.871341 11.136828,0.383935 10.752893,0 10.265486,0 Z M 9.742682,1.7426821 V 3.8339006 H 6.257318 V 1.7426821 M 5.734513,1.0456093 c -0.104833,0 -0.174268,-0.069435 -0.174268,-0.1742683 0,-0.104833 0.06944,-0.174268 0.174268,-0.174268 h 4.530973 c 0.104834,0 0.174269,0.06944 0.174269,0.174268 0,0.104833 -0.06943,0.1742683 -0.174269,0.1742683 M 8.348536,6.622192 c 0.383935,0 0.697073,0.3131383 0.697073,0.6970728 0,0.3839348 -0.313138,0.6970728 -0.697073,0.6970728 -0.383934,0 -0.697073,-0.313138 -0.697073,-0.6970728 0,-0.3839345 0.313139,-0.6970728 0.697073,-0.6970728 z M 7.477195,10.107556 C 7.756296,10.107556 8,10.351259 8,10.630361 c 0,0.279101 -0.243704,0.522804 -0.522805,0.522804 -0.279101,0 -0.522804,-0.243703 -0.522804,-0.522804 0,-0.279102 0.243703,-0.522805 0.522804,-0.522805 z" clip-rule="evenodd"></path>
              </svg>
            </div>
            <div class="d-flex gap-2 w-100 justify-content-between">
              <div>
                <h6 class="mb-0">Chemistry</h6>
                <span class="d-block small opacity-50">0 item(s)</span>
              </div>
            </div>
          </label>

          <input class="list-group-item-check pe-none" type="radio" name="listGroupCheckableRadios" id="listGroupCheckableRadios3" value="3">
          <label class="list-group-item list-group-item-action d-flex gap-3 rounded-3 py-3" for="listGroupCheckableRadios3">
            <div class="masthead-followup-icon d-inline-block p-1 text-bg-secondary rounded flex-shrink-0 bg-primary">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" focusable="false" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="m 9.0305901,10.13151 v 4.700521 c 0.0014,0.340058 0.277128,0.615173 0.617188,0.615886 h 3.1041659 c 0.34006,-7.03e-4 0.615757,-0.275828 0.617188,-0.615886 V 1.171875 C 13.369861,0.830292 13.093527,0.552802 12.751944,0.552083 H 9.6477781 c -0.341583,7.29e-4 -0.617908,0.278209 -0.617188,0.619792 v 5.941406 c -2.86e-4,0.02869 -0.0047,0.05719 -0.01302,0.08464 l 2.0585939,2.058593 c 0.05468,0.05434 0.08478,0.128657 0.08333,0.20573 l 0.0599,1.104166 c 0.0036,0.06047 -0.01287,0.120438 -0.04687,0.170573 0.191406,0.739584 0.425781,1.010417 0.811198,1.608073 0.280191,0.388626 0.228353,0.924847 -0.121094,1.252604 -0.398102,0.397866 -1.043303,0.397866 -1.441406,0 -0.195377,-0.186142 -0.304768,-0.445003 -0.302083,-0.714843 0.0062,-0.194555 0.06958,-0.38297 0.182291,-0.541667 0.365886,-0.5625 0.606771,-0.877604 0.78125,-1.519531 -0.03928,0.02465 -0.08397,0.0394 -0.130208,0.04297 -0.01038,0.0013 -0.02087,0.0013 -0.03125,0 l -1.1184899,-0.0651 c -0.06973,-0.0043 -0.135488,-0.03394 -0.184895,-0.08333 v 0 L 9.0332011,10.131523 Z M 6.9068921,4.572917 6.6464751,4.833333 8.4693921,6.65625 V 1.171875 C 8.4693921,0.524666 8.9940591,0 9.6412671,0 h 3.1106769 c 0.309607,6.25e-4 0.606389,0.12373 0.825521,0.342448 v 0 c 0.221841,0.218954 0.346605,0.517731 0.346354,0.829427 v 13.660156 c -4.5e-4,0.309514 -0.124147,0.606105 -0.34375,0.824219 v 0 C 13.360511,15.876082 13.06264,15.999727 12.751944,16 H 9.6477781 c -0.647209,0 -1.171875,-0.524666 -1.171875,-1.171875 V 9.580729 L 5.1894441,6.290365 4.9290281,6.550781 2.5032461,4.115885 C 1.2011631,2.813802 3.1712151,0.83724 4.4759031,2.141927 Z m -0.665364,0.660156 -0.651042,0.651042 4.348958,4.35026 0.6914059,0.04037 -0.03776,-0.694011 z M 10.38085,6.760417 c 1.6e-5,0.01556 0.0061,0.0305 0.01693,0.04167 0.01131,0.01062 0.02616,0.01665 0.04167,0.01693 h 1.518229 c 0.01309,9.5e-5 0.02587,-0.004 0.03646,-0.01172 v 0 c 0.01084,-0.01116 0.01691,-0.02611 0.01693,-0.04167 V 2.776044 c 2.6e-4,-0.01561 -0.0058,-0.03067 -0.01693,-0.04167 -0.01069,-0.0117 -0.02582,-0.01832 -0.04167,-0.01823 h -1.513021 c -0.03257,7.03e-4 -0.0586,0.02732 -0.05859,0.0599 z m 0.05859,-4.595052 h 1.518229 c 0.337268,0 0.610677,0.273409 0.610677,0.610677 v 3.984375 c -1.5e-4,0.161851 -0.06482,0.316965 -0.179687,0.430989 l -0.01953,0.01823 C 12.257084,7.313163 12.11022,7.370793 11.95767,7.371095 H 10.439441 C 10.102766,7.369695 9.8301911,7.097093 9.8287641,6.760418 V 2.776043 c 0,-0.337268 0.2734099,-0.610677 0.6106769,-0.610677 z m 1.397845,10.925709 c -0.04088,0.142437 -0.16452,0.306691 -0.324101,0.403779 0,0 -0.07572,0.02636 -0.04693,0.07623 0.02879,0.04987 0.0892,0.01509 0.0892,0.01509 0.186615,-0.09336 0.345457,-0.354568 0.390952,-0.475694 0,0 0.03971,-0.10551 -0.01825,-0.113475 -0.05796,-0.008 -0.09088,0.09407 -0.09088,0.09407 z" clip-rule="evenodd"></path>
              </svg>
            </div>
            <div class="d-flex gap-2 w-100 justify-content-between">
              <div>
                <h6 class="mb-0">Urine Analysis</h6>
                <span class="d-block small opacity-50">0 item(s)</span>
              </div>
            </div>
          </label>

          <input class="list-group-item-check pe-none" type="radio" name="listGroupCheckableRadios" id="listGroupCheckableRadios4" value="4">
          <label class="list-group-item list-group-item-action d-flex gap-3 rounded-3 py-3" for="listGroupCheckableRadios4">
            <div class="masthead-followup-icon d-inline-block p-1 text-bg-secondary rounded flex-shrink-0 bg-primary">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" focusable="false" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M 8.0297284,-2e-7 C 5.5180105,1.4455237 2.1317459,2.4412754 0.9175907,2.782508 0.8027178,5.2186559 0.8470974,8.5266656 2.4577904,11.438302 3.6552824,13.682315 5.6864437,15.09558 7.9974101,16 10.390562,15.209732 12.25588,13.574154 13.543959,11.434839 15.092841,8.639609 15.21711,5.4433274 15.078362,2.7686592 13.430532,2.5260944 10.135814,1.2955469 8.0297284,-2e-7 Z m -0.060038,1.1822838 c 2.029219,1.1656668 4.2116266,1.893616 6.1099746,2.3714819 0.105105,3.0922653 -0.403215,5.5401697 -1.425893,7.3857695 -1.01961,1.90284 -2.7121126,3.137087 -4.6563654,3.969392 C 5.9855676,14.162298 4.4408131,12.768205 3.3479564,10.945308 2.3253452,9.0967412 1.8165004,6.6412875 1.9186009,3.5445286 2.460296,3.3905396 5.5926434,2.472971 7.9696904,1.1822836 Z m 4.1287356,3.3297731 c -2.2311846,0 -2.042864,0.1653099 -3.6576717,2.1163226 C 7.8525394,7.3394084 7.3143355,8.0891653 6.8289781,8.8740132 6.6709317,8.6928504 6.4998221,8.5240489 6.3186582,8.3660043 l 0.0023,-0.00578 C 5.7606167,7.8839368 4.2036386,7.1462997 3.8929038,8.1651061 4.512525,8.5920746 5.0666674,9.1061472 5.5381622,9.6925992 6.1162064,10.385164 6.5227545,11.188193 6.9005524,12.000581 8.5401562,8.8293697 9.9101405,6.9543088 12.098426,4.5120567 Z" clip-rule="evenodd"></path>
              </svg>
            </div>
            <div class="d-flex gap-2 w-100 justify-content-between">
              <div>
                <h6 class="mb-0">Immunology</h6>
                <span class="d-block small opacity-50">0 item(s)</span>
              </div>
            </div>
          </label>

          <input class="list-group-item-check pe-none" type="radio" name="listGroupCheckableRadios" id="listGroupCheckableRadios5" value="5">
          <label class="list-group-item list-group-item-action d-flex gap-3 rounded-3 py-3" for="listGroupCheckableRadios5">
            <div class="masthead-followup-icon d-inline-block p-1 text-bg-secondary rounded flex-shrink-0 bg-primary">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" focusable="false" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M 7.2,0 6.9171872,1.6984375 C 6.5277888,1.7653733 6.1513408,1.8688617 5.7890625,2.003125 l -1.096875,-1.33125 -1.384375,0.8 0.603125,1.6109375 C 3.6105904,3.333117 3.333117,3.6105904 3.0828125,3.9109375 l -1.6109375,-0.603125 -0.8,1.384375 1.33125,1.096875 C 1.8688617,6.1513408 1.7653733,6.5277888 1.6984375,6.9171872 L 0,7.2 v 1.6 l 1.6984375,0.282813 c 0.066936,0.389398 0.1704242,0.765846 0.3046875,1.128125 l -1.33125,1.096875 0.8,1.384374 1.6109375,-0.603125 c 0.2503045,0.300348 0.5277779,0.577821 0.828125,0.828125 l -0.603125,1.610938 1.384375,0.8 1.096875,-1.33125 c 0.3622783,0.134263 0.7387263,0.237751 1.1281247,0.304687 L 7.2,16 h 1.6 l 0.282813,-1.698438 c 0.389398,-0.06694 0.765846,-0.170424 1.128125,-0.304687 l 1.096875,1.33125 1.384374,-0.8 -0.603125,-1.610938 c 0.300348,-0.250304 0.577821,-0.527777 0.828125,-0.828125 l 1.610938,0.603125 0.8,-1.384374 -1.33125,-1.096875 C 14.131138,9.848659 14.234626,9.472211 14.301562,9.082813 L 16,8.8 V 7.2 L 14.301562,6.9171872 C 14.234626,6.5277888 14.131138,6.1513408 13.996875,5.7890625 l 1.33125,-1.096875 -0.8,-1.384375 -1.610938,0.603125 C 12.666883,3.6105904 12.38941,3.333117 12.089062,3.0828125 l 0.603125,-1.6109375 -1.384374,-0.8 -1.096875,1.33125 C 9.848659,1.8688617 9.472211,1.7653733 9.082813,1.6984375 L 8.8,0 Z m 0,3.271875 V 5.7390625 A 2.4,2.4 0 0 0 5.6,8 2.4,2.4 0 0 0 5.6421875,8.4375 L 3.503125,9.673438 C 3.3102601,9.152246 3.2,8.59011 3.2,8 3.2,5.612914 4.924289,3.651039 7.2,3.271875 Z m 1.6,0 c 2.275711,0.379164 4,2.341039 4,4.728125 0,0.59011 -0.11026,1.152246 -0.303125,1.673438 L 10.357813,8.439062 A 2.4,2.4 0 0 0 10.4,8 2.4,2.4 0 0 0 8.8,5.740625 Z m -2.3578128,6.55 A 2.4,2.4 0 0 0 8,10.4 2.4,2.4 0 0 0 9.559375,9.823438 l 2.14375,1.2375 C 10.82431,12.124818 9.4949,12.8 8,12.8 6.5051,12.8 5.1756899,12.124818 4.296875,11.060938 Z" clip-rule="evenodd"></path>
              </svg>
            </div>
            <div class="d-flex gap-2 w-100 justify-content-between">
              <div>
                <h6 class="mb-0">Supply</h6>
                <span class="d-block small opacity-50">0 item(s)</span>
              </div>
            </div>
          </label>
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <div class="row product">

        <div class="col-md-3">
          <div class="card mb-2">
            <img class="img-fluid img-fluid" src="/assets/images/tube-red.png" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">Innomed Tube </h5>
              <p class="card-text"></p>
            </div>
            <div class="card-footer">
              <button type="button" class="btn btn-link btn-sm"><i class="fa-solid fa-heart" style="font-size: 1.5em;color:#dddddd"></i></button>
              <button type="button" class="btn btn-success btn-sm">Buy Now</button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script src="/assets/js/home.js?v=<?= $version ?>"></script>
<script>
  window.addEventListener("load", function(event) {
    var home = new Home();
    home.getCategory();
    home.getProduct();

    document.querySelectorAll('input[name="listGroupCheckableRadios"]').forEach(function(input) {
      input.addEventListener('change', function(event) {
        //console.log(event, event.target.id, event.target.value);
        home.getProduct();
      });
    });
  });
</script>
<script src="/assets/js/video.min.js"></script>
<script src="/assets/js/swiper-bundle.min.js"></script>