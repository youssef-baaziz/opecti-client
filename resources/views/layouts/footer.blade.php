@extends('layouts.app')
@section('main-footer')
<footer class="footer-1 border-t-2 border-gray-700 bg-gray-700 py-8 pt-8 mt-8 text-gray-100 sm:py-12">
    <div class="container m-auto px-4">
        <div class="sm:flex sm:flex-wrap sm:-mx-4 mt-6 pt-6 sm:mt-12 sm:pt-12 border-t">
            <div class="sm:w-full px-4 md:w-1/6">
                <strong>Mohammed</strong>
            </div>
            <div class="px-4 sm:w-1/2 md:w-1/4 mt-4 md:mt-0">
                <h6 class="font-bold mb-2">Address</h6>
                <address class="not-italic mb-4 text-sm">
                    Casablanca.<br>
                    Maroc
                </address>
            </div>
            <div class="px-4 sm:w-1/2 md:w-1/4 mt-4 md:mt-0">
                <h6 class="font-bold mb-2">Contact</h6>
                <p class="mb-4 text-sm">
                    Email: <a href="mailto:info@example.com" class="text-blue-400 hover:underline">info@example.com</a><br>
                    Phone: <a href="tel:+1234567890" class="text-blue-400 hover:underline">+123 456 7890</a>
                </p>
            </div>
            <div class="px-4 sm:w-1/2 md:w-1/4 mt-4 md:mt-0">
                <h6 class="font-bold mb-2">Follow Us</h6>
                <div class="flex space-x-4">
                    <a href="#" class="text-blue-400 hover:text-blue-600"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-blue-400 hover:text-blue-600"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-blue-400 hover:text-blue-600"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-blue-400 hover:text-blue-600"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>
@endsection