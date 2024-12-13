@extends('portal.master')

@section('content')

    @include('portal.includes.breakingnews')
    @include('portal.includes.coverimage')

    {{-- For Over Lay Image and the java script --}}

    {{-- @php
    $latestActiveAd = $ads->firstWhere('status', 1);
@endphp

@if ($latestActiveAd)
    <div id="popup-overlay">
        <div id="popup">
            <img src="{{ asset('uploads/images/ads/' . $latestActiveAd->image) }}" alt="Pop-up Image">
            <button id="close-btn">Close</button>
        </div>
    </div>
@endif

<div id="overlay"></div> --}}


{{-- @if (!empty($homeads))
<div id="popup-overlay">
    <div id="popup">
        <img src="{{ asset('uploads/images/ads/' . $homeads->image) }}" alt="Pop-up Image">
        <button id="close-btn">Close</button>
    </div>
</div>
@endif
<div id="overlay"></div> --}}


@if (!empty($homeads) && $homeads->status == 0)
    <div id="popup-overlay">
        <div id="popup">
            <img src="{{ asset('uploads/images/ads/' . $homeads->image) }}" alt="Pop-up Image">
            <button id="close-btn">Close</button>
        </div>
    </div>

<div id="overlay"></div>
@else
@endif

    <script>
        window.onload = function() {
            const popupAd = document.getElementById('popup-overlay');
            const overlay = document.getElementById('overlay');
            const body = document.body;

            // Function to show the pop-up ad and overlay
            function showPopup() {

                window.scrollTo(0, 0);
                popupAd.style.display = 'block';
                overlay.style.display = 'block';
                overlay.classList.add('active');
                body.style.overflow = 'hidden'; // Disable scrolling when the ad is active
            }

            // Function to hide the pop-up ad and overlay
            function hidePopup() {
                popupAd.style.display = 'none';
                overlay.style.display = 'none';
                overlay.classList.remove('active');
                body.style.overflow = 'auto'; // Enable scrolling when the ad is closed
            }

            // Function to be called on body onload
            function initializePage() {
                showPopup(); // Call the function to show the pop-up ad

                // Listen for messages from the ad
                window.addEventListener('message', (event) => {
                    if (event.data === 'closeAd') {
                        hidePopup();
                    }
                });

                // Call the additional function you have in your master blade
                updateClock(); // Assuming this is the function you have in your master blade
            }

            // Call the initializePage function on body onload
            initializePage();
        };

        // Code inside the ad to send a message to the main page when the close button is clicked
        const closeButton = document.getElementById('close-btn');
        closeButton.addEventListener('click', () => {
            // Send a message to the main page when the close button is clicked
            window.parent.postMessage('closeAd', '*');
        });
    </script>


    {{-- Samachar --}}
    @include('portal.includes.second')

    {{-- Rajniti--}}
    @include('portal.includes.third')

    {{--Artha/Vyapar--}}
    @include('portal.includes.sixth')

    {{--Khelkud--}}
    @include('portal.includes.seventh')

    {{--Swasthya/Sikshya--}}
    @include('portal.includes.first')
  
    {{--Kala/Sahitya--}}
    @include('portal.includes.fourth')

    {{--Pradesh/Sthaniya Taha--}} 
    @include('portal.includes.ninth')

    {{--Bidesh--}}
    @include('portal.includes.eighth')
   
    {{--Photo--}}
    @include('portal.includes.fifth')

    {{--Most Shared, Must Read, Rochak --}}
    @include('portal.includes.tenth')

@endsection
