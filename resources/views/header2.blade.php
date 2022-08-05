@php  
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" xmlns:og="http://ogp.me/ns#">
    <head>

 
    </head>

 

    <body>
 
 
 
        <div class="container-fluid top-line">
 
        
            <div style=" " class="mobile-parent-menu2 col-lg-6 col-md-6 col-sm-6 col-xs-10">


                  <form action="/search" method="GET" class="search_div">
                        @csrf
                        <div class="input-outer" style="background: #f6f6f6;margin: 5px 0px; border-radius: 40px;     width: 100%;  display: inline-block;">
                            <div  class="col-lg-11 col-md-10 col-sm-9 col-xs-9" style="padding: 0px;">
                                <input type="search" name="search" class="search_text form-control" value="" maxlength="128"  minlength="3"  required="required" style=" display: inline-block; padding: 0px 20px; margin: 0px; border-radius: 40px; border: 0px; height: 32px;">
                                <input type="hidden" name="page" value="1">

                            </div>
                            <div  class="col-lg-1 col-md-2 col-sm-3 col-xs-3" style="padding: 0px;">
                                <button type="submit" class="btn btn-border color-default" style="border: 0px; background-color:#bbe8b0; color: #165929; display: block;height: 32px;padding: 0px 6px;   width: auto;    border-radius: 40px;  " ><span>{{ __('messages.search') }}</span></button>
                            </div>

                            
                        </div> 
                    </form>

                    <form autocomplete="off" action="/search" method="GET">
                        @csrf
                        <div class="input-outer"   style="  margin: 15px 0px;  background: #f6f6f6; border-radius: 40px;  border: 1px solid #eee;  width: 100%;  display: inline-block;">
                            <div  class="col-lg-10 col-md-10 col-sm-10 col-xs-10" style="padding: 0px;">
                                <input type="search" name="search" id="search_input" class="search_text form-control" value="" maxlength="128" minlength="3" onkeyup="dosearch();" placeholder="{{ __('messages.keyword') }}"   required="required" style=" display: inline-block; padding: 0px 20px; margin: 0px; border-radius: 40px; border: 0px; height: 50px;">
                                <input type="hidden" name="page" value="1">

                            </div>
                            <div  class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="padding: 0px;">
                                <button type="submit" class="btn btn-border color-default" style="  background-color: #165929; color: #fff; display: block;height: 50px;padding: 0px 10px;  width: 100%;    padding: 10px ;  border-radius: 40px;    " ><span>{{ __('messages.search') }}</span></button>
                            </div>


                            <div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 0px;top: 80px; display: block; position: absolute; background: #f6f6f8;    width: auto;    z-index: 999;" id="search-result">


                            </div>

                            
                        </div> 
                    </form>
        
            </div>
        </div>
   

 

 
 

@yield('main_content')

@include('includes.footer2')