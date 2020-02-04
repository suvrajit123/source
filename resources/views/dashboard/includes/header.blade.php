<style>
    /* ul.navbar-nav li.navBtn ul.dropDownLog li.navBtn a.pbtn {padding: 6px 10px 6px 45px !important;
    border-radius: 0 !important; color: #fff !important; font-weight: 600;
    background: #a68720 url("../images/arow_new.png") 18px center no-repeat !important;
    background-size: auto;
    margin: 0;
    text-align: left;
    background-size: 10% !important;} */
</style>
<nav class="navbar navbar-expand-md bg-dark navbar-dark sticky-top">
    <a class="navbar-brand pl-5" href="{{ url('/') }}">
        <img src="{{ asset('irh_assets/images/irhsignika.png') }}" alt="" width="auto" height="45">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item px-3">
                <a class="nav-link" href="{{ route('theme.resources.filtered') }}">Resources</a>
            </li>
            <li class="nav-item px-2">
                <a class="nav-link" href="{{ url('/support-us') }}">Support Us</a>
            </li>
            <li class="nav-item px-2">
                <a class="nav-link" href="{{ url('/contactus') }}">Contact Us</a>
            </li>
            @if(Auth::user())
                <li class="nav-item px-2 navBtn">
                    <a class="btn button bg-yellow btn-block navButton" href="{{ Auth::user()->roles[0]->name == 'admin' ? route('admin.dashboard.index') : route('user.dashboard.index') }}">Welcome {!! Auth::user()->first_name !!}</a>
                    <ul class="dropDownLog">
                        <li class="nav-item px-2 navBtn"><a class="pbtn button bg-yellow btn-block navButton" href="{{ Auth::user()->roles[0]->name == 'admin' ? route('admin.create.resource') : route('user.create.resource') }}">Upload Resource</a></li>
                        <li class="nav-item px-2 navBtn"><a class="pbtn button bg-yellow btn-block navButton" href="{{ Auth::user()->roles[0]->name == 'admin' ?  route('admin.view.savedResource.list') : route('user.view.savedResource.list') }}">Saved Resource</a></li>
                        <li class="nav-item px-2 navBtn"><a class="pbtn button bg-yellow btn-block navButton" href="{{ Auth::user()->roles[0]->name == 'admin' ? route('admin.own.profile.form') : route('user.own.profile.form') }}">Edit Profile</a></li>
                        <li class="nav-item px-2 navBtn"><a class="pbtn button bg-yellow btn-block navButton" href="{{ route('theme.resources.authorprofile', Auth::user()->id) }}" target="_blank">Public Profile</a></li>
                        <li class="nav-item px-2 navBtn"><a class="pbtn button bg-yellow btn-block navButton" href="javascript:void(0)" data-toggle="modal" data-target="#oneTimeDonationModal">Donate to IRH</a></li>
                        <li class="nav-item px-2 navBtn"><a class="pbtn button bg-yellow btn-block navButton" href="{{ Auth::user()->roles[0]->name == 'admin' ? route('admin.own.profile.password') : route('user.own.profile.password') }}">Reset Password</a></li>
                        <li class="nav-item px-2 navBtn">
                            <a class="btn button bg-yellow btn-block navButton" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            @else
                <li class="nav-item px-2 navBtn">
                    <a class="btn button bg-yellow btn-block navButton" href="{{ url('/login') }}">Sign in</a>
                </li>
            @endif
        </ul>
    </div>
</nav>