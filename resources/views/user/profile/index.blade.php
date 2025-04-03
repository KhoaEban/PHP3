@extends('layouts.navbar_user')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-4">
                <div class="profile">
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : ($user->google_id ? 'https://www.google.com/s2/photos/profile/' . $user->google_id : 'https://placehold.co/96x96') }}"
                        alt="User profile picture">
                    <p class="name h3">{{ $user->name }}</p>
                    <button><i class="fas fa-edit"></i> <a href="{{ route('user.profile.edit') }}">Chỉnh sửa hồ
                            sơ</a></button>
                    <p class="email mt-3"><i class="fas fa-envelope"></i> {{ $user->email }}</p>
                    <p class="role"><i class="fas fa-user"></i> Vai trò:
                        {{ $user->role == 'admin' ? 'Quản trị viên' : 'Người dùng' }}</p>
                    <p class="joined"><i class="fas fa-calendar-alt"></i> Tham gia từ
                        {{ $user->created_at->diffForHumans() }}</p>
                </div>
            </div>
            <div class="col-8">
                <div class="row">
                    <div class="col-12">
                        <div class="courses">
                            <div class="header">
                                <p>Sản phẩm đã xem (0)</p>
                            </div>
                            <div class="grid">
                                {{-- Hiển thị sản phẩm đã xem nếu có --}}
                                @forelse($user->viewedProducts ?? [] as $product)
                                    <div class="course-card">
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}">
                                        <div class="content">
                                            <p class="title">{{ $product->name }}</p>
                                            <p class="price">{{ $product->price }} VNĐ</p>
                                        </div>
                                    </div>
                                @empty
                                    <p>Chưa có sản phẩm nào đã xem.</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="col-12 p-0">
                            <div class="courses">
                                <div class="header">
                                    <p>Sản phẩm đã mua (0)</p>
                                </div>
                                <div class="grid">
                                    @forelse($user->purchasedProducts ?? [] as $product)
                                        <div class="course-card">
                                            <img src="https://placehold.co/300x200" alt="React JS course image">
                                            <div class="content">
                                                <p class="title">React JS course</p>
                                                <p class="price">150.000 VNĐ</p>
                                                <div class="info">
                                                    <i class="fas fa-user"></i> Tác giả: Nguyễn Văn A
                                                    <i class="fas fa-folder"></i> Danh mục: Sách lập trình
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p>Chưa có sản phẩm nào được mua.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 16px;
    }

    .profile {
        display: flex;
        flex-direction: column;
        padding: 0 44px;
    }

    .profile img {
        width: 216px;
        height: 216px;
        border-radius: 50%;
        background-color: #e2e2e2;
        margin-bottom: 16px;
        margin: auto;
    }

    .profile p {
        margin: 4px 0;
    }

    .profile button {
        margin-top: 8px;
        padding: 8px 16px;
        background-color: #e2e2e2;
        border: none;
        border-radius: 9999px;
        font-size: 14px;
        font-weight: 500;
    }

    .activity {
        margin-top: 32px;
    }

    .activity p {
        font-size: 18px;
        font-weight: 600;
    }

    .activity .grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 4px;
        margin-top: 8px;
    }

    .activity .grid div {
        width: 16px;
        height: 16px;
        background-color: #e2e2e2;
    }

    .activity .options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 16px;
    }

    .activity .options select {
        margin-left: 8px;
        font-size: 14px;
        color: #4a4a4a;
    }

    .activity .legend {
        display: flex;
        align-items: center;
    }

    .activity .legend span {
        font-size: 12px;
        color: #4a4a4a;
    }

    .activity .legend div {
        width: 16px;
        height: 16px;
        margin-left: 4px;
    }

    .activity .legend .bg-gray {
        background-color: #e2e2e2;
    }

    .activity .legend .bg-green-200 {
        background-color: #c6f6d5;
    }

    .activity .legend .bg-green-400 {
        background-color: #68d391;
    }

    .activity .legend .bg-green-600 {
        background-color: #48bb78;
    }

    .activity .legend .bg-green-800 {
        background-color: #2f855a;
    }

    .courses {
        margin-top: 32px;
    }

    .courses .header {
        display: flex;
        align-items: center;
        border-bottom: 2px solid #e2e2e2;
    }

    .courses .header p {
        font-size: 18px;
        font-weight: 600;
    }

    .courses .grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 16px;
        margin-top: 16px;
    }

    @media (min-width: 768px) {
        .courses .grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1024px) {
        .courses .grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (min-width: 1280px) {
        .courses .grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .course-card {
        background-color: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    .course-card img {
        width: 100%;
        height: 160px;
        object-fit: cover;
    }

    .course-card .content {
        padding: 16px;
    }

    .course-card .content p {
        margin: 4px 0;
    }

    .course-card .content .title {
        font-size: 18px;
        font-weight: 600;
    }

    .course-card .content .price {
        color: #e53e3e;
        margin-top: 8px;
    }

    .course-card .content .info {
        display: flex;
        align-items: center;
        font-size: 14px;
        color: #4a4a4a;
        margin-top: 8px;
    }

    .course-card .content .info i {
        margin-right: 4px;
    }

    .course-card .content .info i+span {
        margin-left: 16px;
    }
</style>
