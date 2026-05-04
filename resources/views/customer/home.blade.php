@extends('layouts.app')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
    <div>
        <h2 class="text-4xl font-bold mb-4">Pesan Makanan Favoritmu 🍔</h2>
        <p class="text-gray-600 mb-6">
            Nikmati berbagai menu lezat dengan harga terjangkau.  
            Pilih, pesan, dan makanan diantar langsung ke tempatmu!
        </p>
        <a href="/menu" class="px-6 py-3 bg-red-500 text-white rounded-lg shadow hover:bg-red-600 transition">
            Lihat Menu
            
        </a>
    </div>

    <div>
        <img src="https://i.ibb.co/6NmpJGg/food-illustration.png" class="w-full">
    </div>
</div>

@endsection
