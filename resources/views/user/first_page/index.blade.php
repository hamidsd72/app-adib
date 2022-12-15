@extends('layouts.layout_first_page')
@section('content')

    <!-- Home Section -->
	<section id="home">
		
        <!-- Navigation Section -->
        <section id="navigation" class="shadow">
            <!-- Content -->
            <div class="content navigation">
                <!-- Logo -->
                <div class="logo">
                    <a class="scroll" href="#home"><img src="{{ asset('assets/test-image/logo.png')}}" alt="Logo"/></a>
                </div>
                <!-- End Logo -->
                
                <!-- Nav Menu -->
                <div class="nav-menu">
                    <ul class="nav main-nav">
                        <li class="active"><a class="scroll" href="#footer">Home</a></li>
                        <li><a class="scroll" href="#important_links">About</a></li>
                        <li><a class="scroll" href="#history">History</a></li>
                        <li><a class="scroll" href="#team">Team</a></li>
                        <li><a class="scroll" href="#our-clients">Clients</a></li>
                        <li><a class="scroll" href="#services">Services</a></li>
                        <li><a class="scroll" href="#portfolio">Portfolio</a></li>
                        <li class="dropdown-toggle nav-toggle"><a class="scroll" href="#prices">Prices</a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Prices 1</a></li>
                                <li><a href="#">Prices 2</a></li>
                                <li><a href="#">Prices 3</a></li>
                            </ul>
                        </li>                    
                        <li><a class="scroll" href="#contact">Contact</a></li>				
                    </ul>
                </div>
                <!-- End Nav Menu -->
                
                <!-- Dropdown Menu For Mobile Devices-->
                <div class="dropdown mobile-drop">
                    <a data-toggle="dropdown" class="mobile-menu" href="#"><i class="fa fa-bars"></i></a>
                    <ul class="nav dropdown-menu fullwidth" role="menu" >
                        <li><a class="scroll" href="#home">Home</a></li>
                        <li><a class="scroll" href="#important_links">About</a></li>
                        <li><a class="scroll" href="#history">History</a></li>
                        <li><a class="scroll" href="#team">Team</a></li>
                        <li><a class="scroll" href="#our-clients">Clients</a></li>
                        <li><a class="scroll" href="#services">Services</a></li>
                        <li><a class="scroll" href="#portfolio">Portfolio</a></li>                                               
                        <li class="dropdown-toggle mobile-toggle"><a class="scroll" href="#prices">Prices</a>
                        	<ul class="dropdown-menu dr-mobile">
                                <li><a href="#">Prices 1</a></li>
                                <li><a href="#">Prices 2</a></li>
                                <li><a href="#">Prices 3</a></li>
                            </ul>
                        </li>                    
                        <li><a class="scroll" href="#contact">Contact</a></li>
                    </ul>
                </div>
                <!-- End Dropdown Menu For Mobile Devices-->
                <div class="clear"></div>
            </div>
            <!-- End Content -->
        </section>
        <!-- End Navigation Section -->
	
	</section>
    <!-- End Intro Section -->


    <section id="one">
        <div id="demo" class="carousel slide mx-2" data-ride="carousel">
            <ul class="carousel-indicators">
                @for ($i = 0; $i < $sliders->count(); $i++)
                    @if ($i == 0)
                        <li data-target="#demo" data-slide-to="0" class="active"></li> 
                    @else
                        <li data-target="#demo" data-slide-to="{{$i}}"></li>
                    @endif
                @endfor
            </ul>
            <div class="carousel-inner">
                @foreach ($sliders as $slider)
                    @if ($sliders[0]->id == $slider->id)
    
                        <div class="carousel-item active">
                            <a href="{{$slider->link}}" target="_blank" >
                                <img src="{{$slidersPhotos->where('pictures_id', $slider->id)->first()->path}}" alt="Los Angeles" ">
                                <div class="carousel-caption p-0 pt-2">
                                    <h4 class="text-light mx-3">{{$slider->title}}</h4>
                                </div>   
                            </a>
                        </div>
                        
                    @else
                    
                        <div class="carousel-item">
                            <a href="{{$slider->link}}" target="_blank" >
                                <img src="{{$slidersPhotos->where('pictures_id', $slider->id)->first()->path}}" alt="Los Angeles" ">
                                <div class="carousel-caption p-0 pt-2">
                                    <h4 class="text-light mx-3">{{$slider->title}}</h4>
                                </div>   
                            </a>
                        </div>
    
                    @endif
    
                @endforeach
            </div>
        </div>
    </section>


    @include('../auth/register2')
    
	<!-- Footer Section -->
	<section id="footer" class="main-content">
		<div class="content footer">
            <div class="col-xs-12 align-center">
            
                <!-- Go To Top -->
                <div id="go-top" class="animated" data-animation="fadeInUp" data-animation-delay="0">
                    <a href="#home" class="scroll"><i class="fa fa-chevron-up"></i></a>
                </div><!-- End Go To Top -->
                
                <span class="footer-logo animated" data-animation="fadeInUp" data-animation-delay="500">Arwen</span>
                <!-- Site Copyright -->
				<p class="footer-text copyright animated" data-animation="fadeInUp" data-animation-delay="700">
					Copyright © 2014 - Arwen. All Rights Reserved.
				</p><!-- End Site Copyright -->
                
            </div>						
			<div class="clear"></div>
		</div> 
        <!-- End Footer Content -->
	</section>
    <!-- End Footer Section -->
		
@endsection
