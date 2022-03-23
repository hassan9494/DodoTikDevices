@extends('layouts.front')

@section('meta')
    <!-- Primary Meta Tags -->
    <meta name="description" content="{{ $general->meta_desc }}">
    <meta name="keywords" content="{{ $general->keyword }}">
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="127.0.0.1:8000">
    <meta property="og:title" content="{{ $general->title }}">
    <meta property="og:description" content="{{ $general->meta_desc }}">
    <meta property="og:image" content="{{ asset('storage/'.$general->favicon) }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="127.0.0.1:8000">
    <meta property="twitter:title" content="{{ $general->title }}">
    <meta property="twitter:description" content="{{ $general->meta_desc }}">
    <meta property="twitter:image" content="{{ asset('storage/'.$general->favicon) }}">

@endsection

@section('content')
    <!-- ======= Hero Section ======= -->
    <section id="hero">
        <div id="heroCarousel" class="carousel slide carousel-fade" data-ride="carousel">

            <div class="carousel-inner" role="listbox">

                <div class="carousel-item active" style="background-image: url('{{ asset('front/img/1.jpg')}}')">
                    <div class="carousel-container">

                    </div>
                </div>
                <div class="carousel-item " style="background-image: url('{{ asset('front/img/2.jpg')}}')">
                    <div class="carousel-container">

                    </div>
                </div>
                <div class="carousel-item " style="background-image: url('{{ asset('front/img/3.jpg')}}')">
                    <div class="carousel-container">

                    </div>
                </div>


            </div>

            <a class="carousel-control-prev" href="#heroCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon icofont-simple-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>

            <a class="carousel-control-next" href="#heroCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon icofont-simple-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>

            <ol class="carousel-indicators" id="hero-carousel-indicators"></ol>

        </div>
    </section><!-- End Hero -->

    <main id="main">

        <!-- ======= about us Section ======= -->
        <section id="portfolio" class="portfolio">
            <div class="container">

                <div class="section-title">
                    <h2>TIPOS DE PEMPAs</h2>
                </div>
                <div class="col-lg-12 d-flex justify-content-center">
                    <ul id="portfolio-flters">
                        <li id="1" class="filter-active">PAV</li>
                        <li id="2">Mast Lifts</li>
                        <li id="3">Scissor Lifts</li>
                        <li id="4">Articulating Boom Lift</li>
                        <li id="5">Stick Boom Lifts</li>
                        <li id="6">Track Mounts</li>
                        <li id="7">Truck Mounts</li>
                    </ul>
                </div>
                <transition-group id="kinds" class="kinds" name="kinds" >

                    <div class="item active animate__animated animate__backInDown" id="1">
                        <h2 class="port-title">Reach height: <span>3.60m – 5.10m</span></h2>
                        <h3 class="port-title">IPAF category: <span>PAV</span></h3>
                        <p>
                            Push around vertical platforms, often called PAVs or personnel lifts, are a small type of mobile vertical lift with scissor operation. PAVs are ideal for indoor low level access making them suitable for smaller warehouses and factories, replacing ladders and steps that are not recommended for safe access.
                        </p>
                        <p>
                            They are compact and lightweight, which allows them to navigate aisles, doorways and narrow corners, and can be easily moved from location to location, including between floors in multistory buildings.
                        </p>
                        <p>
                            PAVs meet the requirements of the Work at Height regulations by ensuring the safety of workers on the platform with a low entry point.
                        </p>
                    </div>

                    <div class="item animate__animated animate__backInDown" id="2">
                        <h2 class="port-title">Reach height: <span> 4.20m – 10.00m</span></h2>
                        <h3 class="port-title">IPAF category: <span>3a & 3b</span></h3>
                        <p>
                        Mast lifts are a small type of cherry picker with boom lift operation. They are ideal for low level access in tight spaces and busy environments, such as in retail shops, offices, public buildings and hotels, where traditional cherry pickers or scissor lifts would not be able to access.
                        </p>
                        <p>
                        They are compact in design so can be used indoors and in restricted spaces without presenting a hazard to other workers, and, because they are small, they can be easily maneuvered into narrow aisles.
                        </p>
                        <p>
                        Mast lifts meet the requirements of the Work at Height regulations with a caged platform that provides access without the need for ladders/steps.
                        </p>
                    </div>
                    <div class="item animate__animated animate__backInDown" id="3">
                        <h2 class="port-title">Reach height: <span> 7.80m – 33.70m</span></h2>
                        <h3 class="port-title">IPAF category: <span>3a</span></h3>
                        <p>
                        Scissor lifts are large mobile vertical lifts (sometimes called ‘flying carpets’), similar to PAVs. Ideal for use in a variety applications in both indoor and outdoor spaces, where a straight lift is required for access.
                        </p>
                        <p>
                        Although narrow width models are available, usually scissor lifts would not be suitable for tighter spaces as they offer a much larger platform area for workers. They are also available as rough terrain versions and with double extending decks.
                        </p>
                        <p>
                        Scissor lifts meet the requirements of the Work at Height regulations with guard rails around the platform. They provide much safer access to workers required to carry out tasks at height.
                        </p>
                    </div>
                    <div class="item animate__animated animate__backInDown" id="4">
                        <h2 class="port-title">Reach height: <span> 10.00m – 43.15m</span></h2>
                        <h3 class="port-title">IPAF category: <span>3b</span></h3>
                        <p>
                        Articulated boom lifts, most often referred to as a cherry picker, offer an extensive range of movement.
                        </p>
                        <p>
                        The sideways outreach makes it a practical solution for both indoor and outdoor applications, as the extended reach enables the platform to maneuver around and over obstacles such as buildings and other equipment.
                        </p>
                        <p>
                        Available in a range of sizes, with additional features including rough terrain option, non-marking tyres and a variety of power options. Articulating boom lifts meet the requirements of the Work at Height regulations.
                        </p>
                    </div>
                    <div class="item animate__animated animate__backInDown" id="5">
                        <h2 class="port-title">Reach height: <span> 15.45m – 47.70m</span></h2>
                        <h3 class="port-title">IPAF category: <span>3b</span></h3>
                        <p>
                        Stick boom lifts, also commonly referred to as telescopic booms, offer an extensive range of movement similar to the articulating boom lift.
                        </p>
                        <p>
                        The platform can extend sideways, as well as offer great height, so stick booms are ideal for maneuvering around and above obstacles, machinery or buildings.  They can be used internally and externally with 4×4 and rough terrain options available.
                        </p>
                        <p>
                        Stick or telescopic boom lifts meet the requirements of the Work at Height regulations with a caged platform to ensure the safety of workers.
                        </p>
                    </div>
                    <div class="item animate__animated animate__backInDown" id="6">
                        <h2 class="port-title">Reach height: <span> 13.00m – 23.00m</span></h2>
                        <h3 class="port-title">IPAF category: <span>1b</span></h3>
                        <p>
                        Track mounted access lifts, sometimes called spider lifts, are the ideal solution for outdoor access requirements when the ground is uneven. They can also be useful on steep inclines, in narrow areas and even on stairs.
                        </p>
                        <p>
                        They are practical for low-point loading and offer extended height as well as outreach, and the tracked chassis spreads the weight more evenly reducing pressure on the ground, making them perfect for use on soft ground or delicate floors, such as suspended levels.
                        </p>
                        <p>
                        Track mounts meet the requirements of the Work at Height regulations with a caged platform to ensure the safety of workers.
                        </p>
                    </div>
                    <div class="item animate__animated animate__backInDown" id="7">
                        <h2 class="port-title">Reach height: <span> 20.00m – 55.00m</span></h2>
                        <h3 class="port-title">IPAF category: <span>1b</span></h3>
                        <p>
                        ruck mounted access lifts, also called lorry mounted platforms, are the perfect solution for multi-location projects, as they can travel between sites quickly and be ready to use immediately upon arrival.
                        </p>
                        <p>
                        They offer exceptional height capabilities, as well as side reach, making the great for outdoor applications and tasks, such as installing signage & telecoms systems, high-level maintenance, pest control and even filming and broadcasting.
                        </p>
                        <p>
                        Truck mounted access platforms meet the requirements of the Work at Height regulations with a caged platform to ensure the safety of workers.
                        </p>
                    </div>
                </transition-group>
            </div>
        </section>
        <!-- End about us Section -->
        <!-- ======= about us Section ======= -->
        <section id="portfolio" class="portfolio about">
            <div class="container">

                <div class="section-title">
                    <h2 >Inform About ANAPAT Training</h2>
                </div>

                <div class="row">
                    <ul class="hList">
                        <li>
                            <a href="#click" class="menu">
                                <h2 class="menu-title menu-title_2nd">about ANAPAT</h2>
                                <ul id="Inform-list" class="menu-dropdown col-md-3 col-sm-12" >
                                    <li class="li-active" id="1">about ANAPAT</li>
                                    <li id="2">Characteristics</li>
                                    <li id="3">certifications</li>
                                </ul>
                            </a>
                        </li>
                    </ul>



                <div class="col-md-9 col-sm-12 info info1 active animate__animated animate__backInLeft" data-aos="fade-up">
                        <h2><span>About us</span></h2>
                        <p>
                        The «National Association of Renters of Aerial Work Platforms» (ANAPAT) is a professional business organization of national scope, constituted in November 1993 to defend the general interests of the renters of mobile personal lifting platforms (PEMP, current denomination in force in Europe and now official in Spain), in accordance with the conception, design, manufacture and destination of these machines, usually operated by people outside the owner or lessor’s company and who depend on the lessee or user……..
                        </p>
                        <button>show more</button>
                    </div>
                    <div class="col-md-9 col-sm-12 info info2 animate__animated animate__backInLeft" data-aos="fade-up">
                        <h2><span>objectives</span></h2>
                        <p>
                        In just over twenty years, Mobile Personnel Platforms (PEMP) have become an essential work equipment for work at height. They are ideal machines for many jobs in construction, industry and services. They are safe equipment that is put into service with the corresponding CE marking that guarantees its compliance with the Machinery Directive, in addition the UNE-58921-IN report defines the instructions for installation, handling, maintenance, reviews and inspections for proper maintenance.
                        </p>
                        <button>show more</button>
                    </div>
                    <div class="col-md-9 col-sm-12 info info3 animate__animated animate__backInLeft" " data-aos="fade-up">
                        <h2><span>About us</span></h2>
                        <p>
                        The «National Association of Renters of Aerial Work Platforms» (ANAPAT) is a professional business organization of national scope, constituted in November 1993 to defend the general interests of the renters of mobile personal lifting platforms (PEMP, current denomination in force in Europe and now official in Spain), in accordance with the conception, design, manufacture and destination of these machines, usually operated by people outside the owner or lessor’s company and who depend on the lessee or user……..
                        </p>
                        <button>show more</button>
                    </div>
                </div>


            </div>
        </section>
        <!-- End about us Section -->
        <!-- ======= Our Clients Section ======= -->
        <section id="clients" class="clients">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h2>Our Partner</h2>
                </div>

                <div class="row no-gutters clients-wrap clearfix" data-aos="fade-up">


                    @foreach($partner as $partner)
                        <div class="col-lg-3 col-md-4 col-6">
                            <div class="client-logo">
                                <a href="{{ route('partner',$partner->id) }}"  rel="noopener noreferrer">
                                    <img src="{{ asset('storage/'.$partner->cover) }}" class="img-fluid" alt="">
                                    <h2 style="text-align: center;"> {{$partner->name}}</h2>
                                </a>
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>
        </section><!-- End Our Clients Section -->

    </main><!-- End #main -->
@endsection
