<link rel="stylesheet" href="/assets/css/video-js.min.css" />
<link rel="stylesheet" href="/assets/css/swiper-bundle.min.css" />
<div class="container">
  <!-- Swiper -->
  <div class="swiper">
    <div class="parallax-bg" data-swiper-parallax="-23%"></div>
    <div class="swiper-wrapper">
      <div class="swiper-slide" data-slide-type="vdo">
        <video id="my-player" class="video-js" preload="auto" autoplay="autoplay" muted="muted" loop="loop" style="
    position: absolute;
    height: auto;
    width: 100%;
    z-index: -5;
    top: 50%;  /* position the top  edge of the element at the middle of the parent */
    left: 50%; /* position the left edge of the element at the middle of the parent */

    transform: translate(-50%, -50%); /* This is a shorthand of
                                         translateX(-50%) and translateY(-50%) */
">
          <source src="/assets/videos/DP-H10_ppt_20221014102802A001.mp4" type="video/mp4">
          </source>
        </video>
        <div class="title" data-swiper-parallax="-300">DP-H10</div>
        <div class="subtitle" data-swiper-parallax="-200">Automatic Hematology Analyzer, POCT</div>
        <div class="text" data-swiper-parallax="-100">
          <p>Single-use reagent kit, Free maintenance</p>
        </div>
      </div>
      <div class="swiper-slide" ata-slide-type="vdo">
      <video id="my-player2" class="video-js" preload="auto" autoplay="autoplay" muted="muted" loop="loop" style="
    position: absolute;
    height: auto;
    width: 100%;
    z-index: -5;
    top: 50%;  /* position the top  edge of the element at the middle of the parent */
    left: 50%; /* position the left edge of the element at the middle of the parent */

    transform: translate(-50%, -50%); /* This is a shorthand of
                                         translateX(-50%) and translateY(-50%) */
">
          <source src="/assets/videos/96321_20230406152527A071.mp4" type="video/mp4">
          </source>
        </video>
        <div class="title" data-swiper-parallax="-300" data-swiper-parallax-opacity="0">DP-H10</div>
        <div class="subtitle" data-swiper-parallax="-200">Automatic Hematology Analyzer, POCT</div>
        <div class="text" data-swiper-parallax="-100">
          <p>Single-use reagent kit, Free maintenance</p>
        </div>
      </div>
      <div class="swiper-slide" ata-slide-type="vdo">
      <video id="my-player3" class="video-js" preload="auto" autoplay="autoplay" muted="muted" loop="loop" style="
    position: absolute;
    height: auto;
    width: 100%;
    z-index: -5;
    top: 50%;  /* position the top  edge of the element at the middle of the parent */
    left: 50%; /* position the left edge of the element at the middle of the parent */

    transform: translate(-50%, -50%); /* This is a shorthand of
                                         translateX(-50%) and translateY(-50%) */
">
          <source src="/assets/videos/96619_20230406152456A069.mp4" type="video/mp4">
          </source>
        </video>
        <div class="title" data-swiper-parallax="-300">DH-615</div>
        <div class="subtitle" data-swiper-parallax="-200">AI Automatic Hematology Analyzer with RET</div>
        <div class="text" data-swiper-parallax="-100">
          <p>AI Cube Technology, Efficient, Comprehensive</p>
        </div>
      </div>
    </div>
    <!-- Add Pagination -->
    <div class="swiper-pagination swiper-pagination-white"></div>
    <!-- Add Navigation -->
    <div class="swiper-button-prev swiper-button-white"></div>
    <div class="swiper-button-next swiper-button-white"></div>
  </div>



  <!-- Initialize Swiper -->
  <script type="module">
    // variable 
    var VIDEO_PLAYING_STATE = {
      "PLAYING": "PLAYING",
      "PAUSE": "PAUSE"
    }
    var videoPlayStatus = VIDEO_PLAYING_STATE.PAUSE
    var timeout = null
    var waiting = 3000
    var swiper = new Swiper('.swiper', {
      speed: 600,
      parallax: true,
      loop: true,
      autoplay: {
        delay: 8000,
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

    // HTML5 vdo object
    var options = {};
    /*var player = videojs('my-player', options);
    player.on('ended', function() {
      next();
    })

    var player2 = videojs('my-player2', options);
    player2.on('ended', function() {
      next();
    })*/


    // swiper object
    swiper.on('slideChangeTransitionEnd', function() {
      var index = swiper.activeIndex
      var currentSlide = $(swiper.slides[index])
      var currentSlideType = currentSlide.data('slide-type')

      // incase user click next before video ended
      /*if (videoPlayStatus === VIDEO_PLAYING_STATE.PLAYING) {
        player.pause();
      }*/

      clearTimeout(timeout);

      switch (currentSlideType) {
        case 'img':
          runNext();
          break;
        case 'vdo':
          player.currentTime(0)
          player.play()
          videoPlayStatus = VIDEO_PLAYING_STATE.PLAYING
          break;
        default:
          throw new Error('invalid slide type');
      }
    });

    // global function
    function prev() {
      swiper.slidePrev();
    }

    function next() {
      swiper.slideNext();
    }

    function runNext() {
      timeout = setTimeout(function() {
        next()
      }, waiting);
    }

    runNext();
    /*import Swiper from 'swiper/swiper-bundle.mjs';
    import 'swiper/swiper-bundle.css';*/
    /*var swiper = new Swiper('.swiper', {
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
    });*/
  </script>
  

  <!-- Top Deplay -->
  <div class="row my-2">
    <div class="col-md-8 mb-2">
      <div id="carouselExampleCaptions" class="carousel slide">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="/assets/images/cover.png" class="d-block w-100" alt="...">
          </div>
          <div class="carousel-item">
            <img src="/assets/images/cover.png" class="d-block w-100" alt="...">
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
      </div>
    </div>
  </div>
</div>



<div class="container">
  <!-- ////////////////////////  Recommended    //////////-->
  <div class="row" style="margin-top: 50px;">

    <div class="col-md-12">
      <h2><i>Recommended</i></h2>
    </div>

    <div class="row">
      <div class="col-md-3">
        <div class="card mb-2">
          <img class="img-fluid img-fluid" src="/assets/images/rect2.png" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">Coagulation Analyzer PT-M1-11</h5>
            <p class="card-text"></p>
            <a href="#" class="btn btn-success">Buy Now</a>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card mb-2">
          <img class="img-fluid img-fluid" src="/assets/images/tube-red.png" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">Innomed Tube </h5>
            <p class="card-text"></p>
            <a href="#" class="btn btn-success">Buy Now</a>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card mb-2">
          <img class="img-fluid img-fluid" src="/assets/images/tube-red.png" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">Innomed Tube </h5>
            <p class="card-text"></p>
            <a href="#" class="btn btn-success">Buy Now</a>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card mb-2">
          <img class="img-fluid img-fluid" src="/assets/images/tube-red.png" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">Innomed Tube </h5>
            <p class="card-text"></p>
            <a href="#" class="btn btn-success">Buy Now</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ////////////////////////  Recommended    //////////-->




<!-- /////////////////////////product Box ////////////-->

<!-- //////////////////////Nav bar box/////////// -->

<div class="container">
  <div style="margin-top: 50px;">
    <div class="navbar-brand">
      <h3><i>Product</i></h3>
    </div>

    <nav class="nav nav-pills flex-column flex-sm-row">
      <a class="flex-sm-fill text-sm-center nav-link active" aria-current="page" href="#">Active</a>
      <a class="flex-sm-fill text-sm-center nav-link" href="#">Longer nav link</a>
      <a class="flex-sm-fill text-sm-center nav-link" href="#">Link</a>
      <a class="flex-sm-fill text-sm-center nav-link disabled">Disabled</a>
    </nav>

    <nav class="navbar navbar-expand-lg navbar-light ">

      <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <ul class="navbar-nav mr-auto">
          <div class="dropdown">
            <button class="btn  dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
              <h6>Hematology</h6>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
              <li><a class="dropdown-item" href="DF50">DF50</a></li>
              <li><a class="dropdown-item" href="#">UN73</a></li>
            </ul>
          </div>

          <li class="nav-item ">
            <a class="nav-link" href="#">
              <h6>CBC Poct</h6> <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <h6>automate chemistry</h6>
            </a>
          </li>

        </ul>
      </div>
    </nav>
  </div>
</div>
<!-- //////////////////////Nav bar box/////////// -->


<!-- ////////////////////  Nav card product  box  /////////////////////// -->
<div class="container">
  <div class="row">
    <div class="col-md-3">
      <div class="card mb-2" id="DF50">
        <img class="img-fluid" src="/assets/images/xq-DF50CRP.png" alt="Card image cap">
        <div class="card-body">
          <h5 class="card-title"><a class="link-success  link-opacity-75-hover" href="/product/1">DF50</a></h5>
          <p class="card-text"> 5-Part+</p>
          <a href="#" class="btn btn-success">Buy Now</a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card mb-2">
        <img class="img-fluid" src="/assets/images/xq-UN73_.png" alt="Card image cap">
        <div class="card-body">
          <h5 class="card-title"><a class="link-success  link-opacity-75-hover" href="/product/1">UN73</a></h5>
          <p class="card-text">3-Part & 5-Part Combined Auto Hematology Analyzer</p>
          <a href="#" class="btn btn-success">Buy Now</a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card mb-2">
        <img class="img-fluid" src="/assets/images/xq-DP-H10-2(1).png" alt="Card image cap">
        <div class="card-body">
          <h5 class="card-title"><a class="link-success  link-opacity-75-hover" href="/product/1">DP-H10</a></h5>
          <p class="card-text">Automatic Hematology Analyzer, POCT</p>
          <a href="#" class="btn btn-success">Buy Now</a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card mb-2">
        <img class="img-fluid" src="/assets/images/xq-Dp-c16.png" alt="Card image cap">
        <div class="card-body">
          <h5 class="card-title"><a class="link-success  link-opacity-75-hover" href="/product/1">DP-C16</a></h5>
          <p class="card-text">Semi-Auto Biochemistry & Coagulation Analyzer</p>
          <a href="#" class="btn btn-success">Buy Now</a>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="container">

  <div class="row" style="margin-top: 50px;">
    <div class="col-md-3">
      <div class="card mb-2" id="DF50">
        <img class="img-fluid" src="/assets/images/xq-DF50CRP.png" alt="Card image cap">
        <div class="card-body">
          <h5 class="card-title"><a class="link-success  link-opacity-75-hover" href="/product/1">DF50</a></h5>
          <p class="card-text"> 5-Part+</p>
          <a href="#" class="btn btn-success">Buy Now</a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card mb-2">
        <img class="img-fluid" src="/assets/images/xq-UN73_.png" alt="Card image cap">
        <div class="card-body">
          <h5 class="card-title"><a class="link-success  link-opacity-75-hover" href="/product/1">UN73</a></h5>
          <p class="card-text">3-Part & 5-Part Combined Auto Hematology Analyzer</p>
          <a href="#" class="btn btn-success">Buy Now</a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card mb-2">
        <img class="img-fluid" src="/assets/images/xq-DP-H10-2(1).png" alt="Card image cap">
        <div class="card-body">
          <h5 class="card-title"><a class="link-success  link-opacity-75-hover" href="/product/1">DP-H10</a></h5>
          <p class="card-text">Automatic Hematology Analyzer, POCT</p>
          <a href="#" class="btn btn-success">Buy Now</a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card mb-2">
        <img class="img-fluid" src="/assets/images/xq-Dp-c16.png" alt="Card image cap">
        <div class="card-body">
          <h5 class="card-title"><a class="link-success  link-opacity-75-hover" href="/product/1">DP-C16</a></h5>
          <p class="card-text">Semi-Auto Biochemistry & Coagulation Analyzer</p>
          <a href="#" class="btn btn-success">Buy Now</a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- ////////////////////  Nav card product  box  /////////////////////// -->

<script src="/assets/js/video.min.js"></script>
<script src="/assets/js/swiper-bundle.min.js"></script>