<header class="app-header"><a class="app-header__logo" href="{{route('dashboard')}}">Zouple</a>
    <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">

        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
            <ul class="dropdown-menu settings-menu dropdown-menu-right">
                <!-- <li><a class="dropdown-item" href="page-user.html"><i class="fa fa-cog fa-lg"></i> Settings</a></li>
            <li><a class="dropdown-item" href="page-user.html"><i class="fa fa-user fa-lg"></i> Profile</a></li>-->
                <li><a class="dropdown-item" href="{{route('logout')}}"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
            </ul>
        </li>
    </ul>
</header>
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="{{URL::asset('public/img/logo.png')}}" alt="User Image" width="40%">
        <div>
            <p class="app-sidebar__user-name">{{Auth::user()->name}}</p>
            <p class="app-sidebar__user-designation">Admin Panel</p>
        </div>
    </div>
    <ul class="app-menu">
        <li><a class="app-menu__item" href="{{route('dashboard')}}"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>

        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-video-camera"></i><span class="app-menu__label">Video </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{route('mainVideo')}}"><i class="icon fa fa-circle-o"></i> Main Video</a></li>
                <li><a class="treeview-item" href="{{route('slider_mange')}}"><i class="icon fa fa-circle-o"></i>Main Text Slider</a></li>
                <li><a class="treeview-item" href="{{route('subVideo')}}"><i class="icon fa fa-circle-o"></i> Sub Video </a></li>
                
            </ul>
        </li>
        
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-file-image-o"></i><span class="app-menu__label">Banner </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{route('banner')}}"><i class="icon fa fa-circle-o"></i> Page Banner</a></li>
                <li><a class="treeview-item" href="{{route('loginBanner')}}"><i class="icon fa fa-circle-o"></i>Login Banner</a></li>
                <li><a class="treeview-item" href="{{route('offer')}}"><i class="icon fa fa-circle-o"></i> Advertisement Banner </a></li>
                <li><a class="treeview-item" href="{{route('flashBanner')}}"><i class="icon fa fa-circle-o"></i> Flash Banner </a></li>
                
            </ul>
        </li>
        
        <li><a class="app-menu__item" href="{{route('about')}}"><i class="app-menu__icon fa fa-info-circle"></i><span class="app-menu__label">About Us</span></a></li>
        
        <li><a class="app-menu__item" href="{{route('cms_page')}}"><i class="app-menu__icon fa fa-file-text"></i><span class="app-menu__label">CMS Page</span></a></li>

        <li><a class="app-menu__item" href="{{route('blog_page')}}"><i class="app-menu__icon fa fa-rss"></i><span class="app-menu__label">Blog</span></a></li>
        
        <li><a class="app-menu__item" href="{{route('vendor')}}"><i class="app-menu__icon fa fa-user-o"></i><span class="app-menu__label">Vendor</span></a></li>

        <li><a class="app-menu__item" href="{{route('currency')}}"><i class="app-menu__icon fa fa-money"></i><span class="app-menu__label">Currency </span></a></li>
        
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">All Forms</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">

                <li><a class="treeview-item" href="{{route('userlist')}}" rel="noopener"><i class="icon fa fa-circle-o"></i> User List</a></li>
                <li><a class="treeview-item" href="{{route('newsubscribers')}}"><i class="icon fa fa-circle-o"></i> Subscribers For NewsLetter </a></li>
                 <li><a class="treeview-item" href="{{route('contact_information')}}" rel="noopener"><i class="icon fa fa-circle-o"></i> Contact Us</a></li>
                <li><a class="treeview-item" href="{{route('messageSend')}}"><i class="icon fa fa-circle-o"></i> Ask Me</a></li>
                
                <li><a class="treeview-item" href="{{route('getNotification')}}"><i class="icon fa fa-circle-o"></i> Get Notification</a></li>
                
                <li><a class="treeview-item" href="{{route('review_information')}}"><i class="icon fa fa-circle-o"></i> Review</a></li>
                
            </ul>
        </li>
        
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-sitemap"></i><span class="app-menu__label">Catelogue</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">

                <li><a class="treeview-item" href="{{route('attribute')}}" rel="noopener"><i class="icon fa fa-circle-o"></i> Attributes</a></li>
                <li><a class="treeview-item" href="{{route('category_list')}}"><i class="icon fa fa-circle-o"></i> Categories</a></li>
                <li><a class="treeview-item" href="{{route('product_list')}}" rel="noopener"><i class="icon fa fa-circle-o"></i> Products</a></li>

            </ul>
        </li>
        
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-cart-plus"></i><span class="app-menu__label">Orders</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{route('order_information')}}"><i class="icon fa fa-circle-o"></i> Order List</a></li>
                <li><a class="treeview-item" href="{{route('order_report')}}"><i class="icon fa fa-circle-o"></i> Order Report</a></li>
            </ul>
        </li>
        
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-gift"></i><span class="app-menu__label">Coupon </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{route('productCoupon')}}"><i class="icon fa fa-circle-o"></i> Product Coupon </a></li>
                <li><a class="treeview-item" href="{{route('customerCoupon')}}"><i class="icon fa fa-circle-o"></i> Customer Coupon </a></li>
                <li><a class="treeview-item" href="{{route('pricesCoupon')}}"><i class="icon fa fa-circle-o"></i> Prices Coupon </a></li>
            </ul>
        </li>
        
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-shirtsinbulk"></i><span class="app-menu__label">Shirt Design</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">

                <li><a class="treeview-item" href="{{route('shirtCategory')}}" rel="noopener"><i class="icon fa fa-circle-o"></i> Febric</a></li>
                <li><a class="treeview-item" href="{{route('shirtAttribut')}}"><i class="icon fa fa-circle-o"></i> Element</a></li>
                <li><a class="treeview-item" href="{{route('attributValue')}}" rel="noopener"><i class="icon fa fa-circle-o"></i> Element Value</a></li>
                <li><a class="treeview-item" href="{{route('shirtSize')}}" rel="noopener"><i class="icon fa fa-circle-o"></i> Shirt Size</a></li>
                <li><a class="treeview-item" href="{{route('customerShirt')}}" rel="noopener"><i class="icon fa fa-circle-o"></i> Home Customize Shirt</a></li>

            </ul>
        </li>

        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-cog"></i><span class="app-menu__label">Setting</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{route('siteinformationUpdate')}}"><i class="icon fa fa-circle-o"></i> Site Profile</a></li>
                <!-- <li><a class="treeview-item" href="{{route('mail_page')}}" rel="noopener"><i class="icon fa fa-circle-o"></i> Mail Setting</a>-->
        </li>
        <li><a class="treeview-item" href="{{route('pincode')}}"><i class="icon fa fa-circle-o"></i> Pincode</a></li>
        <li><a class="treeview-item" href="{{route('change_admin_panel')}}"><i class="icon fa fa-circle-o"></i> Change Password</a></li>
    </ul>
    </li>







    </ul>
</aside>
