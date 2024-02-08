<nav id="sidebar">
    <div class="sidebar-header d-flex align-items-center">
        <div class="avatar"><img src="img/avatar-6.jpg" alt="..." class="img-fluid rounded-circle"></div>
        <div class="title">
            <h1 class="h5">Mark Stephen</h1>
            <p>Web Designer</p>
        </div>
    </div>
    <span class="heading">Main</span>
    <ul class="list-unstyled">
        <li class="<?= (url_is('/') ? 'active' : '' ) ?>"><a href="<?= site_url('/') ?>"> <i class="icon-home"></i>Home </a></li>
        <li class="<?= (url_is('users*') ? 'active' : '' ) ?>"><a href="<?= site_url('users') ?>"> <i class="icon-user"></i>Usuários </a></li>
</nav>