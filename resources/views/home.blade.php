<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="{{asset('css/style.css')}}" />
        <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous"
        />
        <title>Rainbow</title>
    </head>
    <body>
        <section class="header">
            <div class="container">
                <div class="row d-flex justify-content-between align-items-center">
                    <div class="col-md-4"></div>
                    <div class="col-md-4 d-flex justify-content-center align-items-center">
                        <div class="logo">
                            <img src="images/rlogo.png" alt="logo" />
                        </div>
                    </div>
                    <div class="col-md-4 contact ">
                        <div class="phone d-flex align-items-center gap-2">
                            <img src="images/call.png" alt="phone" />1800-2122-22
                        </div>
                        <div class="mob-txt">
                            <P>Hyderabad’s Trusted Partner in Women & Child Healthcare.</P>
                            <a href="#">Call Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="locations">
            <div class="container">
                <div class="row">
                    <div class="loc-heading text-center">Please Select the Nearest Unit</div>
                </div>
                <div class="locations-grid">
                    @if(isset($branches))
                        @foreach($branches as $key=>$branch)
                            <div class="location-card">
                                <img src="{{config(key: 'costomVar.cms_url').($branch->our_centre_bg_image_mobile)}}" alt="{{$branch->name}}"/>
                                <div class="overlay"></div>
                                <a href="{{route('branch.doctors', [$branch->region->slug, $branch->slug])}}">
                                    <div class="location-label">
                                        <span>{{$branch->name}}</span>
                                        <div class="arrow-btn">
                                            <img src="images/arrow.png" alt="arrow">
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>
</html>
