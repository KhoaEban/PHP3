{{-- About --}}
@extends('layouts.navbar_user')

@section('content')
    <div id="home" class="intro route bg-image" style="background-image: url({{ asset('img/intro-bg.jpg') }})">
        <div class="overlay-itro"></div>
        <div class="intro-content display-table">
            <div class="table-cell">
                <div class="container">
                    <!--<p class="display-6 color-d">Hello, world!</p>-->
                    <h1 class="intro-title mb-4">I am Y Khoa Êban</h1>
                    <p class="intro-subtitle"><span class="text-slider-items">CEO DevFolio,Web Developer,Web
                            Designer,Frontend Developer,Graphic Designer</span><strong class="text-slider"></strong></p>
                    <!-- <p class="pt-3"><a class="btn btn-primary btn js-scroll px-4" href="#about" role="button">Learn More</a></p> -->
                </div>
            </div>
        </div>
    </div>
    <!--/ Intro Skew End /-->

    <section id="about" class="about-mf sect-pt4 route">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box-shadow-full">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-sm-6 col-md-5">
                                        <div class="about-img">
                                            <img src="{{ asset('images/avtkhoa.png') }}" class="img-fluid rounded b-shadow-a"
                                                alt="">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-7">
                                        <div class="about-info">
                                            <p><span class="title-s">Tên: </span> <span>{{ $name }}</span></p>
                                            <p><span class="title-s">Hồ sơ: </span> <span>{{ $profile }}</span></p>
                                            <p><span class="title-s">Email: </span> <span>{{ $email }}</span></p>
                                            <p><span class="title-s">Sđt: </span> <span>{{ $phone }}</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="skill-mf">
                                    <p class="title-s">Kỹ năng</p>
                                    <span>{{ $skills[0] }}</span> <span class="pull-right">85%</span>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 85%;" aria-valuenow="85"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span>{{ $skills[1] }}</span> <span class="pull-right">75%</span>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span>{{ $skills[2] }}</span> <span class="pull-right">50%</span>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span>{{ $skills[3] }}</span> <span class="pull-right">90%</span>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 90%" aria-valuenow="90"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="about-me pt-4 pt-md-0">
                                    <div class="title-box-2">
                                        <h5 class="title-left">
                                            Về bản thân tôi
                                        </h5>
                                    </div>
                                    <p class="lead">
                                        {{ $about }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--/ Section Services Star /-->
    <section id="service" class="services-mf route">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="title-box text-center">
                        <h3 class="title-a">
                            Services
                        </h3>
                        <p class="subtitle-a">
                            Lorem ipsum, dolor sit amet consectetur adipisicing elit.
                        </p>
                        <div class="line-mf"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="service-box">
                        <div class="service-ico">
                            <span class="ico-circle"><i class="ion-monitor"></i></span>
                        </div>
                        <div class="service-content">
                            <h2 class="s-title">Web Design</h2>
                            <p class="s-description text-center">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni adipisci eaque autem fugiat!
                                Quia,
                                provident vitae! Magni
                                tempora perferendis eum non provident.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-box">
                        <div class="service-ico">
                            <span class="ico-circle"><i class="ion-code-working"></i></span>
                        </div>
                        <div class="service-content">
                            <h2 class="s-title">Web Development</h2>
                            <p class="s-description text-center">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni adipisci eaque autem fugiat!
                                Quia,
                                provident vitae! Magni
                                tempora perferendis eum non provident.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-box">
                        <div class="service-ico">
                            <span class="ico-circle"><i class="ion-camera"></i></span>
                        </div>
                        <div class="service-content">
                            <h2 class="s-title">Photography</h2>
                            <p class="s-description text-center">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni adipisci eaque autem fugiat!
                                Quia,
                                provident vitae! Magni
                                tempora perferendis eum non provident.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-box">
                        <div class="service-ico">
                            <span class="ico-circle"><i class="ion-android-phone-portrait"></i></span>
                        </div>
                        <div class="service-content">
                            <h2 class="s-title">Responsive Design</h2>
                            <p class="s-description text-center">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni adipisci eaque autem fugiat!
                                Quia,
                                provident vitae! Magni
                                tempora perferendis eum non provident.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-box">
                        <div class="service-ico">
                            <span class="ico-circle"><i class="ion-paintbrush"></i></span>
                        </div>
                        <div class="service-content">
                            <h2 class="s-title">Graphic Design</h2>
                            <p class="s-description text-center">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni adipisci eaque autem fugiat!
                                Quia,
                                provident vitae! Magni
                                tempora perferendis eum non provident.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-box">
                        <div class="service-ico">
                            <span class="ico-circle"><i class="ion-stats-bars"></i></span>
                        </div>
                        <div class="service-content">
                            <h2 class="s-title">Marketing Services</h2>
                            <p class="s-description text-center">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni adipisci eaque autem fugiat!
                                Quia,
                                provident vitae! Magni
                                tempora perferendis eum non provident.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ Section Services End /-->

    <div class="section-counter paralax-mf bg-image" style="background-image: url(img/counters-bg.jpg)">
        <div class="overlay-mf"></div>
        <div class="container">
            <div class="row">
                <div class="col-sm-3 col-lg-3">
                    <div class="counter-box">
                        <div class="counter-ico">
                            <span class="ico-circle"><i class="ion-checkmark-round"></i></span>
                        </div>
                        <div class="counter-num">
                            <p class="counter">450</p>
                            <span class="counter-text">WORKS COMPLETED</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-lg-3">
                    <div class="counter-box pt-4 pt-md-0">
                        <div class="counter-ico">
                            <span class="ico-circle"><i class="ion-ios-calendar-outline"></i></span>
                        </div>
                        <div class="counter-num">
                            <p class="counter">15</p>
                            <span class="counter-text">YEARS OF EXPERIENCE</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-lg-3">
                    <div class="counter-box pt-4 pt-md-0">
                        <div class="counter-ico">
                            <span class="ico-circle"><i class="ion-ios-people"></i></span>
                        </div>
                        <div class="counter-num">
                            <p class="counter">550</p>
                            <span class="counter-text">TOTAL CLIENTS</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-lg-3">
                    <div class="counter-box pt-4 pt-md-0">
                        <div class="counter-ico">
                            <span class="ico-circle"><i class="ion-ribbon-a"></i></span>
                        </div>
                        <div class="counter-num">
                            <p class="counter">36</p>
                            <span class="counter-text">AWARD WON</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--/ Section Portfolio Star /-->
    <section id="work" class="portfolio-mf sect-pt4 route">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="title-box text-center">
                        <h3 class="title-a">
                            Portfolio
                        </h3>
                        <p class="subtitle-a">
                            Lorem ipsum, dolor sit amet consectetur adipisicing elit.
                        </p>
                        <div class="line-mf"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="work-box">
                        <a href="img/work-1.jpg" data-lightbox="gallery-mf">
                            <div class="work-img">
                                <img src="img/work-1.jpg" alt="" class="img-fluid">
                            </div>
                            <div class="work-content">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h2 class="w-title">Lorem impsum dolor</h2>
                                        <div class="w-more">
                                            <span class="w-ctegory">Web Design</span> / <span class="w-date">18 Sep.
                                                2018</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="w-like">
                                            <span class="ion-ios-plus-outline"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="work-box">
                        <a href="img/work-2.jpg" data-lightbox="gallery-mf">
                            <div class="work-img">
                                <img src="img/work-2.jpg" alt="" class="img-fluid">
                            </div>
                            <div class="work-content">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h2 class="w-title">Loreda Cuno Nere</h2>
                                        <div class="w-more">
                                            <span class="w-ctegory">Web Design</span> / <span class="w-date">18 Sep.
                                                2018</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="w-like">
                                            <span class="ion-ios-plus-outline"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="work-box">
                        <a href="img/work-3.jpg" data-lightbox="gallery-mf">
                            <div class="work-img">
                                <img src="img/work-3.jpg" alt="" class="img-fluid">
                            </div>
                            <div class="work-content">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h2 class="w-title">Mavrito Lana Dere</h2>
                                        <div class="w-more">
                                            <span class="w-ctegory">Web Design</span> / <span class="w-date">18 Sep.
                                                2018</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="w-like">
                                            <span class="ion-ios-plus-outline"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="work-box">
                        <a href="img/work-4.jpg" data-lightbox="gallery-mf">
                            <div class="work-img">
                                <img src="img/work-4.jpg" alt="" class="img-fluid">
                            </div>
                            <div class="work-content">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h2 class="w-title">Bindo Laro Cado</h2>
                                        <div class="w-more">
                                            <span class="w-ctegory">Web Design</span> / <span class="w-date">18 Sep.
                                                2018</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="w-like">
                                            <span class="ion-ios-plus-outline"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="work-box">
                        <a href="img/work-5.jpg" data-lightbox="gallery-mf">
                            <div class="work-img">
                                <img src="img/work-5.jpg" alt="" class="img-fluid">
                            </div>
                            <div class="work-content">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h2 class="w-title">Studio Lena Mado</h2>
                                        <div class="w-more">
                                            <span class="w-ctegory">Web Design</span> / <span class="w-date">18 Sep.
                                                2018</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="w-like">
                                            <span class="ion-ios-plus-outline"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="work-box">
                        <a href="img/work-6.jpg" data-lightbox="gallery-mf">
                            <div class="work-img">
                                <img src="img/work-6.jpg" alt="" class="img-fluid">
                            </div>
                            <div class="work-content">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h2 class="w-title">Studio Big Bang</h2>
                                        <div class="w-more">
                                            <span class="w-ctegory">Web Design</span> / <span class="w-date">18 Sep.
                                                2017</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="w-like">
                                            <span class="ion-ios-plus-outline"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!--/ Section Portfolio End /-->

    <!--/ Section Testimonials Star /-->
    <div class="testimonials paralax-mf bg-image" style="background-image: url(img/overlay-bg.jpg)">
        <div class="overlay-mf"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div id="testimonial-mf" class="owl-carousel owl-theme">
                        <div class="testimonial-box">
                            <div class="author-test">
                                <img src="img/testimonial-2.jpg" alt="" class="rounded-circle b-shadow-a">
                                <span class="author">Xavi Alonso</span>
                            </div>
                            <div class="content-test">
                                <p class="description lead">
                                    Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Lorem ipsum dolor sit
                                    amet,
                                    consectetur adipiscing elit.
                                </p>
                                <span class="comit"><i class="fa fa-quote-right"></i></span>
                            </div>
                        </div>
                        <div class="testimonial-box">
                            <div class="author-test">
                                <img src="img/testimonial-4.jpg" alt="" class="rounded-circle b-shadow-a">
                                <span class="author">Marta Socrate</span>
                            </div>
                            <div class="content-test">
                                <p class="description lead">
                                    Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Lorem ipsum dolor sit
                                    amet,
                                    consectetur adipiscing elit.
                                </p>
                                <span class="comit"><i class="fa fa-quote-right"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--/ Section Blog Star /-->
    <section id="blog" class="blog-mf sect-pt4 route">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="title-box text-center">
                        <h3 class="title-a">
                            Blog
                        </h3>
                        <p class="subtitle-a">
                            Lorem ipsum, dolor sit amet consectetur adipisicing elit.
                        </p>
                        <div class="line-mf"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-blog">
                        <div class="card-img">
                            <a href="blog-single.html"><img src="img/post-1.jpg" alt="" class="img-fluid"></a>
                        </div>
                        <div class="card-body">
                            <div class="card-category-box">
                                <div class="card-category">
                                    <h6 class="category">Travel</h6>
                                </div>
                            </div>
                            <h3 class="card-title"><a href="blog-single.html">See more ideas about Travel</a></h3>
                            <p class="card-description">
                                Proin eget tortor risus. Pellentesque in ipsum id orci porta dapibus. Praesent sapien massa,
                                convallis
                                a pellentesque nec,
                                egestas non nisi.
                            </p>
                        </div>
                        <div class="card-footer">
                            <div class="post-author">
                                <a href="#">
                                    <img src="img/testimonial-2.jpg" alt="" class="avatar rounded-circle">
                                    <span class="author">Morgan Freeman</span>
                                </a>
                            </div>
                            <div class="post-date">
                                <span class="ion-ios-clock-outline"></span> 10 min
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-blog">
                        <div class="card-img">
                            <a href="blog-single.html"><img src="img/post-2.jpg" alt="" class="img-fluid"></a>
                        </div>
                        <div class="card-body">
                            <div class="card-category-box">
                                <div class="card-category">
                                    <h6 class="category">Web Design</h6>
                                </div>
                            </div>
                            <h3 class="card-title"><a href="blog-single.html">See more ideas about Travel</a></h3>
                            <p class="card-description">
                                Proin eget tortor risus. Pellentesque in ipsum id orci porta dapibus. Praesent sapien massa,
                                convallis
                                a pellentesque nec,
                                egestas non nisi.
                            </p>
                        </div>
                        <div class="card-footer">
                            <div class="post-author">
                                <a href="#">
                                    <img src="img/testimonial-2.jpg" alt="" class="avatar rounded-circle">
                                    <span class="author">Morgan Freeman</span>
                                </a>
                            </div>
                            <div class="post-date">
                                <span class="ion-ios-clock-outline"></span> 10 min
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-blog">
                        <div class="card-img">
                            <a href="blog-single.html"><img src="img/post-3.jpg" alt="" class="img-fluid"></a>
                        </div>
                        <div class="card-body">
                            <div class="card-category-box">
                                <div class="card-category">
                                    <h6 class="category">Web Design</h6>
                                </div>
                            </div>
                            <h3 class="card-title"><a href="blog-single.html">See more ideas about Travel</a></h3>
                            <p class="card-description">
                                Proin eget tortor risus. Pellentesque in ipsum id orci porta dapibus. Praesent sapien massa,
                                convallis
                                a pellentesque nec,
                                egestas non nisi.
                            </p>
                        </div>
                        <div class="card-footer">
                            <div class="post-author">
                                <a href="#">
                                    <img src="img/testimonial-2.jpg" alt="" class="avatar rounded-circle">
                                    <span class="author">Morgan Freeman</span>
                                </a>
                            </div>
                            <div class="post-date">
                                <span class="ion-ios-clock-outline"></span> 10 min
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ Section Blog End /-->

    <!--/ Section Contact-Footer Star /-->
    <section class="paralax-mf footer-paralax bg-image sect-mt4 route" style="background-image: url(img/overlay-bg.jpg)">
        <div class="overlay-mf"></div>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="contact-mf">
                        <div id="contact" class="box-shadow-full">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="title-box-2">
                                        <h5 class="title-left">
                                            Send Message Us
                                        </h5>
                                    </div>
                                    <div>
                                        <form action="" method="post" role="form" class="contactForm">
                                            <div id="sendmessage">Your message has been sent. Thank you!</div>
                                            <div id="errormessage"></div>
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <div class="form-group">
                                                        <input type="text" name="name" class="form-control"
                                                            id="name" placeholder="Your Name" data-rule="minlen:4"
                                                            data-msg="Please enter at least 4 chars" />
                                                        <div class="validation"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <div class="form-group">
                                                        <input type="email" class="form-control" name="email"
                                                            id="email" placeholder="Your Email" data-rule="email"
                                                            data-msg="Please enter a valid email" />
                                                        <div class="validation"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="subject"
                                                            id="subject" placeholder="Subject" data-rule="minlen:4"
                                                            data-msg="Please enter at least 8 chars of subject" />
                                                        <div class="validation"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <div class="form-group">
                                                        <textarea class="form-control" name="message" rows="5" data-rule="required"
                                                            data-msg="Please write something for us" placeholder="Message"></textarea>
                                                        <div class="validation"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <button type="submit"
                                                        class="button button-a button-big button-rouded">Send
                                                        Message</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="title-box-2 pt-4 pt-md-0">
                                        <h5 class="title-left">
                                            Get in Touch
                                        </h5>
                                    </div>
                                    <div class="more-info">
                                        <p class="lead">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis dolorum dolorem
                                            soluta quidem
                                            expedita aperiam aliquid at.
                                            Totam magni ipsum suscipit amet? Autem nemo esse laboriosam ratione nobis
                                            mollitia inventore?
                                        </p>
                                        <ul class="list-ico">
                                            <li><span class="ion-ios-location"></span> 329 WASHINGTON ST BOSTON, MA 02108
                                            </li>
                                            <li><span class="ion-ios-telephone"></span> (617) 557-0089</li>
                                            <li><span class="ion-email"></span> contact@example.com</li>
                                        </ul>
                                    </div>
                                    <div class="socials">
                                        <ul>
                                            <li><a href=""><span class="ico-circle"><i
                                                            class="ion-social-facebook"></i></span></a></li>
                                            <li><a href=""><span class="ico-circle"><i
                                                            class="ion-social-instagram"></i></span></a></li>
                                            <li><a href=""><span class="ico-circle"><i
                                                            class="ion-social-twitter"></i></span></a></li>
                                            <li><a href=""><span class="ico-circle"><i
                                                            class="ion-social-pinterest"></i></span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="copyright-box">
                            <p class="copyright">&copy; Copyright <strong>DevFolio</strong>. All Rights Reserved</p>
                            <div class="credits">
                                <!--
                              All the links in the footer should remain intact.
                              You can delete the links only if you purchased the pro version.
                              Licensing information: https://bootstrapmade.com/license/
                              Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=DevFolio
                            -->
                                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </section>
    <!--/ Section Contact-footer End /-->

    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
    <div id="preloader"></div>

    <!-- JavaScript Libraries -->
    <script src="/lib/jquery/jquery.min.js"></script>
    <script src="/lib/jquery/jquery-migrate.min.js"></script>
    <script src="/lib/popper/popper.min.js"></script>
    <script src="/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="/lib/easing/easing.min.js"></script>
    <script src="/lib/counterup/jquery.waypoints.min.js"></script>
    <script src="/lib/counterup/jquery.counterup.js"></script>
    <script src="/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="/lib/lightbox/js/lightbox.min.js"></script>
    <script src="/lib/typed/typed.min.js"></script>

    <!-- Contact Form JavaScript File -->
    <script src="/contactform/contactform.js"></script>

    <!-- Template Main Javascript File -->
    <script src="/js/main.js"></script>
@endsection
