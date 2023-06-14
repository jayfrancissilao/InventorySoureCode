<style>
    .main-sidebar{
        background-color: greenyellow;
    }
    .black {
        color:black;
    }
</style>

<aside class="main-sidebar sidebar-white-primary elevation-4">
    <!-- Brand Logo -->
    <a href="javascript:void(0)" class="brand-link">
        <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Ecommerce Aljay</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
            </div>

            <div class="info">
                <a href="#" class="d-block">Admin</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                <li class="nav-header">Dashboard</li>
                <li class="nav-item">
                    <a href="{{route('categories.index')}}" class="nav-link">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p class="black">
                            Categories
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('products.index')}}" class="nav-link">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p class="black">
                            Products
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('customers.index')}}" class="nav-link">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p class="black">
                            Customers
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('addresses.index')}}" class="nav-link">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p class="black">
                            Addresses
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('payments.index')}}" class="nav-link">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p class="black">
                            payment
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('order_details.index')}}" class="nav-link">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p class="black">
                            order_details
                        </p>
                    </a>
                </li>




            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>