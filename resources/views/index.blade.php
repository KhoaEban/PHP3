@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 p-0">
                {{-- Category --}}
                <div class="card">
                    <div class="card-header">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" style="width: 30px; height: 30px;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        Danh mục
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">Danh mục 1</li>
                            <li class="list-group-item">Danh mục 2</li>
                            <li class="list-group-item">Danh mục 3</li>
                            <li class="list-group-item">Danh mục 1</li>
                            <li class="list-group-item">Danh mục 2</li>
                            <li class="list-group-item">Danh mục 3</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8 p-0">
                <div class="slider-container">
                    <div class="slider">
                        <div class="slide"><img src="{{ asset('images/web-1012467_1280.webp') }}" alt="Slide 1"></div>
                        <div class="slide"><img
                                src="{{ asset('images/360_F_888069452_7loiTnJ09mbvWnWbPhNKtCAuoeGIiNql.jpg') }}"
                                alt="Slide 2"></div>
                        <div class="slide"><img
                                src="{{ asset('images/360_F_598732082_xlL59GmevM6BeWOt3tY7Ea98ZDBiYewH.jpg') }}"
                                alt="Slide 3"></div>
                    </div>

                    <!-- Nút điều khiển -->
                    <button class="prev" onclick="prevSlide()">&#10094;</button>
                    <button class="next" onclick="nextSlide()">&#10095;</button>
                </div>
            </div>
        </div>

        {{-- Danh mục nổi bật --}}
        <br>
        <div class="row">
            <div class="col-md-12 p-0">
                <h1 class="text-center">Danh mục nổi bật</h1>
            </div>
            <div class="col-md-12 p-0 mt-3">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('images/360_F_598732082_xlL59GmevM6BeWOt3tY7Ea98ZDBiYewH.jpg') }}"
                                class="card-img-top" alt="Slide 1">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the
                                    bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('images/360_F_598732082_xlL59GmevM6BeWOt3tY7Ea98ZDBiYewH.jpg') }}"
                                class="card-img-top" alt="Slide 1">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the
                                    bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('images/360_F_598732082_xlL59GmevM6BeWOt3tY7Ea98ZDBiYewH.jpg') }}"
                                class="card-img-top" alt="Slide 1">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the
                                    bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
    </div>
@endsection

<style>
    /* Định dạng slider */
    .slider-container {
        width: 100%;
        max-width: 850px;
        overflow: hidden;
        margin: auto;
        position: relative;
    }

    /* Dàn layout ảnh trong slider */
    .slider {
        display: flex;
        width: 100%;
        /* Không cần width: 300% */
        transition: transform 0.5s ease-in-out;
    }

    /* Mỗi ảnh chiếm chính xác 100% chiều rộng slider-container */
    .slide {
        flex: 0 0 100%;
    }

    .slide img {
        width: 100%;
        height: auto;
    }

    /* Nút điều khiển */
    .prev,
    .next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        padding: 10px 18px;
        cursor: pointer;
        font-size: 18px;
        border-radius: 50%;
        transition: background-color 0.3s ease-in-out;

    }

    .prev {
        left: 10px;
    }

    .next {
        right: 10px;
    }

    .prev:hover,
    .next:hover {
        background-color: rgba(0, 0, 0, 0.8);
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let currentIndex = 0;
        const slider = document.querySelector('.slider');
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;

        function updateSlide() {
            slider.style.transform = `translateX(-${currentIndex * 100}%)`;
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % totalSlides;
            updateSlide();
        }

        function prevSlide() {
            currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
            updateSlide();
        }

        // Tự động chuyển slide sau mỗi 3 giây
        setInterval(nextSlide, 3000);

        // Gán sự kiện cho nút bấm
        document.querySelector('.prev').addEventListener('click', prevSlide);
        document.querySelector('.next').addEventListener('click', nextSlide);
    });
</script>
