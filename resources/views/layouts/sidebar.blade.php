<aside class="sidebar" id="sidebar">

    <!-- Logo -->
    <div class="sidebar-header">

        <div class="logo">

            <div class="logo-icon">
                <i class="bi bi-globe2"></i>
            </div>

            <div class="logo-text">

                <h5>Global Supply</h5>
                <span>Risk Intelligence</span>

            </div>

        </div>

    </div>

    <!-- Menu -->
    <ul class="sidebar-menu">

        <li class="menu-title">
            MENU UTAMA
        </li>

        <li>
            <a href="{{ route('dashboard') }}" class="active">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li>
            <a href="{{ route('country.index') }}">
                <i class="bi bi-globe-americas"></i>
                <span>Dashboard Negara</span>
            </a>
        </li>

        <li>
            <a href="{{ route('risk.index') }}">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>Analisis Risiko</span>
            </a>
        </li>

        <li>
            <a href="{{ route('weather.index') }}">
                <i class="bi bi-cloud-sun-fill"></i>
                <span>Monitoring Cuaca</span>
            </a>
        </li>

        <li>
            <a href="{{ route('currency.index') }}">
                <i class="bi bi-currency-exchange"></i>
                <span>Mata Uang</span>
            </a>
        </li>

        <li>
            <a href="{{ route('news.index') }}">
                <i class="bi bi-newspaper"></i>
                <span>News Intelligence</span>
            </a>
        </li>

        <li>
            <a href="{{ route('port.index') }}">
                <i class="bi bi-truck"></i>
                <span>Dashboard Pelabuhan</span>
            </a>
        </li>

        <li>
            <a href="{{ route('visualization.index') }}">
                <i class="bi bi-bar-chart-line-fill"></i>
                <span>Visualisasi Data</span>
            </a>
        </li>

        <li>
            <a href="{{ route('comparison.index') }}">
                <i class="bi bi-diagram-3-fill"></i>
                <span>Perbandingan Negara</span>
            </a>
        </li>

        <li>
            <a href="{{ route('favorite.index') }}">
                <i class="bi bi-star-fill"></i>
                <span>Negara Favorit</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.index') }}">
                <i class="bi bi-person-workspace"></i>
                <span>Administrator</span>
            </a>
        </li>

    </ul>

    <!-- Footer Sidebar -->

    <div class="sidebar-footer">

        <a href="{{ route('logout') }}">

            <i class="bi bi-box-arrow-right"></i>

            <span>Logout</span>

        </a>

    </div>

</aside>