<div id="footer-bar" class="footer-bar-1" >
    <a href="{{ route('user.contact.show') }}" class="{{ \Request::route()->getName() == 'user.contact.show' ? 'active-nav' : '' }}">
        <img src="https://img.icons8.com/ultraviolet/28/000000/forgot-password.png"/>
        <span>درباره ما</span></a>
    <a href="{{ route('user.works') }}" class="{{ \Request::route()->getName() == 'user.works' ? 'active-nav' : '' }}">
        <img src="https://img.icons8.com/external-icematte-lafs/28/000000/external-Monitoring-it-icematte-lafs.png"/>
        <span>کار ها</span></a>
    <a href="{{ route('user.index') }}" class="{{ \Request::route()->getName() == 'user.index' ? 'active-nav' : '' }}">
        <div class="home_route">
            <img src="https://img.icons8.com/external-icematte-lafs/36/000000/external-Home-it-icematte-lafs.png"/>
            <span style="font-size: 14px;">خانه</span>
        </div>
    </a>
    <a href="{{ route('user.ticket.index') }}" class="{{ \Request::route()->getName() == 'user.ticket.index' ? 'active-nav' : '' }}">
        <img src="https://img.icons8.com/ultraviolet/28/000000/edit-property.png"/>
        <span>تیکت ها</span>
    </a>
    <a href="{{ route('admin.profile.show') }}" class="{{ \Request::route()->getName() == 'admin.profile.show' ? 'active-nav' : '' }}" >
        <img src="https://img.icons8.com/ultraviolet/28/000000/guest-male.png"/>
        <span>پروفایل</span>
    </a>
</div>
