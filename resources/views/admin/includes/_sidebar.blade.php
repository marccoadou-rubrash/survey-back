<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <div id="sidebar-menu">
            <ul class="list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="{{ route('admin.dashboard') }}" class="waves-effect">
                        <i class="fa-solid fa-chart-simple"></i>
                        <span>Dashboard</span>
                    </a>
                </li>


                <li>
                    <a href="{{route("admin.surveys.list")}}" class="waves-effect">
                        <i class="fa-regular fa-users"></i>
                        <span>Surveys</span>
                    </a>
                </li>

                <li>
                    <a href="{{route("admin.companies.list")}}" class="waves-effect">
                        <i class="fa-regular fa-building"></i>
                        <span>Companies</span>
                    </a>
                </li>

            </ul>
        </div>

    </div>
</div>
