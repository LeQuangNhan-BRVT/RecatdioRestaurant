<aside class="main-sidebar sidebar-dark-primary elevation-4">
				<!-- Brand Logo -->
				<a href="{{ route('admin.dashboard') }}" class="brand-link">
					<img src="{{ asset('admin-assets/img/logo.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
					<span class="brand-text font-weight-light">Nhà hàng Recatdio</span>
				</a>
				<!-- Sidebar -->
				<div class="sidebar">
					<!-- Sidebar user (optional) -->
					<nav class="mt-2">
						<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
							<!-- Add icons to the links using the .nav-icon class
								with font-awesome or any other icon font library -->
							<li class="nav-item">
								<a href="{{ route('admin.dashboard') }}" class="nav-link">
									<i class="nav-icon fas fa-tachometer-alt"></i>
									<p>Tổng quan</p>
								</a>																
							</li>
							<li class="nav-item">
								<a href="{{ route('categories.index') }}" class="nav-link">
									<i class="nav-icon fas fa-file-alt"></i>
									<p>Loại món ăn</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="{{ route('menu.index') }}" class="nav-link">
									<i class="nav-icon fas fa-file-alt"></i>
									<p>Thực đơn</p>
								</a>
							</li>
							
													
							<li class="nav-item">
								<a href="{{ route('admin.bookings.index') }}" class="nav-link">
									<i class="nav-icon fas fa-calendar-alt"></i>
									<p>Đặt bàn</p>
								</a>
							</li>
							
							<li class="nav-item">
								<a href="#" class="nav-link">
									<i class="nav-icon fas fa-users"></i>
									<p>
										Quản lý người dùng
										<i class="right fas fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<li class="nav-item">
										<a href="{{ route('admin.users.index') }}" class="nav-link">
											<i class="far fa-circle nav-icon"></i>
											<p>Khách hàng</p>
										</a>
									</li>
									<li class="nav-item">
										<a href="{{ route('admin.admins.index') }}" class="nav-link">
											<i class="far fa-circle nav-icon"></i>
											<p>Quản trị viên</p>
										</a>
									</li>
								</ul>
							</li>
														
						</ul>
					</nav>
					<!-- /.sidebar-menu -->
				</div>
				<!-- /.sidebar -->
         	</aside>