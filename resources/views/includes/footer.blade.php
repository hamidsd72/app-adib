<footer class="footer mt-auto pb-0 pt-2 text-center">
    <div class="container">
        <div class="row mb-0">
            <div class="col text-dark">{{$setting->title}}</div>
            <div class="col">
                @foreach ($network as $net)
                    @switch($net->config)
                        @case("instagram")
                            <a href="{{$net->address}}" class="box mx-2">
                                <i class="fab fa-instagram" style="font-size: 20px;"></i>
                            </a>
                            @break
                        @case("whatsapp")
                            <a href="{{$net->address}}" class="box mx-2">
                                <i class="fab fa-whatsapp" style="font-size: 20px;"></i>
                            </a>
                            @break
                        @case("email")
                            <a href="#" onclick='sedarMail("{{$net->address}}")' class="box mx-2">
                                <i class="fa fa-envelope" style="font-size: 20px;"></i>
                            </a>
                            @break
                    @endswitch
                @endforeach
            </div>
            
        </div>
    </div>
    <div class="container text-center">
        <span class="text-secondary">All rights reserved by AdibGroup 2022</span>
    </div>
</footer>

<script>
    function sedarMail(mail) {
        location.href = "mailto:"+mail;
    }
</script>