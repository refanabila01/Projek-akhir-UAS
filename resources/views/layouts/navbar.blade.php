<nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top">

    <div class="container-fluid">

        <span class="fw-bold fs-4 text-primary">

            🌍 Supply Chain Risk

        </span>

        <div class="ms-auto d-flex align-items-center">

            <i class="bi bi-bell fs-5 me-4"></i>

            <div class="text-end">

                <strong>{{ Auth::user()->name }}</strong>

                <br>

                <small class="text-muted">

                    {{ Auth::user()->role }}

                </small>

            </div>

        </div>

    </div>

</nav>